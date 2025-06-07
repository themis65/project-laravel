<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Productos extends Model
{
    protected $fillable = [
        'titulo',
        'slug',
        'descripcion',
        'precio',
        'stock',
        'imagen',
    ];

    public function categorias()
    {
        // Especificar las claves foráneas correctas para la tabla pivote
        return $this->belongsToMany(Categorias::class, 'categoria_producto', 'producto_id', 'categoria_id');
    }

    public function carritosProductos()
    {
        return $this->hasMany(CarritoProducto::class);
    }

    public function pedidosProductos()
    {
        return $this->belongsToMany(PedidosProductos::class);
    }
}
