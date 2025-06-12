<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DireccionController;

//rutas publicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//productos
Route::get('/productos', [ProductoController::class, 'index']);
Route::get('/productos/{id}', [ProductoController::class, 'show']);
Route::get('/categorias', [ProductoController::class, 'categorias']);

//rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/categorias', [ProductoController::class, 'storeCategoria'])->middleware('IsAdmin:admin');
    Route::delete('/categorias/{id}', [ProductoController::class, 'destroyCategoria'])->middleware('IsAdmin:admin');
    
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/productos', [ProductoController::class, 'store'])->middleware('IsAdmin:admin');
    Route::put('/productos/{id}', [ProductoController::class, 'update'])->middleware('IsAdmin:admin');
    Route::delete('/productos/{id}', [ProductoController::class, 'destroy'])->middleware('IsAdmin:admin');

    Route::get('/direcciones', [DireccionController::class, 'direcciones'])->middleware('IsAdmin:cliente');
    Route::post('/direcciones', [DireccionController::class, 'storeDireccion'])->middleware('IsAdmin:cliente');
    Route::put('/direcciones/{id}', [DireccionController::class, 'updateDireccion'])->middleware('IsAdmin:cliente');
    Route::delete('/direcciones/{id}', [DireccionController::class, 'destroyDireccion'])->middleware('IsAdmin:cliente');

    Route::post('/carrito', [AuthController::class, 'storeCarrito']);
    Route::get('/carrito', [AuthController::class, 'carrito']);
    Route::delete('/carrito/{id}', [AuthController::class, 'destroyCarrito']);

    Route::post('/pedidos', [AuthController::class, 'storepedido']);
    Route::get('/pedidos', [AuthController::class, 'pedidos']);
    Route::get('/pedidos/{id}', [AuthController::class, 'showPedido']);
    Route::put('/pedidos/{id}', [AuthController::class, 'updatePedido']);
    Route::delete('/pedidos/{id}', [AuthController::class, 'destroyPedido']);

    Route::get('/usuarios', [AuthController::class, 'usuarios'])->middleware('IsAdmin:admin');
    Route::put('/usuarios/{id}', [AuthController::class, 'updateUsuario'])->middleware('IsAdmin:admin');
});

