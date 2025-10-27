<?php

namespace App\Filament\IntelliTower\Resources\AreaComunResource\Pages;

use App\Filament\IntelliTower\Resources\AreaComunResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAreaComun extends EditRecord
{
    protected static string $resource = AreaComunResource::class;

     protected static ?string $title = 'Reservar Area Comun';

    protected function getHeaderActions(): array
    {
        return [
            //Actions\ViewAction::make(),
            // DeleteAction removed as per requirements - users cannot delete areas
        ];
    }

    protected function getFormActions(): array
    {
        return [
            // Botones "Guardar cambios" y "Cancelar" removidos
        ];
    }
}