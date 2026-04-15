<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ReporteController;
use Illuminate\Support\Facades\Route;

// --- RUTA PÚBLICA ---
Route::get('/', function () {
    return view('welcome');
});

// --- RUTAS PROTEGIDAS (Solo para usuarios logueados) ---
Route::middleware(['auth', 'verified'])->group(function () {
    
    // 1. PANEL PRINCIPAL Y PERFIL (Accesible por todos)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    // 2. OPERACIÓN DIARIA: INVENTARIO Y POS (Accesible por todos)
    Route::controller(ProductoController::class)->group(function () {
        Route::get('/productos', 'index')->name('productos.index');
        Route::get('/productos/crear', 'create')->name('productos.create');
        Route::post('/productos', 'store')->name('productos.store');
        Route::get('/productos/{producto}/editar', 'edit')->name('productos.edit');
        Route::put('/productos/{producto}', 'update')->name('productos.update');
        Route::delete('/productos/{producto}', 'destroy')->name('productos.destroy');
        Route::get('/pos/ticket/{venta}', [PosController::class, 'generarTicket'])->name('pos.ticket');
    });

    Route::controller(PosController::class)->group(function () {
        Route::get('/pos', 'index')->name('pos.index');
        Route::post('/pos/agregar', 'agregar')->name('pos.agregar');
        Route::post('/pos/quitar/{id}', 'quitar')->name('pos.quitar');
        Route::post('/pos/cobrar', 'cobrar')->name('pos.cobrar');
    });


    // 3. SECCIÓN DE ALTA SEGURIDAD (Solo Administradores)
    // Este filtro "can:admin" o validación de rol bloquea a los vendedores de raíz
    // 3. SECCIÓN DE ALTA SEGURIDAD (Solo Administradores)
    // Usamos el alias 'admin' que acabamos de registrar
    Route::middleware(['admin'])->group(function () {
        
        // Gestión de Personal
        Route::resource('usuarios', UsuarioController::class);

        // Registro de Usuarios (Privatizado para el Admin)
        Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
        Route::post('register', [RegisteredUserController::class, 'store']);

        // Historial y Reportes
        Route::get('/historial', [HistorialController::class, 'index'])->name('historial.index');
        Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    });
});

require __DIR__.'/auth.php';