<?php

namespace App\Filament\Widgets\Charts;

use App\Models\Consumo;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class ConsumoTrendChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Tendencias de Consumo por Tipo - Últimos 6 Meses';
    protected static string $color = 'info';
    protected static ?string $pollingInterval = '30s';
    protected static bool $isLazy = true;
    protected static ?int $sort = 12;

     public function getDisplayName(): string {  return "Consumo ";   }

    protected function getData(): array
    {
        $months = [];
        $consumoData = [];
        
        // Obtener tipos de consumo únicos
        $tiposConsumo = Consumo::distinct('tipo')->pluck('tipo')->toArray();
        
        // Colores para cada tipo de consumo
        $colors = [
            'agua' => 'rgba(59, 130, 246, 0.8)',     // Azul
            'luz' => 'rgba(245, 158, 11, 0.8)',      // Amarillo
            'gas' => 'rgba(239, 68, 68, 0.8)',       // Rojo
            'internet' => 'rgba(34, 197, 94, 0.8)',  // Verde
            'cable' => 'rgba(168, 85, 247, 0.8)',    // Púrpura
        ];

        // Generar datos para los últimos 6 meses
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
        }

        // Preparar datasets para cada tipo de consumo
        foreach ($tiposConsumo as $tipo) {
            $monthlyData = [];
            
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                
                // Calcular costo total por tipo y mes
                $costoTotal = Consumo::where('tipo', $tipo)
                    ->whereYear('fecha', $date->year)
                    ->whereMonth('fecha', $date->month)
                    ->selectRaw('SUM(lectura * costo_unitario) as total_costo')
                    ->value('total_costo') ?? 0;
                
                $monthlyData[] = (float) $costoTotal;
            }
            
            $consumoData[] = [
                'label' => ucfirst($tipo),
                'data' => $monthlyData,
                'backgroundColor' => $colors[$tipo] ?? 'rgba(156, 163, 175, 0.8)',
                'borderColor' => str_replace('0.8', '1', $colors[$tipo] ?? 'rgba(156, 163, 175, 1)'),
                'borderWidth' => 2,
            ];
        }

        return [
            'datasets' => $consumoData,
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) {
                            return context.dataset.label + ": $" + context.parsed.y.toLocaleString();
                        }'
                    ]
                ]
            ],
            'scales' => [
                'x' => [
                    'stacked' => false,
                ],
                'y' => [
                    'stacked' => false,
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) {
                            return "$" + value.toLocaleString();
                        }'
                    ]
                ]
            ],
            'interaction' => [
                'intersect' => false,
                'mode' => 'index',
            ],
            'animation' => [
                'duration' => 1200,
                'easing' => 'easeInOutQuart',
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}