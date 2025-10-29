<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AreaComun extends Model
{
    use HasFactory;

    protected $table = 'area_comuns';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'descripcion',
        'capacidad',
        'estado',
        'precio_por_hora',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
        ];
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class, 'area_comun_id');
    }

    public function horariosDisponibles(): HasMany
    {
        return $this->hasMany(HorarioDisponible::class, 'area_comun_id');
    }
}
