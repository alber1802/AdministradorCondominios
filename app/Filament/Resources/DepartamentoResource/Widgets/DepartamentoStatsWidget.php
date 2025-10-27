<?php

namespace App\Filament\Resources\DepartamentoResource\Widgets;

use App\Models\Departamento;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DepartamentoStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';
    protected static bool $isLazy = true;
    protected static ?int $sort = 7;

     public function getDisplayName(): string {  return "Departamentos estadísticas ";   }

    protected function getStats(): array
    {
        try {
            // Total de departamentos
            $totalDepartamentos = Departamento::count();
            
            // Departamentos asignados (con user_id)
            $departamentosAsignados = Departamento::whereNotNull('user_id')
                ->where('user_id', '>', 0)
                ->count();
            
            // Departamentos disponibles (sin user_id o estado activo)
            $departamentosDisponibles = Departamento::where(function($query) {
                $query->whereNull('user_id')
                      ->orWhere('user_id', 0);
            })
            ->where('estado', true)
            ->count();
            
            // Departamentos inactivos
            $departamentosInactivos = Departamento::where('estado', false)->count();
            
            // Porcentaje de ocupación
            $porcentajeOcupacion = $totalDepartamentos > 0 
                ? round(($departamentosAsignados / $totalDepartamentos) * 100, 1) 
                : 0;
            
            // Distribución por piso (top 3 pisos con más departamentos)
            $distribucionPisos = Departamento::selectRaw('piso, COUNT(*) as total')
                ->groupBy('piso')
                ->orderByDesc('total')
                ->limit(3)
                ->pluck('total', 'piso')
                ->toArray();
            
            $pisosInfo = collect($distribucionPisos)
                ->map(fn($count, $piso) => "P{$piso}: {$count}")
                ->implode(' | ');

            return [
                Stat::make('Departamentos Asignados', $departamentosAsignados)
                    ->description("De {$totalDepartamentos} departamentos totales")
                    ->descriptionIcon('heroicon-m-home')
                    ->color($porcentajeOcupacion >= 80 ? 'success' : ($porcentajeOcupacion >= 60 ? 'warning' : 'info')),
                    
                Stat::make('Disponibles', $departamentosDisponibles)
                    ->description($departamentosInactivos > 0 ? "{$departamentosInactivos} inactivos" : 'Todos activos')
                    ->descriptionIcon($departamentosDisponibles > 0 ? 'heroicon-m-key' : 'heroicon-m-lock-closed')
                    ->color($departamentosDisponibles > 0 ? 'success' : 'warning'),
                    
                Stat::make('Ocupación', "{$porcentajeOcupacion}%")
                    ->description($pisosInfo ?: 'Sin distribución por pisos')
                    ->descriptionIcon('heroicon-m-building-office-2')
                    ->color($porcentajeOcupacion >= 90 ? 'danger' : ($porcentajeOcupacion >= 70 ? 'warning' : 'success')),
            ];
            
        } catch (\Exception $e) {
            return [
                Stat::make('Error', 'No disponible Departamento')
                    ->description('Error al cargar datos de departamentos')
                    ->descriptionIcon('heroicon-m-exclamation-triangle')
                    ->color('danger')
            ];
        }
    }
}