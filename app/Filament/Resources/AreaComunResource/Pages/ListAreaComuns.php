<?php

namespace App\Filament\Resources\AreaComunResource\Pages;

use App\Filament\Resources\AreaComunResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAreaComuns extends ListRecords
{
    protected static string $resource = AreaComunResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AreaComunResource\Widgets\ReservaStatsWidget::class,
        ];
    }
}
