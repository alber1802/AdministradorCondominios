<?php

namespace App\Filament\Resources\NominaResource\Pages;

use App\Filament\Resources\NominaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

use Filament\Actions\ExportAction;
use App\Filament\Exports\NominaExporter;



class ListNominas extends ListRecords
{
    protected static string $resource = NominaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ExportAction::make()
                ->exporter(NominaExporter::class)
                ->label('Exportar Nominas en xlsx/csv')
                ->icon('heroicon-o-credit-card')
                ->color('primary'),
            
        ];
    }
}
