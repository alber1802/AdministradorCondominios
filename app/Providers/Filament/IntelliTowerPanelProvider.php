<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Http\Middleware\Auth\BlockUserMiddleware;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;
use Swis\Filament\Backgrounds\ImageProviders\MyImages;
use Afsakar\FilamentOtpLogin\FilamentOtpLoginPlugin;

use Monzer\FilamentChatifyIntegration\ChatifyPlugin;
use App\Filament\Pages\CustomChatifyPage;

use Filament\Navigation\MenuItem;

//reset password  force user 
use Yebor974\Filament\RenewPassword\RenewPasswordPlugin;
use App\Filament\Pages\CustomRenewPassword;

//auth login
use App\Filament\Pages\Auth\EmailVerification;



class IntelliTowerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('intelliTower')
            ->path('intelliTower')
            ->login(\App\Filament\IntelliTower\Pages\Auth\Login::class)
            ->emailVerification(EmailVerification::class)
            ->profile()
            ->brandLogo(asset('mis_imagenes/logo/logo-sin-fondo.png'))
            ->brandLogoHeight('5rem')
            ->colors([
                'danger' => Color::Red,
                'gray' => Color::Slate,
                'info' => Color::Sky,
                'primary' => Color::Blue,
                'success' => Color::Teal,
                'warning' => Color::Amber,
            ])
            ->font('Roboto Mono')
            ->databaseNotifications()
            ->discoverResources(in: app_path('Filament/IntelliTower/Resources'), for: 'App\\Filament\\IntelliTower\\Resources')
            ->discoverPages(in: app_path('Filament/IntelliTower/Pages'), for: 'App\\Filament\\IntelliTower\\Pages')
            ->pages([
                Pages\Dashboard::class,

            ])
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label(fn() => auth()->user()->email),
                MenuItem::make()
                ->label('Mi Perfil')
                ->url('/intelliTower/mi-perfil')
                ->icon('heroicon-o-cog-6-tooth'),
                // ...
            ])
            ->discoverWidgets(in: app_path('Filament/IntelliTower/Widgets'), for: 'App\\Filament\\IntelliTower\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                
               
            ])
            ->authMiddleware([
                Authenticate::class,
                \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
                 BlockUserMiddleware::class,
                
            ])
            ->plugin(
                BreezyCore::make()
                    ->avatarUploadComponent(fn($fileUpload) => $fileUpload->disableLabel()->directory('foto_perfil'))
                    ->myProfile(
                        shouldRegisterUserMenu: true,
                        userMenuLabel: 'Mi Perfil',
                        shouldRegisterNavigation: true,
                        navigationGroup: 'Configuracion',
                        hasAvatars: true,
                        slug: 'mi-perfil'
                    )
                    ->myProfileComponents([
                        'update_password' => \Jeffgreco13\FilamentBreezy\Livewire\UpdatePassword::class,
                        'two_factor_authentication' => \Jeffgreco13\FilamentBreezy\Livewire\TwoFactorAuthentication::class,
                        'custom_profile' => \App\Livewire\CustomProfileSection::class,
                        'personal_info' => \Jeffgreco13\FilamentBreezy\Livewire\PersonalInfo::class,
                        'browser-session' => \Jeffgreco13\FilamentBreezy\Livewire\BrowserSessions::class,
                    ])
                    ->enableTwoFactorAuthentication(force: false)
            )
            ->plugin(FilamentOtpLoginPlugin::make())
            ->plugin(
                RenewPasswordPlugin::make()
                    ->passwordExpiresIn(days: 90) // Renovar cada 90 días
                    ->forceRenewPassword(forceRenewColumn: 'force_renew_password') // Forzar renovación
                    ->renewPage(CustomRenewPassword::class) // Página personalizada con rate limiting
            )   
            ->plugins([
                FilamentBackgroundsPlugin::make()
                    ->showAttribution(false)
                    ->imageProvider(
                        MyImages::make()
                            ->directory('\mis_imagenes\fondos')
                    ),
                ChatifyPlugin::make(),
                // ->customPage(CustomChatifyPage::class)
                // ->disableFloatingChatWidget(),
            ]);
            
    }
}
