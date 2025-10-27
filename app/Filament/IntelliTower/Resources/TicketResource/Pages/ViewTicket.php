<?php

namespace App\Filament\IntelliTower\Resources\TicketResource\Pages;

use App\Filament\IntelliTower\Resources\TicketResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTicket extends ViewRecord
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Verificar que el ticket pertenece al usuario autenticado
        if ($data['user_id'] !== auth()->id()) {
            abort(403, 'No tienes permisos para ver este ticket.');
        }
        
        return $data;
    }
}