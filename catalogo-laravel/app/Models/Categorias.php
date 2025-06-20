<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Productos;
class Categorias extends Model
{
    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
    ];

    public function productos()
    {
        return $this->belongsToMany(Productos::class, 'categoria_producto');
    }
}
