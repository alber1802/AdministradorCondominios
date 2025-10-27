<?php

namespace App\Filament\Resources\FacturaResource\Widgets;

use App\Models\Factura;
use App\Models\Pago;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class FacturaStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';
    protected static bool $isLazy = true;
    protected static ?int $sort = 2;

     public function getDisplayName(): string {  return "Factura estadísticas ";   }

    protected function getStats(): array
    {
        try {
            $currentMonth = Carbon::now()->startOfMonth();
            
            // Total facturas emitidas este mes
            $totalFacturas = Factura::where('fecha_emision', '>=', $currentMonth)->count();
            
            // Monto total pendiente (facturas no pagadas completamente)
            $montoPendiente = Factura::where('estado', '!=', 'pagada')
                ->sum('monto') ?? 0;
            
            // Facturas vencidas
            $facturasVencidas = Factura::where('fecha_vencimiento', '<', Carbon::now())
                ->where('estado', '!=', 'pagada')
                ->count();
            
            // Tasa de cobro mensual (facturas pagadas vs emitidas)
            $facturasPagadas = Factura::where('fecha_emision', '>=', $currentMonth)
                ->where('estado', 'pagada')
                ->count();
            
            $tasaCobro = $totalFacturas > 0 
                ? round(($facturasPagadas / $totalFacturas) * 100, 1) 
                : 0;

            return [
                Stat::make('Facturas Emitidas', $totalFacturas)
                    ->description('Total del mes actual')
                    ->descriptionIcon('heroicon-m-document-text')
                    ->color('info'),
                    
                Stat::make('Monto Pendiente', '$' . number_format($montoPendiente, 2))
                    ->description($facturasVencidas > 0 ? "{$facturasVencidas} facturas vencidas" : 'Sin facturas vencidas')
                    ->descriptionIcon($facturasVencidas > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-currency-dollar')
                    ->color($facturasVencidas > 0 ? 'warning' : 'success'),
                    
                Stat::make('Tasa de Cobro', $tasaCobro . '%')
                    ->description('Facturas pagadas este mes')
                    ->descriptionIcon('heroicon-m-chart-pie')
                    ->color($tasaCobro >= 80 ? 'success' : ($tasaCobro >= 60 ? 'warning' : 'danger')),
            ];
            
        } catch (\Exception $e) {
            return [
                Stat::make('Error', 'No disponible Factura')
                    ->description('Error al cargar datos de facturas')
                    ->descriptionIcon('heroicon-m-exclamation-triangle')
                    ->color('danger')
            ];
        }
    }
}