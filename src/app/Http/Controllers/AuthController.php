<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\BitacoraHelper;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->merge([
            'email' => strtolower(trim($request->email)),
        ]);

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'El correo es obligatorio.',
            'email.email' => 'Debe ingresar un correo válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            BitacoraHelper::registrar(
                'Autenticación',
                'Login',
                'Inicio de sesión del usuario: ' . auth()->user()->name . ' (' . auth()->user()->email . ')'
            );

            return redirect()->intended('/');
        }

        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Credenciales incorrectas.');
    }

    public function logout(Request $request)
    {
        $usuario = auth()->user();

        if ($usuario) {
            BitacoraHelper::registrar(
                'Autenticación',
                'Logout',
                'Cierre de sesión del usuario: ' . $usuario->name . ' (' . $usuario->email . ')'
            );
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')
            ->with('success', 'Sesión cerrada correctamente.');
    }
}