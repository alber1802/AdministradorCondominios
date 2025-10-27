<?php

namespace App\Filament\IntelliTower\Resources\MascotaResource\Pages;

use App\Filament\IntelliTower\Resources\MascotaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMascota extends EditRecord
{
    protected static string $resource = MascotaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Eliminar mascota')
                ->color('danger'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return '¡Información actualizada! 🐾';
    }
}
