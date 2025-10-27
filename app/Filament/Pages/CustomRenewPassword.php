<?php
namespace App\Filament\Pages;

use Filament\Facades\Filament;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Filament\Notifications\Notification;
use Yebor974\Filament\RenewPassword\Pages\Auth\RenewPassword as BaseRenewPassword;
use Illuminate\Auth\Events\PasswordReset;

class CustomRenewPassword extends BaseRenewPassword
{
    public function renew()
    {
        /** @var \App\Models\User $user */
        $user = Filament::auth()->user();
        $data = $this->form->getState();

        // Verifica la contraseña actual manualmente
        if (!Hash::check($data['currentPassword'], $user->password)) {
            // Incrementa el contador de fallos
            $user->failed_attempts = ($user->failed_attempts ?? 0) + 1;
            $intentosRestantes = 3 - $user->failed_attempts;

            // Si supera 3 intentos, bloquea al usuario
            if ($user->failed_attempts >= 3) {
                $user->is_blocked = true;
                $user->save();

                Notification::make()
                    ->danger()
                    ->title('Demasiados intentos')
                    ->body('Tu cuenta ha sido bloqueada por demasiados intentos. Contacta al administrador.')
                    ->icon('heroicon-o-exclamation-circle')
                    ->persistent()
                    ->send();

                // Cierra la sesión del usuario
                Filament::auth()->logout();
                return redirect()->to(Filament::getLoginUrl());
            }

            $user->save();

            Notification::make()
                ->danger()
                ->title('Contraseña Incorrecta')
                ->body("Te quedan {$intentosRestantes} intento(s)")
                ->icon('heroicon-o-exclamation-circle')
                ->persistent()
                ->send();

            throw ValidationException::withMessages([
                'data.currentPassword' => 'La contraseña actual es incorrecta.'
            ]);
        }

        // Si la contraseña está bien, reinicia el contador
        $user->failed_attempts = 0;
        $user->is_blocked = false;
        $user->save();

        // Continúa con el proceso normal de renovación
        $this->renewPassword($user, $data);

        if (request()->hasSession()) {
            request()->session()->put([
                'password_hash_' . Filament::getAuthGuard() => $data['password'],
            ]);
        }

        event(new PasswordReset($user));

        Notification::make()
            ->title(__('filament-renew-password::renew-password.notifications.title'))
            ->body(__('filament-renew-password::renew-password.notifications.body'))
            ->success()
            ->send();

        return redirect()->intended(Filament::getUrl());
    }

    protected function getCurrentPasswordFormComponent(): Component
    {
        return TextInput::make('currentPassword')
            ->label(__('filament-renew-password::renew-password.form.current-password.label'))
            ->password()
            ->required()
            // Removemos la validación automática para manejarla manualmente
            ->dehydrated();
    }
}