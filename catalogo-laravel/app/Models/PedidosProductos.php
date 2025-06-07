<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidosProductos extends Model
{
    protected $fillable = [
        'pedido_id',
        'producto_id',
        'cantidad',
        'precio',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedidos::class);
    }

    public function producto()
    {
        return $this->belongsTo(Productos::class);
    }
}
