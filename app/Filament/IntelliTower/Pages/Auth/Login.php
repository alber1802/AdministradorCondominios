<?php

namespace App\Filament\IntelliTower\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Notifications\Notification;

class Login extends BaseLogin
{
    public function mount(): void
    {
        parent::mount();
        
        // Mostrar notificación si la cuenta fue bloqueada
        if (session()->has('blocked_account')) {
            Notification::make()
                ->danger()
                ->title('¡Cuenta bloqueada!')
                ->body('Tu cuenta ha sido bloqueada por seguridad. Por favor, contacta al administrador para más información.')
                ->persistent()
                ->send();
                
            session()->forget('blocked_account');
        }
    }
}
