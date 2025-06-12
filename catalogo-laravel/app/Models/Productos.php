<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Categorias;
use App\Models\Carritos;
use App\Models\CarritoProducto;
use App\Models\PedidosProductos;

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
        return $this->hasMany(PedidosProductos::class, 'producto_id');
    }
    public function carritos()
    {
        return $this->belongsToMany(Carritos::class, 'carrito_producto', 'producto_id', 'carrito_id')
            ->withPivot('cantidad')
            ->withTimestamps();
    }
}
