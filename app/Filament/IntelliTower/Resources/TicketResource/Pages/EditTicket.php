<?php

namespace App\Filament\IntelliTower\Resources\TicketResource\Pages;

use App\Filament\IntelliTower\Resources\TicketResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTicket extends EditRecord
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Verificar que el ticket pertenece al usuario autenticado
        if ($data['user_id'] !== auth()->id()) {
            abort(403, 'No tienes permisos para editar este ticket.');
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Asegurar que el user_id no se pueda cambiar
        $data['user_id'] = auth()->id();

        if ($data['titulo'] === 'Daños') {
            $data['prioridad'] = 'Alta';
        } else {
            if ($data['titulo'] === 'Problemas comunes') {
                $data['prioridad'] = 'Media';
            }
        }
        // dd($data);

        return $data;
    }
}
