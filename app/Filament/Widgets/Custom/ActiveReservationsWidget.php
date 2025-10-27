<?php

namespace App\Filament\Widgets\Custom;

use Filament\Widgets\Widget;
use App\Models\Reserva;
use App\Models\AreaComun;
use Carbon\Carbon;

class ActiveReservationsWidget extends Widget
{
    protected static string $view = 'filament.widgets.active-reservations';
    
    protected static ?int $sort = 23;
    
    protected static ?string $pollingInterval = '60s';
    
    protected static bool $isLazy = true;
    
    public function getDisplayName(): string {  return " Notificaciones Recientes";   }

    /**
     * Get the active reservations data for the widget
     */
    protected function getViewData(): array
    {
        try {
            $now = now();
            $today = $now->toDateString();
            $nextWeek = $now->addWeek()->toDateString();

            // Get active reservations (currently happening)
            $activeReservations = Reserva::where('estado', 'Confirmada')
                ->where('fecha_inicio', '<=', $now)
                ->where('fecha_fin', '>=', $now)
                ->with(['user', 'areaComun'])
                ->orderBy('fecha_fin', 'asc')
                ->limit(3)
                ->get()
                ->map(function ($reserva) {
                    return [
                        'id' => $reserva->id,
                        'area' => $reserva->areaComun?->nombre ?? 'Área no encontrada',
                        'usuario' => $reserva->user?->name ?? 'Usuario no encontrado',
                        'fecha_inicio' => $reserva->fecha_inicio,
                        'fecha_fin' => $reserva->fecha_fin,
                        'tiempo_restante' => $reserva->fecha_fin->diffForHumans(),
                        'estado' => $reserva->estado,
                    ];
                });

            // Get upcoming reservations (next 7 days)
            $upcomingReservations = Reserva::where('estado', 'Confirmada')
                ->whereBetween('fecha_inicio', [$now, $nextWeek])
                ->with(['user', 'areaComun'])
                ->orderBy('fecha_inicio', 'asc')
                ->limit(5)
                ->get()
                ->map(function ($reserva) {
                    return [
                        'id' => $reserva->id,
                        'area' => $reserva->areaComun?->nombre ?? 'Área no encontrada',
                        'usuario' => $reserva->user?->name ?? 'Usuario no encontrado',
                        'fecha_inicio' => $reserva->fecha_inicio,
                        'fecha_fin' => $reserva->fecha_fin,
                        'tiempo_hasta' => $reserva->fecha_inicio->diffForHumans(),
                        'estado' => $reserva->estado,
                    ];
                });

            // Get area availability status
            $areasStatus = AreaComun::withCount([
                'reservas as reservas_activas' => function ($query) use ($now) {
                    $query->where('estado', 'Confirmada')
                          ->where('fecha_inicio', '<=', $now)
                          ->where('fecha_fin', '>=', $now);
                },
                'reservas as reservas_hoy' => function ($query) use ($today) {
                    $query->where('estado', 'Confirmada')
                          ->whereDate('fecha_inicio', $today);
                }
            ])
            ->get()
            ->map(function ($area) {
                return [
                    'id' => $area->id,
                    'nombre' => $area->nombre,
                    'disponible' => $area->reservas_activas == 0,
                    'reservas_hoy' => $area->reservas_hoy,
                    'estado' => $area->reservas_activas > 0 ? 'Ocupada' : 'Disponible',
                ];
            });

            // Statistics
            $stats = [
                'total_activas' => $activeReservations->count(),
                'total_proximas' => $upcomingReservations->count(),
                'areas_disponibles' => $areasStatus->where('disponible', true)->count(),
                'areas_ocupadas' => $areasStatus->where('disponible', false)->count(),
            ];

            return [
                'activeReservations' => $activeReservations,
                'upcomingReservations' => $upcomingReservations,
                'areasStatus' => $areasStatus,
                'stats' => $stats,
                'hasData' => $activeReservations->isNotEmpty() || $upcomingReservations->isNotEmpty(),
            ];
        } catch (\Exception $e) {
            return [
                'activeReservations' => collect(),
                'upcomingReservations' => collect(),
                'areasStatus' => collect(),
                'stats' => [
                    'total_activas' => 0,
                    'total_proximas' => 0,
                    'areas_disponibles' => 0,
                    'areas_ocupadas' => 0,
                ],
                'hasData' => false,
                'error' => 'Error al cargar datos de reservas',
            ];
        }
    }
}