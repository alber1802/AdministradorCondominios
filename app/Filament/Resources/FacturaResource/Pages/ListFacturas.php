<?php

namespace App\Filament\Resources\FacturaResource\Pages;

use App\Filament\Resources\FacturaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

//accionales
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
            Actions\CreateAction::make(),
            ExportAction::make()
                ->exporter(FacturaExporter::class)
                 ->label('Exportar Facturas en xlsx/csv')
                ->icon('heroicon-o-credit-card')
                ->color('warning'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            FacturaResource\Widgets\FacturaStatsWidget::class,
        ];
    }
}
