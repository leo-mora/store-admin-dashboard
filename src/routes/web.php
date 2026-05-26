<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\DevolucionController;
use App\Http\Controllers\BitacoraController;
/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| SISTEMA PROTEGIDO
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categorias', CategoriaController::class);
    Route::resource('productos', ProductoController::class);
    Route::resource('clientes', ClienteController::class);
    Route::resource('ventas', VentaController::class);
        Route::get('/ventas/{venta}/devoluciones/create', [DevolucionController::class, 'create'])
        ->name('devoluciones.create');

    Route::post('/ventas/{venta}/devoluciones', [DevolucionController::class, 'store'])
        ->name('devoluciones.store');

    Route::get('/devoluciones/{id}', [DevolucionController::class, 'show'])
        ->name('devoluciones.show');

    /*
    |--------------------------------------------------------------------------
    | FACTURA
    |--------------------------------------------------------------------------
    */

    Route::get('/ventas/{id}/factura', [VentaController::class, 'factura'])
        ->name('ventas.factura');

    Route::post('/ventas/{id}/enviar-factura', [VentaController::class, 'enviarFactura'])
        ->name('ventas.enviarFactura');

    /*
    |--------------------------------------------------------------------------
    | REPORTES
    |--------------------------------------------------------------------------
    */

    Route::get('/reportes', [ReporteController::class, 'index'])
        ->name('reportes.index');

    Route::get('/reportes/excel', [ReporteController::class, 'exportarExcel'])
        ->name('reportes.excel');
});

/*
|--------------------------------------------------------------------------
| USUARIOS (SOLO ADMIN)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{id}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
});

Route::get('/bitacoras', [BitacoraController::class, 'index'])->name('bitacoras.index');