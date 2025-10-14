<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    use HasFactory;

    // 🚨 CLAVE: Especifica el nombre de la tabla (singular, como en tu BD)
    protected $table = 'detalle_pedido';

    protected $fillable = [
        'pedido_id', 
        'producto_id', 
        'cantidad', 
        'precio_unitario'
    ];
    
    // RELACIÓN: Un detalle pertenece a un Pedido. (N:1)
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    // RELACIÓN: Un detalle se refiere a un Producto. (N:1)
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
