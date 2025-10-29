<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HorarioDisponible extends Model
{
    use HasFactory;

    protected $table = 'horarios_disponibles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'area_comun_id',
        'dia_semana',
        'hora_apertura',
        'hora_cierre',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'area_comun_id' => 'integer',
            'dia_semana' => 'integer',
        ];
    }

    public function areaComun(): BelongsTo
    {
        return $this->belongsTo(AreaComun::class, 'area_comun_id');
    }
}
