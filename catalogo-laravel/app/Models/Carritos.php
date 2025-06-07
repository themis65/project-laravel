<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carritos extends Model
{
    protected $fillable = [
        'user_id',
        'producto_id',
        'cantidad',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function producto()
    {
        return $this->belongsTo(Productos::class);
    }
}
