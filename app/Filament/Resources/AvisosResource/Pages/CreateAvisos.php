<?php

namespace App\Filament\Resources\AvisosResource\Pages;

use App\Models\User;
use App\Filament\Resources\AvisosResource;
use Filament\Support\Facades\FilamentColor;
use Filament\Resources\Pages\CreateRecord;
use Rupadana\FilamentAnnounce\Announce;
use Rupadana\FilamentAnnounce\Resources\AnnouncementResource;

class CreateAvisos extends CreateRecord
{
    protected static string $resource = AvisosResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
     public function afterCreate()
    {
        $record = $this->getRecord();

        $color = $record->color;
        $custom_color = $record->custom_color;
        $icon = $record->icon;
        $title = $record->title;
        $body = $record->body;

        $isNotifyToAll = in_array('all', $record->users);

        $users = $isNotifyToAll ? User::all() : User::query()->whereIn('id', $record->users)->get();

        $announce = Announce::make();

        if ($title) {
            $announce->title($title);
        }
        if ($body) {
            $announce->body($body);
        }
        if ($icon) {
            $announce->icon($icon);
        }

        if ($color && $color == 'custom') {
            $announce->color(str($custom_color)->remove('rgb(')->remove(')'));
        } else {
            $announce->color(FilamentColor::getColors()[$color]['500']);
        }

        $announce->announceTo($users);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return '¡Aviso creado exitosamente!';
    }
}
