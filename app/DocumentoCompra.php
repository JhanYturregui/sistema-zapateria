<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class DocumentoCompra extends Authenticatable
{
    use Notifiable;

    protected $table = 'documentos_compra';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'numero', 'fecha','usuario', 'proveedor', 'productos', 'cantidades', 'monto_total', 'estado', 'created_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
}
