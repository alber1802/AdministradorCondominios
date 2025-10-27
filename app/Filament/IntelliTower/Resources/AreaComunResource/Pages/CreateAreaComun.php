<?php

namespace App\Filament\IntelliTower\Resources\AreaComunResource\Pages;

use App\Filament\IntelliTower\Resources\AreaComunResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAreaComun extends CreateRecord
{
    protected static string $resource = AreaComunResource::class;

    protected function getFormActions(): array
    {
        return [
            // Botones "Crear" y "Cancelar" removidos
        ];
    }
}