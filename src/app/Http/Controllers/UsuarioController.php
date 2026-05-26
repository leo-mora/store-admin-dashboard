<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Helpers\BitacoraHelper;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::orderBy('id', 'desc')->get();

        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $request->merge([
            'name' => trim($request->name),
            'email' => strtolower(trim($request->email)),
        ]);

        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'max:50'],
            'rol' => ['required', Rule::in(['admin', 'empleado'])],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.max' => 'El nombre no puede tener más de 100 caracteres.',

            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Debe ingresar un correo válido.',
            'email.max' => 'El correo no puede tener más de 100 caracteres.',
            'email.unique' => 'Este correo ya está registrado.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.max' => 'La contraseña no puede tener más de 50 caracteres.',

            'rol.required' => 'Debe seleccionar un rol.',
            'rol.in' => 'El rol seleccionado no es válido.',
        ]);

        $usuario = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol
        ]);

        BitacoraHelper::registrar(
            'Usuarios',
            'Crear',
            'Se creó el usuario: ' . $usuario->name . ' (' . $usuario->email . ') con rol ' . $usuario->rol
        );

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function edit($id)
    {
        $usuario = User::findOrFail($id);

        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);
        $nombreAnterior = $usuario->name;
        $emailAnterior = $usuario->email;
        $rolAnterior = $usuario->rol;

        $request->merge([
            'name' => trim($request->name),
            'email' => strtolower(trim($request->email)),
        ]);

        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'email' => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($usuario->id)],
            'password' => ['nullable', 'string', 'min:6', 'max:50'],
            'rol' => ['required', Rule::in(['admin', 'empleado'])],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.max' => 'El nombre no puede tener más de 100 caracteres.',

            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Debe ingresar un correo válido.',
            'email.max' => 'El correo no puede tener más de 100 caracteres.',
            'email.unique' => 'Este correo ya está registrado.',

            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.max' => 'La contraseña no puede tener más de 50 caracteres.',

            'rol.required' => 'Debe seleccionar un rol.',
            'rol.in' => 'El rol seleccionado no es válido.',
        ]);

        if ($usuario->rol === 'admin' && $request->rol !== 'admin') {
            $totalAdmins = User::where('rol', 'admin')->count();

            if ($totalAdmins <= 1) {
                return redirect()
                    ->route('usuarios.index')
                    ->with('error', 'No se puede cambiar el rol del último administrador.');
            }
        }

        $datos = [
            'name' => $request->name,
            'email' => $request->email,
            'rol' => $request->rol,
        ];

        if ($request->filled('password')) {
            $datos['password'] = Hash::make($request->password);
        }

        $usuario->update($datos);

        BitacoraHelper::registrar(
            'Usuarios',
            'Editar',
            'Se actualizó el usuario: ' . $nombreAnterior . ' (' . $emailAnterior . ', rol: ' . $rolAnterior . ') a ' . $usuario->name . ' (' . $usuario->email . ', rol: ' . $usuario->rol . ')'
        );

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        $usuario = User::findOrFail($id);

        if (auth()->id() == $usuario->id) {
            return redirect()
                ->route('usuarios.index')
                ->with('error', 'No puede eliminar su propio usuario.');
        }

        if ($usuario->rol === 'admin') {
            $totalAdmins = User::where('rol', 'admin')->count();

            if ($totalAdmins <= 1) {
                return redirect()
                    ->route('usuarios.index')
                    ->with('error', 'No se puede eliminar el último administrador del sistema.');
            }
        }

        $nombreUsuario = $usuario->name;
        $emailUsuario = $usuario->email;
        $rolUsuario = $usuario->rol;

        BitacoraHelper::registrar(
            'Usuarios',
            'Eliminar',
            'Se eliminó el usuario: ' . $nombreUsuario . ' (' . $emailUsuario . ') con rol ' . $rolUsuario
        );

        $usuario->delete();

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}