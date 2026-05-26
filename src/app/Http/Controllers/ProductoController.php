<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;
use App\Helpers\BitacoraHelper;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $estado = $request->estado;

        $productos = Producto::with('categoria')
            ->when($buscar, function ($query, $buscar) {
                return $query->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'like', "%{$buscar}%")
                      ->orWhere('marca', 'like', "%{$buscar}%")
                      ->orWhere('sku', 'like', "%{$buscar}%");
                });
            })
            ->when($estado == 'bajo', function ($query) {
                return $query->where('stock', '>', 0)
                             ->where('stock', '<=', 5);
            })
            ->when($estado == 'agotado', function ($query) {
                return $query->where('stock', 0);
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('productos.index', compact('productos', 'buscar', 'estado'));
    }

    public function create()
    {
        $categorias = Categoria::all();
        return view('productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:100|unique:productos,nombre',
            'marca' => 'required|max:100',
            'descripcion' => 'nullable|max:1000',
            'precio' => 'required|numeric|min:1',
            'stock' => 'required|integer|min:0|max:10000',
            'categoria_id' => 'required|exists:categorias,id',
        ], [
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'nombre.max' => 'El nombre del producto no puede tener más de 100 caracteres.',
            'nombre.unique' => 'Ya existe un producto con ese nombre.',

            'marca.required' => 'La marca es obligatoria.',
            'marca.max' => 'La marca no puede tener más de 100 caracteres.',

            'descripcion.max' => 'La descripción no puede tener más de 1000 caracteres.',

            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número válido.',
            'precio.min' => 'El precio debe ser mayor a 0.',

            'stock.required' => 'El stock es obligatorio.',
            'stock.integer' => 'El stock debe ser un número entero.',
            'stock.min' => 'El stock no puede ser negativo.',
            'stock.max' => 'El stock no puede ser mayor a 10000.',

            'categoria_id.required' => 'La categoría es obligatoria.',
            'categoria_id.exists' => 'La categoría seleccionada no es válida.',
        ]);

        // Generar SKU automático
        $ultimoProducto = Producto::latest('id')->first();
        $nuevoNumero = $ultimoProducto ? $ultimoProducto->id + 1 : 1;
        $sku = 'PROD-' . str_pad($nuevoNumero, 4, '0', STR_PAD_LEFT);

        $producto = Producto::create([
            'nombre' => $request->nombre,
            'marca' => $request->marca,
            'sku' => $sku,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock,
            'categoria_id' => $request->categoria_id,
        ]);

        BitacoraHelper::registrar(
            'Productos',
            'Crear',
            'Se creó el producto: ' . $producto->nombre . ' | SKU: ' . $producto->sku
        );

        return redirect('/productos')->with('success', 'Producto creado correctamente.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        $categorias = Categoria::all();

        return view('productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        $stockAnterior = $producto->stock;

        $request->validate([
            'nombre' => 'required|max:100|unique:productos,nombre,' . $id,
            'marca' => 'required|max:100',
            'descripcion' => 'nullable|max:1000',
            'precio' => 'required|numeric|min:1',
            'agregar_stock' => 'nullable|integer|min:0|max:10000',
            'categoria_id' => 'required|exists:categorias,id',
        ], [
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'nombre.max' => 'El nombre del producto no puede tener más de 100 caracteres.',
            'nombre.unique' => 'Ya existe un producto con ese nombre.',

            'marca.required' => 'La marca es obligatoria.',
            'marca.max' => 'La marca no puede tener más de 100 caracteres.',

            'descripcion.max' => 'La descripción no puede tener más de 1000 caracteres.',

            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número válido.',
            'precio.min' => 'El precio debe ser mayor a 0.',

            'agregar_stock.integer' => 'La cantidad a agregar al stock debe ser un número entero.',
            'agregar_stock.min' => 'La cantidad a agregar no puede ser negativa.',
            'agregar_stock.max' => 'No puedes agregar tanto stock de una sola vez.',

            'categoria_id.required' => 'La categoría es obligatoria.',
            'categoria_id.exists' => 'La categoría seleccionada no es válida.',
        ]);

        $nuevoStock = $producto->stock + (int) ($request->agregar_stock ?? 0);

        $producto->update([
            'nombre' => $request->nombre,
            'marca' => $request->marca,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $nuevoStock,
            'categoria_id' => $request->categoria_id,
        ]);

        BitacoraHelper::registrar(
            'Productos',
            'Editar',
            'Se actualizó el producto: ' . $producto->nombre .
            ' | SKU: ' . $producto->sku .
            ' | Stock anterior: ' . $stockAnterior .
            ' | Stock actual: ' . $nuevoStock
        );

        return redirect('/productos')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);

        BitacoraHelper::registrar(
            'Productos',
            'Eliminar',
            'Se eliminó el producto: ' . $producto->nombre . ' | SKU: ' . $producto->sku
        );

        $producto->delete();

        return redirect('/productos')->with('success', 'Producto eliminado correctamente.');
    }
}