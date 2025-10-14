<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Envio extends Model
{
    use HasFactory;

    protected $fillable = [
        'pedido_id', 
        'direccion_envio', 
        'codigo_seguimiento', 
        'estado_envio', 
        'fecha_envio', 
        'fecha_entrega_estimada'
    ];

    // RELACIÓN: Un Envío pertenece a un Pedido. (1:1 o 1:N, aquí 1:1)
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }
}