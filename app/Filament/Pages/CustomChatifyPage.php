<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class CustomChatifyPage extends Page
{
    protected static ?string $slug = 'chat';

    protected static ?string $navigationLabel = 'Chat';

    protected static ?string $title = 'Chat';

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    public function mount()
    {
        return Redirect::to('/chatify');
    }

    protected function getViewData(): array
    {
        return [];
    }
}
