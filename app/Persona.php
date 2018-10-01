<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Persona extends Authenticatable
{
    use Notifiable;

    protected $table = 'personas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'tipo_documento', 'numero_documento', 'nombres', 'apellidos', 'correo', 'telefono', 'direccion', 'roles', 'estado'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
}
