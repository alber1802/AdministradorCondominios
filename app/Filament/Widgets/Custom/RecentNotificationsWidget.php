<?php

namespace App\Filament\Widgets\Custom;

use Filament\Widgets\Widget;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class RecentNotificationsWidget extends Widget
{
    protected static string $view = 'filament.widgets.recent-notifications';
    
    protected static ?int $sort = 21;
    
    protected static ?string $pollingInterval = '30s';

     protected static bool $isDiscovered = false; // No se mostrará automáticamente

    
    protected static bool $isLazy = true;
    
    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 1,
    ];
    public function getDisplayName(): string {  return "Notificaciones Recientes ";   }

    /**
     * Get the recent notifications data for the widget
     */
    protected function getViewData(): array
    {
        try {
            // Get recent notifications for admin users
            $notifications = DatabaseNotification::whereNull('read_at')
                ->orderBy('created_at', 'desc')
                ->where('notifiable_id', Auth::user()->id)
                ->where('notifiable_type', 'App\Models\User')
                ->limit(5)
                ->get()
                ->map(function ($notification) {
                    $data = $notification->data;
                    return [
                        'id' => $notification->id,
                        'title' => $data['title'] ?? 'Notificación',
                        'body' => $data['body'] ?? '',
                        'icon' => $data['icon'] ?? 'heroicon-o-bell',
                        'color' => $data['color'] ?? 'primary',
                        'created_at' => $notification->created_at,
                        'actions' => $data['actions'] ?? [],
                    ];
                });

            return [
                'notifications' => $notifications,
                'hasNotifications' => $notifications->isNotEmpty(),
                'totalUnread' => DatabaseNotification::whereNull('read_at')
                    ->where('notifiable_id', Auth::user()->id)
                    ->where('notifiable_type', 'App\Models\User')
                    ->count(),
            ];
        } catch (\Exception $e) {
            return [
                'notifications' => collect(),
                'hasNotifications' => false,
                'totalUnread' => 0,
                'error' => 'Error al cargar notificaciones',
            ];
        }
    }
}