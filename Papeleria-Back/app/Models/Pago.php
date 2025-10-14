<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'pedido_id', 
        'metodo_pago', 
        'monto', 
        'estado'
    ];

    // RELACIÓN: Un Pago pertenece a un Pedido. (1:1 o 1:N, dependiendo del diseño, pero aquí 1:1)
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }
}