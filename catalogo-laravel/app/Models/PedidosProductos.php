<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pedidos;
use App\Models\Productos;
class PedidosProductos extends Model
{
    protected $fillable = [
        'pedido_id',
        'producto_id',
        'cantidad',
        'precio',
    ];

    public function pedidos()
    {
        return $this->belongsToMany(Pedidos::class, 'pedidos_productos', 'producto_id', 'pedido_id')
            ->withPivot('cantidad', 'precio_unitario', 'subtotal')
            ->withTimestamps();
    }   

    public function productos()
    {
        return $this->belongsTo(Productos::class);
    }
}
