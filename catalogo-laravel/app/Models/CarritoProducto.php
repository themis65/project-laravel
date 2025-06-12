<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Carritos;
use App\Models\Productos;

class CarritoProducto extends Model
{
    protected $table = 'carrito_producto';
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
