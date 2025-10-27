<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nomina extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'mes',
        'monto',
        'estado',
        'fecha_pago',
        'comprobante_pdf',
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
            'user_id' => 'integer',
            'monto' => 'decimal:2',
            'fecha_pago' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // public static function boot()
    // {
    //     parent::boot();

    //     static::updated(function ($nomina) {
    //         if ($nomina->isDirty('estado') && $nomina->estado === 'pagada') {
    //             $nomina->user->notify(new \App\Notifications\NominaPagada($nomina));
    //             $admins = \App\Models\User::whereIn('rol', ['admin', 'super_admin'])->get();
    //             foreach ($admins as $admin) {
    //                 $admin->notify(new \App\Notifications\NominaPagada($nomina));
    //             }
    //         }
    //     });
    // }
}
