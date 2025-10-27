<?php

namespace App\Filament\Widgets\Charts;

use App\Models\Pago;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class PagoMethodChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Distribución de Métodos de Pago - Último Mes';
    protected static string $color = 'primary';
    protected static ?string $pollingInterval = '30s';
    protected static bool $isLazy = true;
    protected static ?int $sort = 14;

      public function getDisplayName(): string {  return "Pagos Estadisticas ";   }

    protected function getData(): array
    {
        // Obtener datos del último mes
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        
        // Obtener pagos agrupados por tipo de pago
        $pagosByMethod = Pago::whereBetween('fecha_pago', [$startDate, $endDate])
            ->selectRaw('tipo_pago, COUNT(*) as cantidad, SUM(monto_pagado) as total_monto')
            ->groupBy('tipo_pago')
            ->get();

        $labels = [];
        $data = [];
        $backgroundColors = [];
        $borderColors = [];
        
        // Colores personalizados para cada método de pago
        $methodColors = [
            'efectivo' => ['bg' => 'rgba(34, 197, 94, 0.8)', 'border' => 'rgb(34, 197, 94)'],
            'tarjeta' => ['bg' => 'rgba(59, 130, 246, 0.8)', 'border' => 'rgb(59, 130, 246)'],
            'transferencia' => ['bg' => 'rgba(245, 158, 11, 0.8)', 'border' => 'rgb(245, 158, 11)'],
            'tigo_money' => ['bg' => 'rgba(239, 68, 68, 0.8)', 'border' => 'rgb(239, 68, 68)'],
            'cripto' => ['bg' => 'rgba(168, 85, 247, 0.8)', 'border' => 'rgb(168, 85, 247)'],
            'cheque' => ['bg' => 'rgba(156, 163, 175, 0.8)', 'border' => 'rgb(156, 163, 175)'],
        ];

        foreach ($pagosByMethod as $pago) {
            $methodName = $this->formatMethodName($pago->tipo_pago);
            $labels[] = $methodName;
            $data[] = (float) $pago->total_monto;
            
            $colors = $methodColors[$pago->tipo_pago] ?? $methodColors['cheque'];
            $backgroundColors[] = $colors['bg'];
            $borderColors[] = $colors['border'];
        }

        // Si no hay datos, mostrar mensaje
        if (empty($data)) {
            $labels = ['Sin datos'];
            $data = [1];
            $backgroundColors = ['rgba(156, 163, 175, 0.5)'];
            $borderColors = ['rgb(156, 163, 175)'];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Monto por Método',
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                    'borderColor' => $borderColors,
                    'borderWidth' => 2,
                    'hoverOffset' => 4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    'labels' => [
                        'padding' => 20,
                        'usePointStyle' => true,
                    ]
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ": $" + context.parsed.toLocaleString() + " (" + percentage + "%)";
                        }'
                    ]
                ]
            ],
            'cutout' => '50%',
            'animation' => [
                'animateRotate' => true,
                'animateScale' => true,
                'duration' => 1500,
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }

    /**
     * Formatear nombres de métodos de pago para mostrar
     */
    private function formatMethodName(string $method): string
    {
        $names = [
            'efectivo' => 'Efectivo',
            'tarjeta' => 'Tarjeta',
            'transferencia' => 'Transferencia',
            'tigo_money' => 'Tigo Money',
            'cripto' => 'Criptomoneda',
            'cheque' => 'Cheque',
        ];

        return $names[$method] ?? ucfirst($method);
    }

    /**
     * Obtener estadísticas adicionales
     */
    protected function getViewData(): array
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        
        $totalPagos = Pago::whereBetween('fecha_pago', [$startDate, $endDate])->count();
        $totalMonto = Pago::whereBetween('fecha_pago', [$startDate, $endDate])->sum('monto_pagado');
        
        $metodosUnicos = Pago::whereBetween('fecha_pago', [$startDate, $endDate])
            ->distinct('tipo_pago')
            ->count();

        return [
            'totalPagos' => $totalPagos,
            'totalMonto' => $totalMonto,
            'metodosUnicos' => $metodosUnicos,
        ];
    }
}