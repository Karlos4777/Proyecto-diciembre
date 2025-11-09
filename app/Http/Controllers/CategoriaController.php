<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Http\Requests\CategoriaRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CategoriaController extends Controller
{
    use AuthorizesRequests;

    /**
     * Muestra una lista de categorías.
     */
    public function index(Request $request)
    {
        $this->authorize('categoria-list'); 

        $texto = $request->input('texto');
        $registros = Categoria::where('nombre', 'like', "%{$texto}%")
            ->orderBy('id', 'desc')
            ->paginate(3);

        return view('categoria.index', compact('registros', 'texto'));
    }

    /**
     * Muestra el formulario para crear una nueva categoría.
     */
    public function create()
    {
        $this->authorize('categoria-create'); 
        return view('categoria.action');
    }

    /**
     * Guarda una nueva categoría en la base de datos.
     */
    public function store(CategoriaRequest $request)
    {
        $this->authorize('categoria-create'); 

        $registro = new Categoria();
        $registro->nombre = $request->input('name');
        $registro->descripcion = $request->input('description');
        $registro->save();
          
        return redirect()->route('categoria.index')
            ->with('mensaje', 'Registro ' . $registro->nombre . ' agregado correctamente');
    }

    /**
     * Muestra el formulario para editar una categoría existente.
     */
    public function edit($id)
    {
        $this->authorize('categoria-edit');

        $registro = Categoria::findOrFail($id);
        return view('categoria.action', compact('registro'));
    }

    /**
     * Actualiza una categoría existente.
     */
    public function update(CategoriaRequest $request, $id)
    {
        $this->authorize('categoria-edit');

        $registro = Categoria::findOrFail($id);
        $registro->nombre = $request->input('name');
        $registro->descripcion = $request->input('description');
        $registro->save();

        return redirect()->route('categoria.index')
            ->with('mensaje', 'Registro ' . $registro->nombre . ' actualizado correctamente');
    }

    /**
     * Elimina una categoría.
     */
    public function destroy($id)
    {
        $this->authorize('categoria-delete');

        $registro = Categoria::findOrFail($id);
        $registro->delete();

        return redirect()->route('categoria.index')
            ->with('mensaje', 'Registro ' . $registro->nombre . ' eliminado correctamente');
    }

    /**
     * Muestra los productos asociados a una categoría específica.
     */
    public function show($id)
    {
        // 🔹 Busca la categoría por su ID
        $categoria = Categoria::findOrFail($id);

        // 🔹 Trae los productos que pertenecen a esta categoría
        $productos = $categoria->productos()->paginate(9);

        // 🔹 Retorna la vista del sitio web donde se mostrarán los productos
        return view('web.categoria', compact('categoria', 'productos'));
    }
}
