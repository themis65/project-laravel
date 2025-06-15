<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Productos;
use App\Models\Categorias;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Productos::with('categorias')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'imagen' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'categorias' => 'nullable|array',
            'categorias.*' => 'exists:categorias,id',
        ]);

        $producto = DB::transaction(function () use ($validated, $request) {
            $producto = Productos::create($validated);

            if ($request->has('categorias')) {
                $producto->categorias()->sync($validated['categorias']);
            }

            //retornamos el producto con las categorias
            return response()-> json($producto->load('categorias'), 201);
        });
    }

    public function categorias()
    {
        $categorias = Categorias::all();
        return response()->json($categorias);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $producto = Productos::with('categorias')->findOrFail($id);
        return response()->json($producto, 200);
    }


    public function update(Request $request, string $id)
    {
        $producto = Productos::findOrFail($id);

        $validated = $request->validate([
            'titulo' => 'sometimes|required|string|max:255',
            'descripcion' => 'sometimes|required|string',
            'precio' => 'sometimes|required|numeric|min:0',
            'imagen' => 'nullable|string',
            'stock' => 'sometimes|required|integer|min:0',
            'categorias' => 'nullable|array',
            'categorias.*' => 'exists:categorias,id',
        ]);
        
        $producto = DB::transaction(function () use ($validated, $request, $producto) {
            $producto->update($validated);
            if ($request->has('categorias')) {
            $producto->categorias()->sync($validated['categorias']);
            }
        });
        //retornamos el producto con las categorias
        return response()->json($producto, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $producto = Productos::findOrFail($id);
        $producto->categorias()->detach(); // Desvincular las categorías
        $producto->delete(); // Eliminar el producto

        return response()->json(['message' => 'Producto eliminado correctamente'], 200);
    }

    public function storeCategoria(Request $request)
   {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            // El slug puede ser opcional en el request
            'slug' => 'nullable|string|max:255|unique:categorias,slug',
        ]);

        // Si no envían slug, lo generamos a partir del nombre
        $slug = $validated['slug'] ?? Str::slug($validated['nombre']);

        $categoria = \App\Models\Categorias::create([
            'nombre' => $validated['nombre'],
            'slug' => $slug,
        ]);

        return response()->json($categoria, 201);
    }
    
    public function destroyCategoria($id)
    {
        $categoria = \App\Models\Categorias::findOrFail($id);
        $categoria->delete();

        return response()->json(['message' => 'Categoría eliminada correctamente'], 200);
    }

}
