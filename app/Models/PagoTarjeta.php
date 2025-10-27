<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PagoTarjeta extends Model
{
    use HasFactory;

    protected $table = 'pago_tarjetas';

    protected $fillable = [
        'pago_id',
        'payment_token',
    ];

    public function pago(): BelongsTo
    {
        return $this->belongsTo(Pago::class);
    }
}