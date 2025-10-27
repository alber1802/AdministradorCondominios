<?php

namespace App\Filament\Exports;

use App\Models\Nomina;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class NominaExporter extends Exporter
{
    protected static ?string $model = Nomina::class;

    public static function getColumns(): array
    {
        return [
            
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('user.name')->label('Nombre Usuario'),
            ExportColumn::make('user.email')->label('Gmail Usuario'),
            ExportColumn::make('mes')->label('Mes'),
            ExportColumn::make('monto')->label('Monto'),
            ExportColumn::make('estado')->label('Estado'),
            ExportColumn::make('fecha_pago')->label('Fechar Pagodo'),
            ExportColumn::make('created_at')->label('Creado'),
            ExportColumn::make('updated_at')->label('Actualizado'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your nomina export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
