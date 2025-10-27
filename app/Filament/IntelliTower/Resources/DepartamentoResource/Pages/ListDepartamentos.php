<?php

namespace App\Filament\IntelliTower\Resources\DepartamentoResource\Pages;

use App\Filament\IntelliTower\Resources\DepartamentoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDepartamentos extends ListRecords
{
    protected static string $resource = DepartamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}