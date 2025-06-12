<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Direcciones;

class DireccionController extends Controller
{
    public function index(Request $request)
    {
        // Devuelve solo las direcciones del usuario autenticado
        $direcciones = Direcciones::where('user_id', $request->user()->id)->get();
        return response()->json($direcciones, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'direccion' => 'required|string|max:255',
            'ciudad' => 'required|string|max:100',
            'provincia' => 'required|string|max:100',
            'telefono' => 'required|string|max:20',
            // Agrega más campos según tu modelo
        ]);

        $direccion = Direcciones::create([
            'user_id' => $request->user()->id,
            ...$validated
        ]);

        return response()->json($direccion, 201);
    }

    public function update(Request $request, $id)
    {
        $direccion = Direcciones::where('user_id', $request->user()->id)->findOrFail($id);

        $validated = $request->validate([
            'direccion' => 'sometimes|required|string|max:255',
            'ciudad' => 'sometimes|required|string|max:100',
            'provincia' => 'sometimes|required|string|max:100',
            'telefono' => 'sometimes|required|string|max:20',
            // Agrega más campos según tu modelo
        ]);

        $direccion->update($validated);

        return response()->json($direccion, 200);
    }

    public function destroy(Request $request, $id)
    {
        $direccion = Direcciones::where('user_id', $request->user()->id)->findOrFail($id);
        $direccion->delete();

        return response()->json(['message' => 'Dirección eliminada correctamente'], 200);
    }
}