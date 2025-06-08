<?php
 
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use App\Models\Categorias as Categoria;
use App\Models\Productos as Producto;
 
class CategoriaProductoSeeder extends Seeder
{
    public function run(): void
    {
        // Crear categorías usando firstOrCreate
        $categorias = [
            ['nombre' => 'Electrónica', 'slug' => 'electronica'],
            ['nombre' => 'Ropa', 'slug' => 'ropa'],
            ['nombre' => 'Hogar', 'slug' => 'hogar'],
        ];
 
        foreach ($categorias as $catData) {
            Categoria::firstOrCreate(
                ['slug' => $catData['slug']],
                $catData
            );
        }
 
        // Crear productos
        $productos = [
            [
                'titulo' => 'Laptop X1',
                'descripcion' => 'Laptop de alto rendimiento',
                'precio' => 1200.50,
                'imagen' => 'https://firebasestorage.googleapis.com/v0/b/catalogo-laravel-49a76.firebasestorage.app/o/laptop.jpg?alt=media&token=76f50616-b9cb-4237-9576-73c85c43ea49',
                'stock' => 10,
            ],
            [
                'titulo' => 'Camisa Casual',
                'descripcion' => 'Camisa de algodón',
                'precio' => 25.99,
                'imagen' => 'https://firebasestorage.googleapis.com/v0/b/catalogo-laravel-49a76.firebasestorage.app/o/camisa.jpg?alt=media&token=5731337c-a11a-4e60-9ba4-939e4010cd81',
                'stock' => 50,
            ],
            [
                'titulo' => 'Sofá Moderno',
                'descripcion' => 'Sofá de diseño cómodo',
                'precio' => 499.99,
                'imagen' => 'https://firebasestorage.googleapis.com/v0/b/catalogo-laravel-49a76.firebasestorage.app/o/sofa.jpg?alt=media&token=784f6247-7454-49f7-ac77-5d9447cd48d9',
                'stock' => 5,
            ],
        ];
 
        foreach ($productos as $prodData) {
            $producto = Producto::UpdateOrCreate(
                ['titulo' => $prodData['titulo']],
                $prodData
            );
 
            // Asignar categorías aleatorias
            $categoriaIds = Categoria::inRandomOrder()->limit(rand(1, 2))->pluck('id')->toArray();
            $producto->categorias()->sync($categoriaIds);
        }
    }
}
