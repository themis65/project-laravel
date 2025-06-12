<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Carritos;
use App\Models\Pedidos;
use App\Models\Direcciones;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

   

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function carritos(){
        return $this->hasOne(Carritos::class, 'user_id');
    }

    public function pedidos(){
        return $this->hasMany(Pedidos::class, 'user_id');
    }

    public function direcciones(){
        return $this->hasMany(Direcciones::class, 'user_id');
    }
}
