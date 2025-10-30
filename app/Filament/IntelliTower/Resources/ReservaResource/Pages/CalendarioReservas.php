<?php

namespace App\Filament\IntelliTower\Resources\ReservaResource\Pages;

use App\Filament\IntelliTower\Resources\ReservaResource;
use App\Models\AreaComun;
use App\Models\Reserva;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;

class CalendarioReservas extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = ReservaResource::class;

    protected static string $view = 'filament.intellitower.resources.reserva-resource.pages.calendario-reservas';

    protected static ?string $title = 'Calendario de Reservas';

    public ?array $data = [];
    
    public $area_comun_id = null;

    public function mount(): void
    {
        $this->form->fill([
            'area_comun_id' => AreaComun::first()?->id,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('area_comun_id')
                    ->label('Seleccionar Área Común')
                    ->options(AreaComun::pluck('nombre', 'id')->toArray())
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function () {
                        $this->dispatch('areaChanged');
                    })
                    ->prefixIcon('heroicon-o-building-office-2')
                    ->placeholder('Seleccione un área común'),
            ])
            ->statePath('data');
    }

    public function getReservas()
    {
        $areaId = $this->data['area_comun_id'] ?? null;

        if (!$areaId) {
            return [];
        }

        // Mostrar todas las reservas del área común (activas)
        return Reserva::with(['residente', 'areaComun'])
            ->where('area_comun_id', $areaId)
            ->whereIn('estado_reserva', ['pendiente', 'confirmada']) // Solo reservas activas
            ->get()
            ->map(function ($reserva) {
                $esMiReserva = $reserva->residente_id === Auth::id();
                
                return [
                    'id' => $esMiReserva ? $reserva->id : null,
                    'title' => $esMiReserva ? 'Mi Reserva' : 'Reservado',
                    'start' => $reserva->fecha_hora_inicio->toIso8601String(),
                    'end' => $reserva->fecha_hora_fin->toIso8601String(),
                    'backgroundColor' => $esMiReserva ? $this->getColorByEstado($reserva->estado_reserva) : '#94a3b8',
                    'borderColor' => $esMiReserva ? $this->getColorByEstado($reserva->estado_reserva) : '#64748b',
                    'extendedProps' => [
                        'esMiReserva' => $esMiReserva,
                        'area' => $reserva->areaComun->nombre,
                        'estado' => $esMiReserva ? $reserva->estado_reserva : null,
                        'costo' => $esMiReserva ? '$' . number_format($reserva->costo_total_calculado, 2) : null,
                        'inicio' => $reserva->fecha_hora_inicio->format('d/m/Y H:i'),
                        'fin' => $reserva->fecha_hora_fin->format('d/m/Y H:i'),
                    ],
                ];
            })
            ->toArray();
    }

    protected function getColorByEstado(string $estado): string
    {
        return match (strtolower($estado)) {
            'confirmada' => '#10b981', // green
            'pendiente' => '#f59e0b', // orange
            'cancelada' => '#ef4444', // red
            'completada' => '#6b7280', // gray
            default => '#3b82f6', // blue
        };
    }

    public function getAreaComun()
    {
        $areaId = $this->data['area_comun_id'] ?? null;
        
        if (!$areaId) {
            return null;
        }

        return AreaComun::find($areaId);
    }
}
