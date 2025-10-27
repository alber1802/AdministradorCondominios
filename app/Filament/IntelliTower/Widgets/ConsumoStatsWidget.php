<?php

namespace App\Filament\IntelliTower\Widgets;

use App\Models\Consumo;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ConsumoStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected static bool $isLazy = true;

    protected static ?int $sort = 1;

    public function getDisplayName(): string
    {
        return 'Consumo estadísticas ';
    }

    protected function getStats(): array
    {
        try {
            // Obtener datos del mes actual
            $currentMonth = Carbon::now()->startOfMonth();
            $previousMonth = Carbon::now()->subMonth()->startOfMonth();

            // Obtener los IDs de los departamentos del usuario autenticado
            $departamentosIds = auth()->user()->departamentos()->pluck('id')->toArray();

            // Si el usuario no tiene departamentos asignados, mostrar mensaje
            if (empty($departamentosIds)) {
                return [
                    Stat::make('Sin Departamentos', 'No asignado')
                        ->description('No tienes departamentos asignados')
                        ->descriptionIcon('heroicon-m-exclamation-triangle')
                        ->color('warning'),
                ];
            }

            // Total consumos del mes actual (solo de los departamentos del usuario)
            $totalConsumosMes = Consumo::whereIn('departamento_id', $departamentosIds)
                ->where('fecha', '>=', $currentMonth)
                ->count();

            // Consumo promedio del mes (solo de los departamentos del usuario)
            $consumoPromedio = Consumo::whereIn('departamento_id', $departamentosIds)
                ->where('fecha', '>=', $currentMonth)
                ->avg('lectura') ?? 0;

            // Alertas activas (consumos con alerta = true, solo de los departamentos del usuario)
            $alertasActivas = Consumo::whereIn('departamento_id', $departamentosIds)
                ->where('alerta', true)
                ->where('fecha', '>=', $currentMonth)
                ->count();

            // Comparación con mes anterior (solo de los departamentos del usuario)
            $consumosMesAnterior = Consumo::whereIn('departamento_id', $departamentosIds)
                ->whereBetween('fecha', [
                    $previousMonth,
                    $currentMonth->copy()->subDay(),
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
                    ->color('danger'),
            ];
        }
    }
}
