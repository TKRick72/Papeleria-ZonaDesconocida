<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores';

    protected $fillable = [
        'nombre', 
        'contacto_email', 
        'telefono'
    ];

    // RELACIÓN: Un Proveedor suministra muchos Productos. (1:N)
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}