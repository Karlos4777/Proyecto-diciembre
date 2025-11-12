<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Catalogo;
use App\Http\Requests\CatalogoRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CatalogoController extends Controller
{
    use AuthorizesRequests;

    /**
     * Muestra una lista de catálogos.
     */
    public function index(Request $request)
    {
        $this->authorize('catalogo-list');

        $texto = $request->input('texto');
        $registros = Catalogo::where('nombre', 'like', "%{$texto}%")
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('catalogo.index', compact('registros', 'texto'));
    }

    /**
     * Muestra el formulario para crear un nuevo catálogo.
     */
    public function create()
    {
        $this->authorize('catalogo-create');
        return view('catalogo.action');
    }

    /**
     * Guarda un nuevo catálogo en la base de datos.
     */
    public function store(CatalogoRequest $request)
    {
        $this->authorize('catalogo-create');

        $registro = new Catalogo();
        $registro->nombre = $request->input('nombre');
        $registro->descripcion = $request->input('descripcion');
        $registro->save();

        return redirect()->route('catalogo.index')
            ->with('mensaje', 'Registro "' . $registro->nombre . '" agregado correctamente.');
    }

    /**
     * Muestra el formulario para editar un catálogo existente.
     */
    public function edit($id)
    {
        $this->authorize('catalogo-edit');

        $registro = Catalogo::findOrFail($id);
        return view('catalogo.action', compact('registro'));
    }

    /**
     * Actualiza un catálogo existente.
     */
    public function update(CatalogoRequest $request, $id)
    {
        $this->authorize('catalogo-edit');

        $registro = Catalogo::findOrFail($id);
        $registro->nombre = $request->input('nombre');
        $registro->descripcion = $request->input('descripcion');
        $registro->save();

        return redirect()->route('catalogo.index')
            ->with('mensaje', 'Registro "' . $registro->nombre . '" actualizado correctamente.');
    }

    /**
     * Elimina un catálogo.
     */
    public function destroy($id)
    {
        $this->authorize('catalogo-delete');

        $registro = Catalogo::findOrFail($id);
        $registro->delete();

        return redirect()->route('catalogo.index')
            ->with('mensaje', 'Registro "' . $registro->nombre . '" eliminado correctamente.');
    }

    /**
     * Muestra los productos asociados a un catálogo específico.
     */
    public function show($id)
    {
        $catalogo = Catalogo::findOrFail($id);

        // Si el modelo tiene relación definida: public function productos() { return $this->hasMany(Producto::class); }
        $productos = $catalogo->productos()->paginate(9);

        return view('web.catalogo', compact('catalogo', 'productos'));
    }
}
