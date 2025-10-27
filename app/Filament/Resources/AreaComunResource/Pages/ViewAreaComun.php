<?php

namespace App\Filament\Resources\AreaComunResource\Pages;

use App\Filament\Resources\AreaComunResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAreaComun extends ViewRecord
{
    protected static string $resource = AreaComunResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
