<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class DocumentoVenta extends Authenticatable
{
    use Notifiable;

    protected $table = 'documentos_venta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'numero', 'usuario', 'cliente', 'productos', 'cantidades', 'monto_total', 'modos_pago', 'montos', 'estado', 'created_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
}
