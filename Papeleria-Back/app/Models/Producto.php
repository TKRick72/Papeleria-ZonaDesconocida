<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'descripcion', 'precio', 'stock', 'imagen_url', 
        'categoria_id', 'proveedor_id'
    ];

    // RELACIÓN: Un Producto pertenece a una Categoría. (N:1)
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    // RELACIÓN: Un Producto pertenece a un Proveedor. (N:1)
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    // RELACIÓN: Un Producto puede estar en muchos DetallePedido. (1:N)
    public function detallesPedido()
    {
        return $this->hasMany(DetallePedido::class);
    }
}