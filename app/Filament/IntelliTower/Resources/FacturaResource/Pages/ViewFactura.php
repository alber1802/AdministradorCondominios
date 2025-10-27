<?php

namespace App\Filament\IntelliTower\Resources\FacturaResource\Pages;

use App\Filament\IntelliTower\Resources\FacturaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFactura extends ViewRecord
{
    protected static string $resource = FacturaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No edit or delete actions for user panel - read only
        ];
    }
}