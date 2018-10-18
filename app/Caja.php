<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Caja extends Authenticatable
{
    use Notifiable;

    protected $table = 'caja';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'numero', 'fecha_apertura', 'fecha_cierre', 'monto_apertura', 'monto_cierre', 'estado', 'created_up', 'updated_up'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
}
