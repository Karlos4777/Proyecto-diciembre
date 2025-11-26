<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Catalogo;
use App\Http\Requests\ProductoRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;
use App\Services\SpotifyService;

class ProductoController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('producto-list'); 
        $texto = $request->input('texto');

        $registros = Producto::with(['categoria', 'catalogo'])
            ->where('nombre', 'like', "%{$texto}%")
            ->orWhere('codigo', 'like', "%{$texto}%")
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('producto.index', compact('registros','texto'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('producto-create'); 
        $categorias = Categoria::all();
        $catalogos  = Catalogo::all();
        return view('producto.action', compact('categorias','catalogos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductoRequest $request)
    {
        $this->authorize('producto-create'); 

        $registro = new Producto();
        $registro->codigo       = $request->input('codigo');
        $registro->barcode      = $request->input('barcode');
        $registro->nombre       = $request->input('nombre');
        $registro->precio       = $request->input('precio');
    $registro->cantidad       = $request->input('cantidad');
    $registro->descuento      = $request->input('descuento', 0); // nuevo campo descuento
        $registro->categoria_id = $request->input('categoria_id');
        $registro->catalogo_id  = $request->input('catalogo_id'); // <- agregado
        $registro->descripcion  = $request->input('descripcion');
        $registro->artista      = $request->input('artista');
        $registro->album        = $request->input('album');
        $registro->preview_url  = $request->input('preview_url');

        $sufijo = strtolower(Str::random(2));
        $image = $request->file('imagen');
        if (!is_null($image)){            
            $nombreImagen = $sufijo.'-'.$image->getClientOriginalName();
            $image->move('uploads/productos', $nombreImagen);
            $registro->imagen = $nombreImagen;
        }

        $registro->save();
        return redirect()->route('productos.index')->with('mensaje', 'Registro '.$registro->nombre. ' agregado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->authorize('producto-edit'); 
        $categorias = Categoria::all();
        $catalogos  = Catalogo::all(); // <- agregado
        $registro   = Producto::findOrFail($id);
        return view('producto.action', compact('registro','categorias','catalogos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductoRequest $request, $id)
    {
        $this->authorize('producto-edit'); 

        $registro = Producto::findOrFail($id);
        $registro->codigo       = $request->input('codigo');
        $registro->barcode      = $request->input('barcode');
        $registro->nombre       = $request->input('nombre');
        $registro->precio       = $request->input('precio');
    $registro->cantidad       = $request->input('cantidad');
    $registro->descuento      = $request->input('descuento', 0); // nuevo campo descuento
        $registro->categoria_id = $request->input('categoria_id');
        $registro->catalogo_id  = $request->input('catalogo_id'); // <- agregado
        $registro->descripcion  = $request->input('descripcion');
        $registro->artista      = $request->input('artista');
        $registro->album        = $request->input('album');
        $registro->preview_url  = $request->input('preview_url');

        $sufijo = strtolower(Str::random(2));
        $image = $request->file('imagen');
        if (!is_null($image)){            
            $nombreImagen = $sufijo.'-'.$image->getClientOriginalName();
            $image->move('uploads/productos', $nombreImagen);

            $old_image = 'uploads/productos/'.$registro->imagen;
            if (file_exists($old_image)) {
                @unlink($old_image);
            }

            $registro->imagen = $nombreImagen;
        }

        $registro->save();
        return redirect()->route('productos.index')->with('mensaje', 'Registro '.$registro->nombre. ' actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('producto-delete');
        $registro = Producto::findOrFail($id);

        $old_image = 'uploads/productos/'.$registro->imagen;
        if (file_exists($old_image)) {
            @unlink($old_image);
        }

        $registro->delete();
        return redirect()->route('productos.index')->with('mensaje', $registro->nombre. ' eliminado correctamente.');
    }

public function buscarProductosAjax(Request $request)
{
    $search = $request->input('search');

    if (!$search || strlen($search) < 2) {
        return response()->json([]);
    }

    $productos = \App\Models\Producto::with(['categoria', 'catalogo'])
        ->where('nombre', 'like', "%{$search}%")
        ->orWhere('descripcion', 'like', "%{$search}%")
        ->orWhere('codigo', 'like', "%{$search}%")
        ->take(10)
        ->get();

    $data = $productos->map(function ($p) {
        // ✅ Detecta la ruta correcta de la imagen (uploads/productos)
        $imagen = null;
        if ($p->imagen && file_exists(public_path('uploads/productos/' . $p->imagen))) {
            $imagen = asset('uploads/productos/' . $p->imagen);
        } else {
            $imagen = asset('img/sin-imagen.png');
        }

        return [
            'id'        => $p->id,
            'nombre'    => $p->nombre,
            'precio'    => $p->precio,
            'categoria' => $p->categoria->nombre ?? 'Sin categoría',
            'catalogo'  => $p->catalogo->nombre ?? 'Sin catálogo',
            // Estándar: >=21 Disponible, 1-20 Pocas unidades, 0 Agotado
            'estado'    => ((int) $p->cantidad) >= 21
                            ? 'Disponible'
                            : (((int) $p->cantidad) >= 1 ? 'Pocas unidades' : 'Agotado'),
            'imagen'    => $imagen,
        ];
    });

    return response()->json($data);
}

    /**
     * Buscar track en Spotify y retornar datos
     * Soporta búsqueda por texto o código de barras UPC/EAN
     */
    public function buscarSpotify(Request $request, SpotifyService $spotify)
    {
        $query = $request->input('query');
        $searchType = $request->input('type', 'text'); // 'text' o 'barcode'
        
        if (!$query || strlen($query) < 2) {
            return response()->json(['error' => 'Consulta muy corta'], 400);
        }

        // Búsqueda por código de barras
        if ($searchType === 'barcode' || (is_numeric($query) && strlen($query) >= 12)) {
            $resultado = $spotify->searchByBarcode($query);
        } else {
            // Búsqueda por texto
            $resultado = $spotify->searchTrack($query);
        }
        
        if (!$resultado) {
            return response()->json(['error' => 'No se encontraron resultados'], 404);
        }

        return response()->json($resultado);
    }

}
