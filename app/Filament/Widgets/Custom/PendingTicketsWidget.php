<?php

namespace App\Filament\Widgets\Custom;

use Filament\Widgets\Widget;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class PendingTicketsWidget extends Widget
{
    protected static string $view = 'filament.widgets.pending-tickets';
    
    protected static ?int $sort = 24;
    
    protected static ?string $pollingInterval = '30s';
    
    protected static bool $isLazy = true;

    public function getDisplayName(): string {  return "Ticket Pendientes ";   }

    /**
     * Get the pending tickets data for the widget
     */
    protected function getViewData(): array
    {
        try {
            // Get pending tickets (not resolved)
            $pendingTickets = Ticket::whereIn('estado', ['Abierto', 'En Proceso'])
                ->with(['user', 'tecnico'])
                ->orderByRaw("FIELD(prioridad, 'Alta', 'Media', 'Baja')")
                ->orderBy('created_at', 'asc')
                ->limit(5)
                ->get()
                ->map(function ($ticket) {
                    return [
                        'id' => $ticket->id,
                        'titulo' => $ticket->titulo,
                        'descripcion' => $ticket->descripcion,
                        'estado' => $ticket->estado,
                        'prioridad' => $ticket->prioridad,
                        'usuario' => $ticket->user?->name ?? 'Usuario no encontrado',
                        'tecnico' => $ticket->tecnico?->name ?? 'Sin asignar',
                        'created_at' => $ticket->created_at,
                        'tiempo_abierto' => $ticket->created_at->diffForHumans(),
                        'es_urgente' => $ticket->prioridad === 'Alta' && $ticket->created_at->diffInHours(now()) > 24,
                    ];
                });

            // Get tickets statistics by status
            $ticketStats = Ticket::select('estado', DB::raw('count(*) as total'))
                ->groupBy('estado')
                ->pluck('total', 'estado')
                ->toArray();

            // Get tickets statistics by priority
            $priorityStats = Ticket::select('prioridad', DB::raw('count(*) as total'))
                ->whereIn('estado', ['Abierto', 'En Proceso'])
                ->groupBy('prioridad')
                ->pluck('total', 'prioridad')
                ->toArray();

            // Get overdue tickets (high priority open for more than 24 hours)
            $overdueTickets = Ticket::where('prioridad', 'Alta')
                ->whereIn('estado', ['Abierto', 'En Proceso'])
                ->where('created_at', '<', now()->subHours(24))
                ->count();

            // Get unassigned tickets
            $unassignedTickets = Ticket::whereNull('tecnico_id')
                ->whereIn('estado', ['Abierto', 'En Proceso'])
                ->count();

            $stats = [
                'total_pendientes' => ($ticketStats['Abierto'] ?? 0) + ($ticketStats['En Proceso'] ?? 0),
                'abiertos' => $ticketStats['Abierto'] ?? 0,
                'en_proceso' => $ticketStats['En Proceso'] ?? 0,
                'resueltos' => $ticketStats['Resuelto'] ?? 0,
                'alta_prioridad' => $priorityStats['Alta'] ?? 0,
                'media_prioridad' => $priorityStats['Media'] ?? 0,
                'baja_prioridad' => $priorityStats['Baja'] ?? 0,
                'vencidos' => $overdueTickets,
                'sin_asignar' => $unassignedTickets,
            ];

            return [
                'pendingTickets' => $pendingTickets,
                'stats' => $stats,
                'hasTickets' => $pendingTickets->isNotEmpty(),
                'hasUrgentTickets' => $pendingTickets->where('es_urgente', true)->isNotEmpty(),
            ];
        } catch (\Exception $e) {
            return [
                'pendingTickets' => collect(),
                'stats' => [
                    'total_pendientes' => 0,
                    'abiertos' => 0,
                    'en_proceso' => 0,
                    'resueltos' => 0,
                    'alta_prioridad' => 0,
                    'media_prioridad' => 0,
                    'baja_prioridad' => 0,
                    'vencidos' => 0,
                    'sin_asignar' => 0,
                ],
                'hasTickets' => false,
                'hasUrgentTickets' => false,
                'error' => 'Error al cargar datos de tickets',
            ];
        }
    }
}