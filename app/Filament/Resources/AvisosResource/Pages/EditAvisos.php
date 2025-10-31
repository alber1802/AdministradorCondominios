<?php

namespace App\Filament\Resources\AvisosResource\Pages;

use App\Filament\Resources\AvisosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAvisos extends EditRecord
{
    protected static string $resource = AvisosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->label('Ver'),
            Actions\DeleteAction::make()
                ->label('Eliminar'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return '¡Aviso actualizado exitosamente!';
    }
}
