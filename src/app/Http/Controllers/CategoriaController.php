<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Producto;
use App\Helpers\BitacoraHelper;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->buscar;

        $categorias = Categoria::withCount('productos')
            ->when($buscar, function ($query, $buscar) {
                return $query->where('nombre', 'like', '%' . $buscar . '%');
            })
            ->get();

        return view('categorias.index', compact('categorias', 'buscar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:100|unique:categorias,nombre'
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.unique' => 'Esta categoría ya existe.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.'
        ]);

        $categoria = Categoria::create([
            'nombre' => $request->nombre
        ]);

        BitacoraHelper::registrar(
            'Categorías',
            'Crear',
            'Se creó la categoría: ' . $categoria->nombre
        );

        return redirect('/categorias')
            ->with('success', 'Categoría creada correctamente');
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
        $categoria = Categoria::findOrFail($id);

        return view('categorias.edit', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|max:100|unique:categorias,nombre,' . $id
        ], [
            'nombre.required' => 'El nombre de la categoría es obligatorio.',
            'nombre.unique' => 'Esta categoría ya existe.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.'
        ]);

        $categoria = Categoria::findOrFail($id);

        $categoria->update([
            'nombre' => $request->nombre
        ]);

        BitacoraHelper::registrar(
            'Categorías',
            'Editar',
            'Se actualizó la categoría: ' . $categoria->nombre
        );

        return redirect('/categorias')
            ->with('success', 'Categoría actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $categoria = Categoria::findOrFail($id);

        $tieneProductos = Producto::where('categoria_id', $id)->exists();

        if ($tieneProductos) {
            return redirect('/categorias')
                ->with('error', 'No se puede eliminar la categoría porque tiene productos asociados.');
        }

        $nombreCategoria = $categoria->nombre;

        BitacoraHelper::registrar(
            'Categorías',
            'Eliminar',
            'Se eliminó la categoría: ' . $nombreCategoria
        );

        $categoria->delete();

        return redirect('/categorias')
            ->with('success', 'Categoría eliminada correctamente.');
    }
}