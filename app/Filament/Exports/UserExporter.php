<?php

namespace App\Filament\Exports;

use App\Models\User;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class UserExporter extends Exporter
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('name')
                ->label('Nombre'),
            ExportColumn::make('email')
                ->label('Email'),
             ExportColumn::make('telefono')
                ->label('Teléfono'),
             ExportColumn::make('last_renew_password_at')
                ->label('Última Renovación de Contraseña'),
             ExportColumn::make('carnet_identidad')
                ->label('CI'),
            ExportColumn::make('is_blocked')
                ->label('Esta Bloqueado'),
            ExportColumn::make('rol')
                ->label('Rol'),
            ExportColumn::make('created_at')
                ->label('Fecha de Creación'),
            ExportColumn::make('updated_at')
                ->label('Fecha de Actualización'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'La exportación de usuarios se ha completado y ' . number_format($export->successful_rows) . ' ' . str('fila')->plural($export->successful_rows) . ' exportadas.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('fila')->plural($failedRowsCount) . ' fallaron al exportar.';
        }

        return $body;
    }
}