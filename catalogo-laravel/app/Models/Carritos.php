<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Productos;

class Carritos extends Model
{
    protected $fillable = ['user_id', 'estado'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productos()
    {
        return $this->belongsToMany(Productos::class, 'carrito_producto', 'carrito_id', 'producto_id')
            ->withPivot('cantidad')
            ->withTimestamps();
    }
}
