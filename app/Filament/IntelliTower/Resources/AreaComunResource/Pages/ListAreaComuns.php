<?php

namespace App\Filament\IntelliTower\Resources\AreaComunResource\Pages;

use App\Filament\IntelliTower\Resources\AreaComunResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAreaComuns extends ListRecords
{
    protected static string $resource = AreaComunResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}