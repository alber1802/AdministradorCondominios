<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Notifications\ConsumoCreado;

class Consumo extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'departamento_id',
        'tipo',
        'lectura',
        'unidad',
        'fecha',
        'costo_unitario',
        'alerta',
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
            'departamento_id' => 'integer',
            'lectura' => 'decimal:2',
            'fecha' => 'date',
            'costo_unitario' => 'decimal:2',
            'alerta' => 'boolean',
        ];
    }

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class);
    }

    public static function boot()
    {
        parent::boot();

        // static::created(function ($consumo) {
        //     $consumo->departamento->user->notify(new \App\Notifications\ConsumoCreado($consumo));

        //     ConsumoCreado::sendFilamentNotification($consumo);
        // });
    }
}
