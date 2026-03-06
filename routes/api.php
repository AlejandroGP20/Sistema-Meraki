<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FuncionController;
use App\Http\Controllers\Api\FuncionImagenController;
use App\Http\Controllers\Api\MesaController;
use App\Http\Controllers\Api\ReservaController;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Funciones públicas (para ver calendario)
Route::get('/funciones', [FuncionController::class, 'index']);
Route::get('/funciones/{funcion}', [FuncionController::class, 'show']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Mesas
    Route::get('/mesas', [MesaController::class, 'index']);
    Route::get('/mesas/disponibilidad', [MesaController::class, 'disponibilidad']);

    // Reservas
    Route::get('/reservas', [ReservaController::class, 'index']);
    Route::post('/reservas', [ReservaController::class, 'store']);
    Route::get('/reservas/{reserva}', [ReservaController::class, 'show']);
    Route::post('/reservas/{reserva}/cancel', [ReservaController::class, 'cancel']);
    
    // Acciones de admin/encargado
    Route::middleware('check.role:admin,encargado')->group(function () {
        Route::post('/reservas/{reserva}/check-in', [ReservaController::class, 'checkIn']);
        Route::post('/reservas/{reserva}/confirm', [ReservaController::class, 'confirm']);
        Route::post('/reservas/{reserva}/no-show', [ReservaController::class, 'noShow']);
        Route::get('/reservas-stats', [ReservaController::class, 'stats']);
        Route::get('/reservas-export', [ReservaController::class, 'export']);
    });

    // Funciones (CRUD para admin/encargado)
    Route::middleware('check.role:admin,encargado')->group(function () {
        Route::post('/funciones', [FuncionController::class, 'store']);
        Route::put('/funciones/{funcion}', [FuncionController::class, 'update']);
        Route::delete('/funciones/{funcion}', [FuncionController::class, 'destroy']);
        
        // Gestión de imágenes
        Route::post('/funciones/{funcion}/imagenes', [FuncionImagenController::class, 'store']);
        Route::post('/funciones/{funcion}/imagenes/reorder', [FuncionImagenController::class, 'reorder']);
        Route::post('/funciones/{funcion}/imagenes/{imagen}/principal', [FuncionImagenController::class, 'setPrincipal']);
        Route::put('/imagenes/{imagen}/alt-text', [FuncionImagenController::class, 'updateAltText']);
        Route::delete('/imagenes/{imagen}', [FuncionImagenController::class, 'destroy']);
    });
});
