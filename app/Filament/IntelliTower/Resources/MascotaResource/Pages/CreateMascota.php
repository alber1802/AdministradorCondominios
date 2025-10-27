<?php

namespace App\Filament\IntelliTower\Resources\MascotaResource\Pages;

use App\Filament\IntelliTower\Resources\MascotaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMascota extends CreateRecord
{
    protected static string $resource = MascotaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Asignar automáticamente el ID del usuario autenticado como propietario
        $data['propietario_id'] = auth()->id();
        
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return '¡Mascota registrada exitosamente! 🐾';
    }
}
