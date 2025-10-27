<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Anuncio extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'titulo',
        'contenido',
        'tipo',
        'fecha_publicacion',
        'user_id',
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
            'fecha_publicacion' => 'date',
            'user_id' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comentarios(): HasMany
    {
        return $this->hasMany(Comentario::class);
    }

    // public static function boot()
    // {
    //     parent::boot();

    //     static::created(function ($anuncio) {
    //         $residentes = \App\Models\User::whereIn('rol', ['residente', 'inquilino'])->get();
    //         foreach ($residentes as $residente) {
    //             $residente->notify(new \App\Notifications\AnuncioCreado($anuncio));
    //         }
    //     });
    // }
}
