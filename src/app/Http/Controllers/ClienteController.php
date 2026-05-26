<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Helpers\BitacoraHelper;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $buscar = trim($request->buscar ?? '');

        $clientes = Cliente::withCount('ventas')
            ->when($buscar, function ($query, $buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'like', "%{$buscar}%")
                      ->orWhere('identificacion', 'like', "%{$buscar}%")
                      ->orWhere('email', 'like', "%{$buscar}%")
                      ->orWhere('telefono', 'like', "%{$buscar}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('clientes.index', compact('clientes', 'buscar'));
    }

    public function show($id)
    {
        $cliente = Cliente::with([
            'ventas' => function ($query) {
                $query->latest();
            }
        ])->findOrFail($id);

        $cantidadCompras = $cliente->ventas->count();
        $totalGastado = $cliente->ventas->sum('total');
        $ultimaCompra = $cliente->ventas->first();

        return view('clientes.show', compact(
            'cliente',
            'cantidadCompras',
            'totalGastado',
            'ultimaCompra'
        ));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'nombre' => trim($request->nombre),
            'identificacion' => trim($request->identificacion),
            'telefono' => trim($request->telefono),
            'email' => strtolower(trim($request->email)),
            'direccion' => trim($request->direccion),
        ]);

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'min:3', 'max:100'],
            'identificacion' => ['required', 'string', 'min:6', 'max:30', 'unique:clientes,identificacion'],
            'telefono' => ['required', 'regex:/^[0-9]{8,15}$/'],
            'email' => ['required', 'email', 'max:100', 'unique:clientes,email'],
            'direccion' => ['required', 'string', 'min:5', 'max:255'],
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.',

            'identificacion.required' => 'La identificación es obligatoria.',
            'identificacion.min' => 'La identificación debe tener al menos 6 caracteres.',
            'identificacion.max' => 'La identificación no puede tener más de 30 caracteres.',
            'identificacion.unique' => 'Esta identificación ya está registrada.',

            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.regex' => 'El teléfono debe contener solo números y tener entre 8 y 15 dígitos.',

            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ingresar un correo electrónico válido.',
            'email.max' => 'El correo electrónico no puede tener más de 100 caracteres.',
            'email.unique' => 'Este correo electrónico ya está registrado.',

            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.min' => 'La dirección debe tener al menos 5 caracteres.',
            'direccion.max' => 'La dirección no puede tener más de 255 caracteres.',
        ]);

        $cliente = Cliente::create($validated);

        BitacoraHelper::registrar(
            'Clientes',
            'Crear',
            'Se creó el cliente: ' . $cliente->nombre . ' | Identificación: ' . $cliente->identificacion
        );

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente creado correctamente.');
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);

        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $request->merge([
            'nombre' => trim($request->nombre),
            'identificacion' => trim($request->identificacion),
            'telefono' => trim($request->telefono),
            'email' => strtolower(trim($request->email)),
            'direccion' => trim($request->direccion),
        ]);

        $validated = $request->validate([
            'nombre' => ['required', 'string', 'min:3', 'max:100'],
            'identificacion' => [
                'required',
                'string',
                'min:6',
                'max:30',
                Rule::unique('clientes', 'identificacion')->ignore($cliente->id),
            ],
            'telefono' => ['required', 'regex:/^[0-9]{8,15}$/'],
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('clientes', 'email')->ignore($cliente->id),
            ],
            'direccion' => ['required', 'string', 'min:5', 'max:255'],
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.',

            'identificacion.required' => 'La identificación es obligatoria.',
            'identificacion.min' => 'La identificación debe tener al menos 6 caracteres.',
            'identificacion.max' => 'La identificación no puede tener más de 30 caracteres.',
            'identificacion.unique' => 'Esta identificación ya está registrada.',

            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.regex' => 'El teléfono debe contener solo números y tener entre 8 y 15 dígitos.',

            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ingresar un correo electrónico válido.',
            'email.max' => 'El correo electrónico no puede tener más de 100 caracteres.',
            'email.unique' => 'Este correo electrónico ya está registrado.',

            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.min' => 'La dirección debe tener al menos 5 caracteres.',
            'direccion.max' => 'La dirección no puede tener más de 255 caracteres.',
        ]);

        $cliente->update($validated);

        BitacoraHelper::registrar(
            'Clientes',
            'Editar',
            'Se actualizó el cliente: ' . $cliente->nombre . ' | Identificación: ' . $cliente->identificacion
        );

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $nombreCliente = $cliente->nombre;

        BitacoraHelper::registrar(
            'Clientes',
            'Eliminar',
            'Se eliminó el cliente: ' . $nombreCliente . ' | Identificación: ' . $cliente->identificacion
        );

        $cliente->delete();

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}