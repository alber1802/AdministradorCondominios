<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PagoTigoMoney extends Model
{
    use HasFactory;

    protected $table = 'pago_tigo_money';

    protected $fillable = [
        'pago_id',
        'numero_telefono',
        'referencia_transaccion',
    ];

    public function pago(): BelongsTo
    {
        return $this->belongsTo(Pago::class);
    }
}