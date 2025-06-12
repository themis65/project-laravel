<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Pedidos;
class Direcciones extends Model
{
    protected $table = 'direcciones';
    
    protected $fillable = [
        'user_id',
        'direccion',
        'ciudad',
        'provincia',
        'telefono',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pedidos()
    {
        return $this->hasMany(Pedidos::class, 'direccion _id');
    }
}
