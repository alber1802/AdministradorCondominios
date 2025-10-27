<?php

namespace App\Filament\Resources\TicketResource\Widgets;

use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class TicketStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';
    protected static bool $isLazy = true;
    protected static ?int $sort = 6;

     public function getDisplayName(): string {  return "Reservas estadísticas ";   }

    protected function getStats(): array
    {
        try {
            $currentMonth = Carbon::now()->startOfMonth();
            
            // Tickets pendientes (abiertos/en progreso)
            $ticketsPendientes = Ticket::whereIn('estado', ['abierto', 'en_progreso'])
                ->count();
            
            // Tickets resueltos este mes
            $ticketsResueltos = Ticket::where('estado', 'resuelto')
                ->where('updated_at', '>=', $currentMonth)
                ->count();
            
            // Tickets por prioridad (solo pendientes)
            $ticketsAlta = Ticket::whereIn('estado', ['abierto', 'en_progreso'])
                ->where('prioridad', 'alta')
                ->count();
            
            $ticketsMedia = Ticket::whereIn('estado', ['abierto', 'en_progreso'])
                ->where('prioridad', 'media')
                ->count();
            
            $ticketsBaja = Ticket::whereIn('estado', ['abierto', 'en_progreso'])
                ->where('prioridad', 'baja')
                ->count();
            
            // Tasa de resolución del mes
            $totalTicketsMes = Ticket::where('created_at', '>=', $currentMonth)->count();
            $tasaResolucion = $totalTicketsMes > 0 
                ? round(($ticketsResueltos / $totalTicketsMes) * 100, 1) 
                : 0;

            return [
                Stat::make('Tickets Pendientes', $ticketsPendientes)
                    ->description($ticketsAlta > 0 ? "{$ticketsAlta} de alta prioridad" : 'Sin tickets de alta prioridad')
                    ->descriptionIcon($ticketsAlta > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-ticket')
                    ->color($ticketsAlta > 0 ? 'danger' : ($ticketsPendientes > 0 ? 'warning' : 'success')),
                    
                Stat::make('Resueltos este Mes', $ticketsResueltos)
                    ->description("Tasa de resolución: {$tasaResolucion}%")
                    ->descriptionIcon('heroicon-m-check-circle')
                    ->color($tasaResolucion >= 80 ? 'success' : ($tasaResolucion >= 60 ? 'warning' : 'danger')),
                    
                Stat::make('Por Prioridad', "{$ticketsAlta} | {$ticketsMedia} | {$ticketsBaja}")
                    ->description('Alta | Media | Baja')
                    ->descriptionIcon('heroicon-m-bars-3-bottom-left')
                    ->color('info'),
            ];
            
        } catch (\Exception $e) {
            return [
                Stat::make('Error', 'No disponible ticket')
                    ->description('Error al cargar datos de tickets')
                    ->descriptionIcon('heroicon-m-exclamation-triangle')
                    ->color('danger')
            ];
        }
    }
}