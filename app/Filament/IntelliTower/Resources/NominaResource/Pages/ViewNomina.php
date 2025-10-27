<?php

namespace App\Filament\IntelliTower\Resources\NominaResource\Pages;

use App\Filament\IntelliTower\Resources\NominaResource;
use Filament\Resources\Pages\ViewRecord;

class ViewNomina extends ViewRecord
{
    protected static string $resource = NominaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No incluir EditAction ni DeleteAction
        ];
    }
}