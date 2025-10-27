<?php

namespace App\Filament\IntelliTower\Resources\DepartamentoResource\Pages;

use App\Filament\IntelliTower\Resources\DepartamentoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDepartamento extends ViewRecord
{
    protected static string $resource = DepartamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}