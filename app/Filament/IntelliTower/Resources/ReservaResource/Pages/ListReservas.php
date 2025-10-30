<?php

namespace App\Filament\IntelliTower\Resources\ReservaResource\Pages;

use App\Filament\IntelliTower\Resources\ReservaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReservas extends ListRecords
{
    protected static string $resource = ReservaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('calendario')
                ->label('Ver Calendario')
                ->icon('heroicon-o-calendar-days')
                ->color('info')
                ->url(fn (): string => ReservaResource::getUrl('calendario')),
            Actions\CreateAction::make()
                ->label('Nueva Reserva'),
        ];
    }
}
