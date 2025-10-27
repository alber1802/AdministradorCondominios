<?php

namespace App\Filament\IntelliTower\Resources\FacturaResource\Pages;

use App\Filament\IntelliTower\Resources\FacturaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

//acciones adiccionar para exportar excel
use App\Filament\Exports\FacturaExporter;
use Filament\Actions\ExportAction;
use HayderHatem\FilamentExcelImport\Actions\FullImportAction;
use HayderHatem\FilamentExcelImport\Actions\Concerns\CanImportExcelRecords;

class ListFacturas extends ListRecords
{
    protected static string $resource = FacturaResource::class;

    protected function getHeaderActions(): array
    {
        return [
             ExportAction::make()
                ->exporter(FacturaExporter::class)
                ->label('Exportar Facturas en xlsx/csv')
                ->icon('heroicon-o-credit-card')
                ->color('warning'),

        ];
    }
}