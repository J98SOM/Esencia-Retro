<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Roles API
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\KitchenController;
use App\Http\Controllers\Api\MesaController;
Route::get('/roles', [RoleController::class, 'index']);
Route::get('/roles/{role}', [RoleController::class, 'show']);
Route::post('/roles', [RoleController::class, 'store']);
Route::put('/roles/{role}', [RoleController::class, 'update']);
Route::delete('/roles/{role}', [RoleController::class, 'destroy']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Basic user CRUD using AuthController
    Route::get('/users', [AuthController::class, 'index']);
    Route::post('/users', [AuthController::class, 'store']);
    Route::get('/users/{user}', [AuthController::class, 'show']);
    Route::put('/users/{user}', [AuthController::class, 'update']);
    Route::delete('/users/{user}', [AuthController::class, 'destroy']);

    // Kitchen endpoints (for cocina users)
    Route::get('/kitchen/orders', [KitchenController::class, 'index']);
    Route::post('/kitchen/orders/{id}/status', [KitchenController::class, 'updateStatus']);
    // Mesas CRUD (file-backed fallback) - create/update/delete remain protected
    Route::post('/mesas', [MesaController::class, 'store']);
    Route::delete('/mesas/{id}', [MesaController::class, 'destroy']);
});

// Public mesas index (allow frontend to list mesas without auth)
use App\Http\Controllers\Api\MesaController as PublicMesaController;
Route::get('/mesas', [PublicMesaController::class, 'index'])->name('api.mesas.index');
