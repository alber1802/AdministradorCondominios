<?php

namespace App\Filament\Resources\ReservaResource\Pages;

use App\Filament\Resources\ReservaResource;
use App\Models\AreaComun;
use App\Models\Reserva;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

use Filament\Forms\Form;
use Illuminate\Support\Carbon;

class CalendarioReservas extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = ReservaResource::class;

    
     protected static string $view = 'filament.resources.reserva-resource.pages.calendario-reservas';

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

        return Reserva::with(['residente', 'areaComun'])
            ->where('area_comun_id', $areaId)
            ->get()
            ->map(function ($reserva) {
                return [
                    'id' => $reserva->id,
                    'title' => $reserva->residente->name,
                    'start' => $reserva->fecha_hora_inicio->toIso8601String(),
                    'end' => $reserva->fecha_hora_fin->toIso8601String(),
                    'backgroundColor' => $this->getColorByEstado($reserva->estado_reserva),
                    'borderColor' => $this->getColorByEstado($reserva->estado_reserva),
                    'extendedProps' => [
                        'residente' => $reserva->residente->name,
                        'area' => $reserva->areaComun->nombre,
                        'estado' => $reserva->estado_reserva,
                        'costo' => '$' . number_format($reserva->costo_total_calculado, 2),
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
