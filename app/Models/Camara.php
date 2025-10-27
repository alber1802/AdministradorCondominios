<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Camara extends Model
{
    protected $fillable = [
        'nombre',
        'marca',
        'modelo',
        'numero_serie',
        'tipo',
        'resolucion',
        'ubicacion',
        'direccion_ip',
        'estado',
        'fecha_instalacion',
        'observaciones',
    ];

    protected $casts = [
        'fecha_instalacion' => 'date',
    ];
}
