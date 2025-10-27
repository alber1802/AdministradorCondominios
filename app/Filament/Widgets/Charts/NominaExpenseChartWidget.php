<?php

namespace App\Filament\Widgets\Charts;

use App\Models\Nomina;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class NominaExpenseChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Gastos de Nómina - Tendencias Mensuales';
    protected static string $color = 'warning';
    protected static ?string $pollingInterval = '30s';
    protected static bool $isLazy = true;
    protected static ?int $sort = 13;
    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 2,
    ];
         public function getDisplayName(): string {  return "Nominas Estadisticas ";   }

    protected function getData(): array
    {
        $months = [];
        $totalExpenses = [];
        $paidExpenses = [];
        $pendingExpenses = [];

        // Generar datos para los últimos 12 meses
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->format('M Y');
            
            // Total de gastos de nómina del mes
            $totalMonth = Nomina::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('monto');
            
            // Gastos pagados
            $paidMonth = Nomina::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->where('estado', 'pagada')
                ->sum('monto');
            
            // Gastos pendientes
            $pendingMonth = Nomina::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->where('estado', 'pendiente')
                ->sum('monto');
            
            $months[] = $monthName;
            $totalExpenses[] = (float) $totalMonth;
            $paidExpenses[] = (float) $paidMonth;
            $pendingExpenses[] = (float) $pendingMonth;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Nómina',
                    'data' => $totalExpenses,
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Nómina Pagada',
                    'data' => $paidExpenses,
                    'borderColor' => 'rgb(34, 197, 94)',
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Nómina Pendiente',
                    'data' => $pendingExpenses,
                    'borderColor' => 'rgb(245, 158, 11)',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
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
            'elements' => [
                'point' => [
                    'radius' => 4,
                    'hoverRadius' => 6,
                ]
            ],
        ];
    }

    /**
     * Calcular estadísticas adicionales para tooltips
     */
    protected function getViewData(): array
    {
        $currentMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();
        
        $currentTotal = Nomina::whereYear('created_at', $currentMonth->year)
            ->whereMonth('created_at', $currentMonth->month)
            ->sum('monto');
            
        $lastTotal = Nomina::whereYear('created_at', $lastMonth->year)
            ->whereMonth('created_at', $lastMonth->month)
            ->sum('monto');
            
        $trend = $lastTotal > 0 ? (($currentTotal - $lastTotal) / $lastTotal) * 100 : 0;
        
        return [
            'currentMonthTotal' => $currentTotal,
            'trend' => round($trend, 1),
        ];
    }
}