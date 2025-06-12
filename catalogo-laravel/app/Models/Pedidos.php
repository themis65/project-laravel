<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Direcciones;
use App\Models\Productos;

class Pedidos extends Model
{
    protected $fillable = [
        'user_id',
        'direccion_id',
        'total',
        'estado',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function direccion()
    {
        return $this->belongsTo(Direcciones::class);
    }
    public function productos()
    {
        return $this->belongsToMany(Productos::class, 'pedidos_productos', 'pedido_id', 'producto_id')
            ->withPivot('cantidad', 'precio_unitario', 'subtotal')
            ->withTimestamps();
    }
}
