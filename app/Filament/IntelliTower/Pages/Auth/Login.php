<?php

namespace App\Filament\IntelliTower\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Notifications\Notification;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Illuminate\Support\Facades\Auth;

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

    public function authenticate(): ?LoginResponse
    {
        // Verificar si el usuario ya está autenticado y es admin antes de procesar

        //dd(Auth::user());
        if (Auth::user()->rol == 'super_admin') {
            redirect()->route('filament.admin.pages.dashboard');
            return null;
        }

        return parent::authenticate();
    }
}
