<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Factura extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'facturas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'departamento_id',
        'tipo',
        'monto',
        'estado',
        'fecha_emision',
        'fecha_vencimiento',
        'qr_code',
        'comprobante_pdf',
        'descripcion',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'monto' => 'decimal:2',
            'fecha_emision' => 'date',
            'fecha_vencimiento' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class);
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class);
    }

    // public static function boot()
    // {
    //     parent::boot();

    //     // static::created(function ($factura) {

    //     //     $recipient = $factura->user;
    //     //     SendFacturaCreadaNotificationJob::dispatch($factura,$recipient);
            

    //     //    // $factura->user->notify(new \App\Notifications\FacturaCreada($factura));
    //     // });

    //     static::deleting(function ($factura) {
    //         // $factura->pagos->pagoTarjeta()->delete();
    //         // $factura->pagos->pagoTigoMoney()->delete();
    //         // $factura->pagos->pagoCripto()->delete();
    //         // $factura->pagos->pagoEfectivo()->delete();
    //         $factura->pagos()->delete();
    //     });
    // }

}
