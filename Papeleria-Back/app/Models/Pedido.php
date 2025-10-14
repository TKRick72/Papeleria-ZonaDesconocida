<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables masivamente (permitidos para mass assignment).
     * Corresponden a los campos de tu tabla 'pedidos'.
     */
    protected $fillable = [
        'usuario_id', 
        'fecha_pedido', 
        'estado', 
        'total'
    ];

    /**
     * RELACIÓN: Un Pedido pertenece a un Usuario (Cliente).
     * Clave foránea: usuario_id
     * Tipo: belongsTo (relación N:1)
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    /**
     * RELACIÓN: Un Pedido tiene muchos DetallePedido (Productos y Cantidades).
     * Tipo: hasMany (relación 1:N)
     */
    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }

    /**
     * RELACIÓN: Un Pedido tiene un solo Pago asociado.
     * Tipo: hasOne (relación 1:1)
     */
    public function pago()
    {
        return $this->hasOne(Pago::class);
    }

    /**
     * RELACIÓN: Un Pedido tiene un solo Envío asociado.
     * Tipo: hasOne (relación 1:1)
     */
    public function envio()
    {
        return $this->hasOne(Envio::class);
    }
}
