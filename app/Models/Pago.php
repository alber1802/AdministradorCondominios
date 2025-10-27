<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [
        'factura_id',
        'tipo_pago',
        'monto_pagado',
        'fecha_pago',
    ];

    protected $casts = [
        'monto_pagado' => 'decimal:2',
        'fecha_pago' => 'date',
    ];

    public function factura(): BelongsTo
    {
        return $this->belongsTo(Factura::class);
    }

    public function pagoTarjeta(): HasOne
    {
        return $this->hasOne(PagoTarjeta::class);
    }

    public function pagoTigoMoney(): HasOne
    {
        return $this->hasOne(PagoTigoMoney::class);
    }

    public function pagoCripto(): HasOne
    {
        return $this->hasOne(PagoCripto::class);
    }

    public function pagoEfectivo(): HasOne
    {
        return $this->hasOne(PagoEfectivo::class);
    }

    // public static function boot()
    // {
    //     parent::boot();

        
    // }
}