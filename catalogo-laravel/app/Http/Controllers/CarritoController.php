<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carritos;
use App\Models\Productos;

class CarritoController extends Controller
{
    // Mostrar el carrito del usuario autenticado
    public function index(Request $request)
    {
        $carrito = Carritos::where('user_id', $request->user()->id)
            ->where('estado', 'activo')
            ->with('productos')
            ->first();

    if (!$carrito) {
        $carrito = Carritos::create([
            'user_id' => $request->user()->id,
            'estado' => 'activo',
        ]);
        // Asegúrate de cargar la relación productos (vacía)
        $carrito->load('productos');
    }

    return response()->json($carrito, 200);
    }

    // Agregar o actualizar un producto en el carrito
    public function store(Request $request)
    {
        $validated = $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        $carrito = Carritos::firstOrCreate(['user_id' => $request->user()->id]);

        // Si ya existe el producto en el carrito, actualiza la cantidad
        $carrito->productos()->syncWithoutDetaching([
            $validated['producto_id'] => ['cantidad' => $validated['cantidad']]
        ]);

        $carrito->load('productos');
        return response()->json($carrito, 201);
    }

    // Actualizar la cantidad de un producto en el carrito
    public function update(Request $request, $producto_id)
    {
        $validated = $request->validate([
            'cantidad' => 'required|integer|min:1',

        ]);

        $carrito = Carritos::firstOrCreate(['user_id' => $request->user()->id]);
        $carrito->productos()->updateExistingPivot($producto_id, ['cantidad' => $validated['cantidad']]);

        $carrito->load('productos');
        return response()->json($carrito, 200);
    }

    // Eliminar un producto del carrito
    public function destroy(Request $request, $producto_id)
    {
        $carrito = Carritos::firstOrCreate(['user_id' => $request->user()->id]);
        $carrito->productos()->detach($producto_id);

        $carrito->load('productos');
        return response()->json($carrito, 200);
    }
}