<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorias extends Model
{
    protected $fillable = [
        'titulo',
        'slug',
        'descripcion',
    ];

    public function productos()
    {
        return $this->belongsToMany(Productos::class, 'categoria_producto');
    }
}
