<?php

namespace App\Filament\IntelliTower\Resources\NominaResource\Pages;

use App\Filament\IntelliTower\Resources\NominaResource;
use Filament\Resources\Pages\ListRecords;

class ListNominas extends ListRecords
{
    protected static string $resource = NominaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No incluir CreateAction para usuarios
        ];
    }
}