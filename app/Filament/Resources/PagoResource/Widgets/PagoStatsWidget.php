<?php

namespace App\Filament\Resources\PagoResource\Widgets;

use App\Models\Pago;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PagoStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';
    protected static bool $isLazy = true;
    protected static ?int $sort = 3;

     public function getDisplayName(): string {  return "Pagos estadísticas ";   }

    protected function getStats(): array
    {
        try {
            $today = Carbon::today();
            $currentMonth = Carbon::now()->startOfMonth();
            
            // Pagos recibidos hoy
            $pagosHoy = Pago::whereDate('fecha_pago', $today)->count();
            
            // Monto total recaudado este mes
            $montoRecaudado = Pago::where('fecha_pago', '>=', $currentMonth)
                ->sum('monto_pagado') ?? 0;
            
            // Método de pago más usado este mes
            $metodosPopulares = Pago::where('fecha_pago', '>=', $currentMonth)
                ->select('tipo_pago', DB::raw('count(*) as total'))
                ->groupBy('tipo_pago')
                ->orderBy('total', 'desc')
                ->first();
            
            $metodoMasUsado = $metodosPopulares ? $metodosPopulares->tipo_pago : 'N/A';
            $cantidadMetodo = $metodosPopulares ? $metodosPopulares->total : 0;
            
            // Pagos pendientes de confirmación (asumiendo que existen pagos sin confirmar)
            $pagosPendientes = Pago::whereNull('fecha_pago')
                ->orWhere('fecha_pago', '>', Carbon::now())
                ->count();

            return [
                Stat::make('Pagos Recibidos Hoy', $pagosHoy)
                    ->description('Transacciones del día')
                    ->descriptionIcon('heroicon-m-banknotes')
                    ->color($pagosHoy > 0 ? 'success' : 'gray'),
                    
                Stat::make('Monto Recaudado', '$' . number_format($montoRecaudado, 2))
                    ->description('Total del mes actual')
                    ->descriptionIcon('heroicon-m-currency-dollar')
                    ->color('success'),
                    
                Stat::make('Método Popular', ucfirst($metodoMasUsado))
                    ->description($cantidadMetodo > 0 ? "{$cantidadMetodo} transacciones este mes" : 'Sin datos disponibles')
                    ->descriptionIcon('heroicon-m-credit-card')
                    ->color('info'),
            ];
            
        } catch (\Exception $e) {
            return [
                Stat::make('Error', 'No disponible Pago')
                    ->description('Error al cargar datos de pagos')
                    ->descriptionIcon('heroicon-m-exclamation-triangle')
                    ->color('danger')
            ];
        }
    }
}