<?php

namespace App\Filament\Resources\PagoResource\Pages;

use App\Filament\Exports\PagoExporter;
use App\Filament\Resources\PagoResource;
use Filament\Actions;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;

class ListPagos extends ListRecords
{
    protected static string $resource = PagoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
            ExportAction::make()
                ->exporter(PagoExporter::class)
                ->label('Exportar Pagos en xlsx/csv')
                ->icon('heroicon-o-chart-bar')
                ->color('info'),

        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PagoResource\Widgets\PagoStatsWidget::class,
        ];
    }
}
