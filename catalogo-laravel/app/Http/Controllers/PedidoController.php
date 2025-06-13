<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedidos;
use App\Models\Carritos;
use App\Models\Productos;
use App\Models\Direcciones;

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
        foreach ($validated['productos'] as $prod) {
            $producto = \App\Models\Productos::find($prod['producto_id']);
            if ($producto) {
                $producto->stock -= $prod['cantidad'];
                $producto->save();
            }
        }

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
            ->with('productos', 'direccion')
            ->get();
        return response()->json($pedidos, 200);
    }

    public function indexAdmin(Request $request)
    {
        // Listar todos los pedidos sin filtrar por usuario
        $pedidos = Pedidos::with('productos', 'direccion')
        ->with('user') // Incluye el usuario que realizó el pedido
        ->get();
        return response()->json($pedidos, 200);
    }

    // Mostrar un pedido específico
    public function show(Request $request, $id)
    {
        $pedido = Pedidos::where('user_id', $request->user()->id)
            ->with('productos', 'direccion')
            ->findOrFail($id);
        return response()->json($pedido, 200);
    }

    // Actualizar un pedido
    public function updateC(Request $request, $id)
    {
        // Buscar el pedido que pertenezca al usuario autenticado
        $pedido = Pedidos::where('user_id', $request->user()->id)->findOrFail($id);

        // Validar que se haya enviado una dirección válida
        $validated = $request->validate([
            'direccion_id' => 'required|exists:direcciones,id',
        ]);

        // Verificar que la dirección también le pertenece al usuario
        $direccion = Direcciones::where('id', $validated['direccion_id'])
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$direccion) {
            return response()->json(['error' => 'La dirección no pertenece al usuario'], 403);
        }

        // Actualizar el pedido con la nueva dirección
        $pedido->update(['direccion_id' => $validated['direccion_id']]);

        return response()->json($pedido, 200);
}


    public function updateA(Request $request, $id)
    {
        // Validar el nuevo estado del pedido
        $validated = $request->validate([
            'estado' => 'required|string|in:pendiente,enviado,entregado',
        ]);

        // Buscar el pedido por ID
        $pedido = Pedidos::findOrFail($id);

        // Actualizar solo el campo 'estado'
        $pedido->update(['estado' => $validated['estado']]);

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