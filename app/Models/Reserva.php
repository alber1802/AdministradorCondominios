<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notification;

class Reserva extends Model
{
    use HasFactory,HasUuids;

    protected $table = 'reservas';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'area_comun_id',
        'residente_id',
        'fecha_hora_inicio',
        'fecha_hora_fin',
        'costo_total_calculado',
        'estado_reserva',
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
            'residente_id' => 'integer',
            'fecha_hora_inicio' => 'datetime',
            'fecha_hora_fin' => 'datetime',
            'costo_total_calculado' => 'decimal:2',
        ];
    }

    public function residente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'residente_id');
    }

    public function areaComun(): BelongsTo
    {
        return $this->belongsTo(AreaComun::class, 'area_comun_id');
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($reserva) {

        $recipient = User::where('rol', 'admin')->first();


            \Filament\Notifications\Notification::make()
            ->title("Nueva Reserva Creada")
            ->body("Se ha creado una nueva reserva para {$reserva->areaComun->nombre} el {$reserva->fecha_hora_inicio}.")
            ->icon('heroicon-o-bell')
            ->color("primary")
            ->actions([
                \Filament\Notifications\Actions\Action::make('ver')
                    ->label('Ver Reserva')
                    ->url("/admin/reservas/{$reserva->id}")
                    ->button(),
            ])
            ->duration(5000)
            ->sendToDatabase($recipient);

        //se ha creado ua reserva pra el usuario       
            $recipient2 = User::find($reserva->residente_id);

            \Filament\Notifications\Notification::make()
            ->title("Nueva Reserva Creada")
            ->body("Se ha creado una nueva reserva para {$reserva->areaComun->nombre} el {$reserva->fecha_hora_inicio}.")
            ->icon('heroicon-o-bell')
            ->color("primary")
            ->actions([
                \Filament\Notifications\Actions\Action::make('ver')
                    ->label('Ver Reserva')
                    ->url("/intelliTower/reservas/{$reserva->id}")
                    ->color('success')
                    ->button(),
            ])
            ->duration(5000)
            ->sendToDatabase($recipient2);
        
        });


    }
}
