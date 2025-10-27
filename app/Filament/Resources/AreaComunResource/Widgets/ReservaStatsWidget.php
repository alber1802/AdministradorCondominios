<?php

namespace App\Filament\Resources\AreaComunResource\Widgets;

use App\Models\Reserva;
use App\Models\AreaComun;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class ReservaStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';
    protected static bool $isLazy = true;
    protected static ?int $sort = 5;

     public function getDisplayName(): string {  return "Reserva estadísticas ";   }

    protected function getStats(): array
    {
        try {
            $now = Carbon::now();
            $today = $now->startOfDay();
            $endOfWeek = $now->copy()->endOfWeek();
            
            // Reservas activas (en curso actualmente)
            $reservasActivas = Reserva::where('fecha_inicio', '<=', $now)
                ->where('fecha_fin', '>=', $now)
                ->where('estado', 'confirmada')
                ->count();
            
            // Reservas próximas (próximos 7 días)
            $reservasProximas = Reserva::whereBetween('fecha_inicio', [$now, $endOfWeek])
                ->where('estado', 'confirmada')
                ->count();
            
            // Disponibilidad de áreas (áreas disponibles vs total)
            $totalAreas = AreaComun::count();
            $areasDisponibles = AreaComun::where('disponibilidad', true)->count();
            $porcentajeDisponibilidad = $totalAreas > 0 
                ? round(($areasDisponibles / $totalAreas) * 100, 1) 
                : 0;
            
            // Reservas del día de hoy
            $reservasHoy = Reserva::whereDate('fecha_inicio', $today)
                ->where('estado', 'confirmada')
                ->count();

            return [
                Stat::make('Reservas Activas', $reservasActivas)
                    ->description($reservasActivas > 0 ? 'En curso ahora mismo' : 'Sin reservas activas')
                    ->descriptionIcon($reservasActivas > 0 ? 'heroicon-m-clock' : 'heroicon-m-check-circle')
                    ->color($reservasActivas > 0 ? 'info' : 'success'),
                    
                Stat::make('Próximas Reservas', $reservasProximas)
                    ->description("Próximos 7 días ({$reservasHoy} hoy)")
                    ->descriptionIcon('heroicon-m-calendar-days')
                    ->color('warning'),
                    
                Stat::make('Disponibilidad de Áreas', "{$porcentajeDisponibilidad}%")
                    ->description("{$areasDisponibles} de {$totalAreas} áreas disponibles")
                    ->descriptionIcon($porcentajeDisponibilidad >= 70 ? 'heroicon-m-check-circle' : 'heroicon-m-exclamation-triangle')
                    ->color($porcentajeDisponibilidad >= 70 ? 'success' : ($porcentajeDisponibilidad >= 40 ? 'warning' : 'danger')),
            ];
            
        } catch (\Exception $e) {
            return [
                Stat::make('Error', 'No disponible Reserva')
                    ->description('Error al cargar datos de reservas')
                    ->descriptionIcon('heroicon-m-exclamation-triangle')
                    ->color('danger')
            ];
        }
    }
}