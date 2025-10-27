<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departamento extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'numero',
        'piso',
        'bloque',
        'user_id',
        'estado',
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
            'estado' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function consumos(): HasMany
    {
        return $this->hasMany(Consumo::class);
    }

    public function facturas(): HasMany
    {
        return $this->hasMany(Factura::class);
    }

    public static function boot()
        {
            parent::boot();

            static::creating(function ($departamento) {
                $data = $departamento->getAttributes();
                
                if (isset($data['user_id']) && $data['user_id']) {
                    $existingDepartamento = \App\Models\Departamento::where('numero', $data['numero'])
                        ->where('piso', $data['piso'])
                        ->where('bloque', $data['bloque'])
                        ->where('user_id', $data['user_id'])
                        ->first();
                    
                    if ($existingDepartamento) {
                        \Filament\Notifications\Notification::make()
                            ->title('Error')
                            ->body('Este usuario ya tiene asignado este departamento.')
                            ->danger()
                            ->send();
                        
                        return false;
        }
                }
            });

            static::updating(function ($departamento) {
                $data = $departamento->getAttributes();
                
                if (isset($data['user_id']) && $data['user_id']) {
                    $existingDepartamento = \App\Models\Departamento::where('numero', $data['numero'])
                        ->where('piso', $data['piso'])
                        ->where('bloque', $data['bloque'])
                        ->where('user_id', $data['user_id'])
                        ->where('id', '!=', $departamento->id)
                        ->first();
                    
                    if ($existingDepartamento) {
                        \Filament\Notifications\Notification::make()
                            ->title('Error')
                            ->body('Este usuario ya tiene asignado este departamento.')
                            ->danger()
                            ->send();
                        
                        return false;
                    }
                }
            });
        }
        
    }
