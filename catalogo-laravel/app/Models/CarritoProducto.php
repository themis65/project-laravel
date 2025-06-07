<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarritoProducto extends Model
{
    protected $fillable = [
        'carrito_id',
        'producto_id',
        'cantidad',
    ];
    public function carrito()
    {
        return $this->belongsTo(Carritos::class, 'carrito_id');
    }
    public function producto()
    {
        return $this->belongsTo(Productos::class, 'producto_id');
    }
}
