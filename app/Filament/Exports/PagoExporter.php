<?php

namespace App\Filament\Exports;

use App\Models\Pago;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PagoExporter extends Exporter
{
    protected static ?string $model = Pago::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('factura.id')
                ->label('Factura ID'),
            ExportColumn::make('tipo_pago')
                ->label('Tipo Pago'),
            ExportColumn::make('monto_pagado')
                ->label('Monto Pagado'),
            ExportColumn::make('fecha_pago')
                ->label('Fecha Pago'),
            ExportColumn::make('created_at')
                ->label('Creado'),
            ExportColumn::make('updated_at')
                ->label('Actualizado'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your pago export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
