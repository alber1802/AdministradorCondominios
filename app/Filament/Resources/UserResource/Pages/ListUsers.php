<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Filament\Imports\UserImporter;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use HayderHatem\FilamentExcelImport\Actions\FullImportAction;
use Filament\Actions\ExportAction;
use App\Filament\Exports\UserExporter;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ExportAction::make()
                ->exporter(UserExporter::class)
                ->label('Exportar Usuarios')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('info'),
        ];
    }
}
