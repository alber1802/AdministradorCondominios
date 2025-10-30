<?php

namespace App\Filament\IntelliTower\Resources\ReservaResource\Pages;

use App\Filament\IntelliTower\Resources\ReservaResource;
use App\Models\Reserva;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewReserva extends ViewRecord
{
    protected static string $resource = ReservaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->visible(fn (Reserva $record) => in_array($record->estado_reserva, ['pendiente'])),
        ];
    }
}
