<?php

namespace App\Filament\Widgets\StatsOverview;

use App\Models\Consumo;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class ConsumoStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';
    protected static bool $isLazy = true;
    protected static ?int $sort = 1;

    public function getDisplayName(): string {  return "Consumo estadísticas ";   }

    protected function getStats(): array
    {
        try {
            // Obtener datos del mes actual
            $currentMonth = Carbon::now()->startOfMonth();
            $previousMonth = Carbon::now()->subMonth()->startOfMonth();
            
            // Total consumos del mes actual
            $totalConsumosMes = Consumo::where('fecha', '>=', $currentMonth)->count();
            
            // Consumo promedio del mes
            $consumoPromedio = Consumo::where('fecha', '>=', $currentMonth)
                ->avg('lectura') ?? 0;
            
            // Alertas activas (consumos con alerta = true)
            $alertasActivas = Consumo::where('alerta', true)
                ->where('fecha', '>=', $currentMonth)
                ->count();
            
            // Comparación con mes anterior
            $consumosMesAnterior = Consumo::whereBetween('fecha', [
                $previousMonth, 
                $currentMonth->copy()->subDay()
            ])->count();
            
            $diferenciaMensual = $totalConsumosMes - $consumosMesAnterior;
            $porcentajeCambio = $consumosMesAnterior > 0 
                ? round(($diferenciaMensual / $consumosMesAnterior) * 100, 1) 
                : 0;

            return [
                Stat::make('Total Consumos del Mes', $totalConsumosMes)
                    ->description($diferenciaMensual >= 0 ? "+{$porcentajeCambio}% vs mes anterior" : "{$porcentajeCambio}% vs mes anterior")
                    ->descriptionIcon($diferenciaMensual >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                    ->color($diferenciaMensual >= 0 ? 'success' : 'danger'),
                    
                Stat::make('Consumo Promedio', number_format($consumoPromedio, 2))
                    ->description('Lectura promedio mensual')
                    ->descriptionIcon('heroicon-m-chart-bar')
                    ->color('info'),
                    
                Stat::make('Alertas Activas', $alertasActivas)
                    ->description($alertasActivas > 0 ? 'Consumos que requieren atención' : 'Sin alertas pendientes')
                    ->descriptionIcon($alertasActivas > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
                    ->color($alertasActivas > 0 ? 'warning' : 'success'),
            ];
            
        } catch (\Exception $e) {
            return [
                Stat::make('Error', 'No disponible Consumo')
                    ->description('Error al cargar datos de consumo')
                    ->descriptionIcon('heroicon-m-exclamation-triangle')
                    ->color('danger')
            ];
        }
    }
}