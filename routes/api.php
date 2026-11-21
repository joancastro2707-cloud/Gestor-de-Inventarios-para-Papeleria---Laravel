<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Login con Rate Limiting (máx. 5 intentos por minuto)
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:login')
    ->name('api.login');

// Rutas protegidas con Sanctum + Rate Limiting de API
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

    Route::get('/user', function (Request $request) {
        return response()->json($request->user(), 200);
    })->name('api.user');

    // CRUD RESTful con los 5 verbos HTTP (GET, POST, PUT, PATCH, DELETE)
    Route::get('/productos', [ProductoController::class, 'index'])->name('api.productos.index');
    Route::post('/productos', [ProductoController::class, 'store'])->name('api.productos.store');
    Route::get('/productos/{producto}', [ProductoController::class, 'show'])->name('api.productos.show');
    Route::put('/productos/{producto}', [ProductoController::class, 'update'])->name('api.productos.update');
    Route::patch('/productos/{producto}', [ProductoController::class, 'update'])->name('api.productos.patch');
    Route::delete('/productos/{producto}', [ProductoController::class, 'destroy'])->name('api.productos.destroy');
});