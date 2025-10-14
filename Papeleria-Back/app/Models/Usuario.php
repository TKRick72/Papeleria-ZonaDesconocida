<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Casts\Attribute; // <-- ¡ESTO FALTABA!

class Usuario extends Authenticatable
{
    use HasFactory, HasApiTokens;

    // Mutator moderno para hashear la contraseña automáticamente
    // Se ejecuta cada vez que se asigna un valor a la propiedad 'password'.
    protected function password(): Attribute
    {
        return Attribute::make(
            // La función 'set' es la que maneja la asignación de valor
            set: fn (string $value) => Hash::make($value),
        );
    }
    
    protected $fillable = [
        'nombre', 
        'email', 
        'password', 
        'rol'
    ];

    // RELACIÓN: Un Usuario tiene muchos Pedidos. (1:N)
    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
} 