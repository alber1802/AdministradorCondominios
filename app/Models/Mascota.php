<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mascota extends Model
{
    protected $fillable = [
        'propietario_id',
        'nombre',
        'especie',
        'raza',
        'sexo',
        'fecha_nacimiento',
        'peso_kg',
        'color',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'peso_kg' => 'decimal:2',
    ];

    // Relación con el propietario (User)
    public function propietario()
    {
        return $this->belongsTo(User::class, 'propietario_id');
    }
}
