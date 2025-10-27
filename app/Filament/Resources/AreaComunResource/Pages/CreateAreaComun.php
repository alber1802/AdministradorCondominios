<?php

namespace App\Filament\Resources\AreaComunResource\Pages;

use App\Filament\Resources\AreaComunResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAreaComun extends CreateRecord
{
    protected static string $resource = AreaComunResource::class;

   protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

   
}
