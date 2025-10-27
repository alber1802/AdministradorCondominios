<?php

namespace App\Filament\Exports;

use App\Models\Factura;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class FacturaExporter extends Exporter
{
    protected static ?string $model = Factura::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('departamento.numero')
                ->label('Numero_departamento'),
            ExportColumn::make('user.name')
                ->label('Nombre_usuario'),
            ExportColumn::make('user.email')
                ->label('Emal Usuario'),
            ExportColumn::make('tipo')
                ->label('Tipo'),
            ExportColumn::make('monto')
                ->label('Monto'),
            ExportColumn::make('estado')
                ->label('Estado'),
            ExportColumn::make('fecha_emision')
                ->label('Fecha_emision'),
            ExportColumn::make('fecha_vencimiento')
                ->label('Fecha_vencimiento'),
            ExportColumn::make('descripcion')
                ->label('Descripcion'),
            ExportColumn::make('created_at')
                ->label('Creado'),
            ExportColumn::make('updated_at')
                ->label('Actualizado'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your factura export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
