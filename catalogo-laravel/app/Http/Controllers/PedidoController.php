<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedidos;
use App\Models\Carritos;

class PedidoController extends Controller
{
    // Crear un nuevo pedido
    public function store(Request $request)
    {
        $validated = $request->validate([
        'direccion_id' => 'required|exists:direcciones,id',
        'total' => 'required|numeric|min:0',
        'productos' => 'required|array|min:1',
        'productos.*.producto_id' => 'required|exists:productos,id',
        'productos.*.cantidad' => 'required|integer|min:1',
        'productos.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        $pedido = Pedidos::create([
            'user_id' => $request->user()->id,
            'direccion_id' => $validated['direccion_id'],
            'total' => $validated['total'],
            'estado' => 'pendiente', // Estado inicial del pedido
        ]);

        // Asociar productos al pedido
        $productosSync = [];
        foreach ($validated['productos'] as $prod) {
            $subtotal = $prod['cantidad'] * $prod['precio_unitario'];
            $productosSync[$prod['producto_id']] = [
                'cantidad' => $prod['cantidad'],
                'precio_unitario' => $prod['precio_unitario'],
                'subtotal' => $subtotal,
            ];
        }
        $pedido->productos()->sync($productosSync);

        $carrito = Carritos::where('user_id', $request->user()->id)
            ->where('estado', 'activo')
            ->first();
        if ($carrito) {
            $carrito->estado = 'completado';
            $carrito->save();
        }

        return response()->json($pedido->load('productos'), 201);
    }

    // Listar todos los pedidos del usuario autenticado
    public function index(Request $request)
    {
        $pedidos = Pedidos::where('user_id', $request->user()->id)
            ->with('productos')
            ->get();
        return response()->json($pedidos, 200);
    }

    // Mostrar un pedido específico
    public function show(Request $request, $id)
    {
        $pedido = Pedidos::where('user_id', $request->user()->id)
            ->with('productos')
            ->findOrFail($id);
        return response()->json($pedido, 200);
    }

    // Actualizar un pedido
    public function update(Request $request, $id)
    {
        $pedido = Pedidos::where('user_id', $request->user()->id)->findOrFail($id);

        $validated = $request->validate([
            'direccion_id' => 'sometimes|required|exists:direcciones,id',
            'estado' => 'sometimes|required|string|max:50',
        ]);

        $pedido->update($validated);

        return response()->json($pedido, 200);
    }

    // Eliminar un pedido
    public function destroy(Request $request, $id)
    {
        $pedido = Pedidos::where('user_id', $request->user()->id)->findOrFail($id);
        $pedido->delete();

        return response()->json(['message' => 'Pedido eliminado correctamente'], 200);
    }
}