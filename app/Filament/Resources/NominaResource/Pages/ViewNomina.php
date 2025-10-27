<?php

namespace App\Filament\Resources\NominaResource\Pages;

use App\Filament\Resources\NominaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewNomina extends ViewRecord
{
    protected static string $resource = NominaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
