<?php

namespace App\Filament\IntelliTower\Resources\AreaComunResource\Pages;

use App\Filament\IntelliTower\Resources\AreaComunResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAreaComun extends ViewRecord
{
    protected static string $resource = AreaComunResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
            ->label('Reservar'),
        ];
    }
}