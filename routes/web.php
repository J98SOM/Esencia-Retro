<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
})->name('login');

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/productos', function () {
        return view('admin.productos');
    })->name('productos');

    Route::get('/inventario', function () {
        return view('admin.inventario');
    })->name('inventario');

    Route::get('/mesas', function () {
        return view('admin.mesas');
    })->name('mesas');

    Route::get('/mesas/{id}/pedido', function ($id) {
        return view('admin.pedido', ['mesaId' => $id]);
    })->name('pedido');

    Route::get('/mesas/{id}/checkout', function ($id) {
        return view('admin.checkout', ['mesaId' => $id]);
    })->name('checkout');

    Route::get('/users', function () {
        return view('admin.users');
    })->name('users');

    Route::get('/roles', function () {
        return view('admin.roles');
    })->name('roles');

    Route::get('/reportes', function () {
        return view('admin.reportes');
    })->name('reportes');

    Route::get('/alquiler', function () {
        return view('admin.alquiler');
    })->name('alquiler');

    // Lista paginada de alquileres (vista creada como admin.alquiler_list)
    Route::get('/alquiler/list', function () {
        return view('admin.alquiler_list');
    })->name('alquiler.list');

    Route::get('/cocina', function () {
        return view('admin.cocina');
    })->name('cocina');
});

// Load API routes under /api prefix using the API middleware (stateless)
Route::prefix('api')->middleware('api')->group(function () {
    require __DIR__ . '/api.php';
});
