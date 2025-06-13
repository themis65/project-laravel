<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DireccionController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\CarritoController;

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

    Route::get('/direcciones', [DireccionController::class, 'index'])->middleware('IsAdmin:cliente');
    Route::post('/direcciones', [DireccionController::class, 'store'])->middleware('IsAdmin:cliente');
    Route::put('/direcciones/{id}', [DireccionController::class, 'update'])->middleware('IsAdmin:cliente');
    Route::delete('/direcciones/{id}', [DireccionController::class, 'destroy'])->middleware('IsAdmin:cliente');

    Route::post('/carrito', [CarritoController::class, 'store'])->middleware('IsAdmin:cliente');
    Route::get('/carrito', [CarritoController::class, 'index']);
    Route::put('/carrito/{id}', [CarritoController::class, 'update'])->middleware('IsAdmin:cliente');
    Route::delete('/carrito/{id}', [CarritoController::class, 'destroy']);

    Route::post('/pedidos', [PedidoController::class, 'store'])->middleware('IsAdmin:cliente');
    Route::get('/pedidos', [PedidoController::class, 'index']);
    Route::get('/pedidos/{id}', [PedidoController::class, 'show']);
    Route::put('/pedidos/direccion/{id}', [PedidoController::class, 'updateC'])->middleware('IsAdmin:cliente');

    Route::get('/pedidos/admin', [PedidoController::class, 'indexAdmin'])->middleware('IsAdmin:admin');
    Route::put('/pedidos/estado/{id}', [PedidoController::class, 'updateA'])->middleware('IsAdmin:admin');
    Route::delete('/pedidos/{id}', [PedidoController::class, 'destroy'])->middleware('IsAdmin:admin');

    Route::get('/usuarios', [AuthController::class, 'usuarios'])->middleware('IsAdmin:admin');
    Route::put('/usuarios/{id}', [AuthController::class, 'updateUsuario'])->middleware('IsAdmin:admin');
});

