<?php

namespace App\Filament\Resources\AvisosResource\Pages;

use App\Filament\Resources\AvisosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAvisos extends ListRecords
{
    protected static string $resource = AvisosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Crear Aviso')
                ->icon('heroicon-o-plus'),
        ];
    }
}
