<?php

namespace App\Filament\Widgets\Custom;

use Filament\Widgets\Widget;
use App\Models\Consumo;
use App\Models\Departamento;
use Illuminate\Support\Facades\DB;

class HighConsumptionWidget extends Widget
{
    protected static string $view = 'filament.widgets.high-consumption';
    
    protected static ?int $sort = 22;
    
    protected static ?string $pollingInterval = '60s';
    
    protected static bool $isLazy = true;

    public function getDisplayName(): string {  return "Alto Consumos ";   }

    /**
     * Get the high consumption data for the widget
     */
    protected function getViewData(): array
    {
        try {
            // Get top 5 departments with highest consumption this month
            $currentMonth = now()->format('Y-m');
            
            $highConsumption = Consumo::select(
                'departamento_id',
                DB::raw('SUM(lectura * costo_unitario) as total_costo'),
                DB::raw('SUM(lectura) as total_lectura'),
                DB::raw('COUNT(*) as total_registros')
            )
            ->whereRaw('DATE_FORMAT(fecha, "%Y-%m") = ?', [$currentMonth])
            ->with(['departamento.user'])
            ->groupBy('departamento_id')
            ->orderBy('total_costo', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($consumo) {
                return [
                    'departamento' => $consumo->departamento ? 
                        "Depto {$consumo->departamento->numero} - Piso {$consumo->departamento->piso}" : 
                        'Departamento no encontrado',
                    'propietario' => $consumo->departamento?->user?->name ?? 'Sin asignar',
                    'total_costo' => $consumo->total_costo,
                    'total_lectura' => $consumo->total_lectura,
                    'total_registros' => $consumo->total_registros,
                    'departamento_id' => $consumo->departamento_id,
                ];
            });

            // Get consumption alerts (high consumption warnings)
            $alerts = Consumo::where('alerta', true)
                ->whereRaw('DATE_FORMAT(fecha, "%Y-%m") = ?', [$currentMonth])
                ->with(['departamento.user'])
                ->orderBy('fecha', 'desc')
                ->limit(3)
                ->get()
                ->map(function ($consumo) {
                    return [
                        'departamento' => $consumo->departamento ? 
                            "Depto {$consumo->departamento->numero} - Piso {$consumo->departamento->piso}" : 
                            'Departamento no encontrado',
                        'tipo' => ucfirst($consumo->tipo),
                        'lectura' => $consumo->lectura,
                        'costo' => $consumo->lectura * $consumo->costo_unitario,
                        'fecha' => $consumo->fecha,
                        'departamento_id' => $consumo->departamento_id,
                    ];
                });

            // Calculate average consumption for comparison
            $averageConsumption = Consumo::whereRaw('DATE_FORMAT(fecha, "%Y-%m") = ?', [$currentMonth])
                ->avg(DB::raw('lectura * costo_unitario'));

            return [
                'highConsumption' => $highConsumption,
                'alerts' => $alerts,
                'averageConsumption' => $averageConsumption ?? 0,
                'hasData' => $highConsumption->isNotEmpty() || $alerts->isNotEmpty(),
                'currentMonth' => now()->format('F Y'),
            ];
        } catch (\Exception $e) {
            return [
                'highConsumption' => collect(),
                'alerts' => collect(),
                'averageConsumption' => 0,
                'hasData' => false,
                'error' => 'Error al cargar datos de consumo',
                'currentMonth' => now()->format('F Y'),
            ];
        }
    }
}