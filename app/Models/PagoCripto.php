<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PagoCripto extends Model
{
    use HasFactory;

    protected $table = 'pago_criptos';

    protected $fillable = [
        'pago_id',
        'wallet_origen',
        'wallet_destino',
        'moneda',
        'hash_transaccion',
    ];

    public function pago(): BelongsTo
    {
        return $this->belongsTo(Pago::class);
    }
}