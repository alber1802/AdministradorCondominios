<?php

namespace App\Filament\Widgets\Charts;

use App\Models\Factura;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class FacturaRevenueChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Ingresos por Facturas - Últimos 12 Meses';
    protected static string $color = 'success';
    protected static ?string $pollingInterval = '30s';
    protected static bool $isLazy = true;
    protected static ?int $sort = 11;
    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 2,
    ];

    public function getDisplayName(): string {  return "Facturas Estadisticas ";   }

    protected function getData(): array
    {
        $months = [];
        $revenues = [];
        $pendingAmounts = [];

        // Generar datos para los últimos 12 meses
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthYear = $date->format('Y-m');
            $monthName = $date->format('M Y');
            
            // Ingresos pagados (facturas con pagos)
            $paidRevenue = Factura::whereYear('fecha_emision', $date->year)
                ->whereMonth('fecha_emision', $date->month)
                ->whereHas('pagos')
                ->sum('monto');
            
            // Montos pendientes (facturas sin pagos)
            $pendingRevenue = Factura::whereYear('fecha_emision', $date->year)
                ->whereMonth('fecha_emision', $date->month)
                ->whereDoesntHave('pagos')
                ->sum('monto');
            
            $months[] = $monthName;
            $revenues[] = (float) $paidRevenue;
            $pendingAmounts[] = (float) $pendingRevenue;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Ingresos Cobrados',
                    'data' => $revenues,
                    'borderColor' => 'rgb(34, 197, 94)',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Montos Pendientes',
                    'data' => $pendingAmounts,
                    'borderColor' => 'rgb(239, 68, 68)',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
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
                'y' => [
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
                'duration' => 1000,
                'easing' => 'easeInOutQuart',
            ],
        ];
    }
}