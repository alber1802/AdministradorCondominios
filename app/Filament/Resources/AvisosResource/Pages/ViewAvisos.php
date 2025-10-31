<?php

namespace App\Filament\Resources\AvisosResource\Pages;

use App\Filament\Resources\AvisosResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAvisos extends ViewRecord
{
    protected static string $resource = AvisosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Editar'),
            Actions\DeleteAction::make()
                ->label('Eliminar'),
        ];
    }
}
