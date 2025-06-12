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
            'carrito_id' => 'required|exists:carritos,id',
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1',
        ]);

        $carrito = Carritos::find($validated['carrito_id']);

        // Si ya existe el producto en el carrito, suma la cantidad
        $productoExistente = $carrito->productos()->where('producto_id', $validated['producto_id'])->first();
        if ($productoExistente) {
            $nuevaCantidad = $productoExistente->pivot->cantidad + $validated['cantidad'];
            $carrito->productos()->updateExistingPivot($validated['producto_id'], [
                'cantidad' => $nuevaCantidad
            ]);
        } else {
            $carrito->productos()->syncWithoutDetaching([
                $validated['producto_id'] => ['cantidad' => $validated['cantidad']]
        ]);
        }

        $carrito->load('productos');
        return response()->json($carrito, 201);
    }

    // Actualizar la cantidad de un producto en el carrito
    public function update(Request $request, $producto_id)
    {
    
        $validated = $request->validate([
            'cantidad' => 'required|integer|min:1',
            'carrito_id' => 'required|exists:carritos,id',
            'opcion' => 'required|string|in:sumar,restar',  // nuevo parámetro para elegir acción
        ]);

        if (!empty($validated['carrito_id'])) {
            $carrito = Carritos::find($validated['carrito_id']);
            if (!$carrito) {
                return response()->json(['error' => 'Carrito no encontrado'], 404);
            }
        }else {
            // Si no se proporciona carrito_id, se usa el carrito activo del usuario
            $carrito = Carritos::where('user_id', $request->user()->id)
                ->where('estado', 'activo')
                ->first();

            if (!$carrito) {
                return response()->json(['error' => 'Carrito no se pudo cargar'], 404);
            }
        }

        $productoExistente = $carrito->productos()->where('producto_id', $producto_id)->first();
        
        if (!$productoExistente) {
            return response()->json(['error' => 'Producto no encontrado en el carrito'], 404);
        }

        $cantidadActual = $productoExistente->pivot->cantidad;
        $nuevaCantidad = $cantidadActual;

        if ($validated['opcion'] === 'sumar') {
            $nuevaCantidad += $validated['cantidad'];
        } elseif ($validated['opcion'] === 'restar') {
            $nuevaCantidad -= $validated['cantidad'];
        }

        if ($nuevaCantidad <= 0) {
            // Eliminar producto si la cantidad queda 0 o menos
            $carrito->productos()->detach($producto_id);
            return response()->json(['mensaje' => 'Producto eliminado del carrito porque la cantidad llegó a cero'], 200);
        } else {
        // Actualizar con la nueva cantidad
            $carrito->productos()->updateExistingPivot($producto_id, [
                'cantidad' => $nuevaCantidad
            ]);
            $carrito->load('productos');
            return response()->json($carrito, 200);
        }
    }

    // Eliminar un producto del carrito
    public function destroy(Request $request, $producto_id)
    {
        $carrito_id = $request->query('carrito_id');

        if (!$carrito_id) {
            return response()->json(['error' => 'Falta el carrito_id'], 400);
        }

        $carrito = Carritos::find($carrito_id);

        if (!$carrito) {
            return response()->json(['error' => 'Carrito no encontrado'], 404);
        }

        // Verifica si el producto existe en el carrito
        $productoExistente = $carrito->productos()->where('producto_id', $producto_id)->first();
        if (!$productoExistente) {
            return response()->json(['error' => 'Producto no encontrado en el carrito'], 404);
        }

        // Elimina el producto del carrito
        $carrito->productos()->detach($producto_id);

        // Recarga los productos para retornar el estado actualizado
        $carrito->load('productos');

        return response()->json($carrito, 200);
    }
}