<?php

namespace App\Providers\Filament;

use Afsakar\FilamentOtpLogin\FilamentOtpLoginPlugin;
use App\Filament\Pages\CustomChatifyPage;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Kenepa\Banner\BannerPlugin;
use Monzer\FilamentChatifyIntegration\ChatifyPlugin;
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;
use Swis\Filament\Backgrounds\ImageProviders\MyImages;
use Filament\Navigation\MenuItem;

class AdminPanelProvider extends PanelProvider
{
    protected static ?string $title = 'Finance dashboard';

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->passwordReset()
            ->profile()
            ->databaseNotifications()
            ->brandLogo(asset('mis_imagenes/logo/logo-sin-fondo.png'))
            ->brandLogoHeight('5rem')
            
            ->sidebarCollapsibleOnDesktop()
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::Indigo,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->font('Roboto Mono')
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label(fn() => auth()->user()->email),
                MenuItem::make()
                ->label('Mi Perfil')
                ->url('/admin/mi-perfil')
                ->icon('heroicon-o-cog-6-tooth'),
                // ...
            ])

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                // \App\Filament\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Los widgets se ordenan automáticamente por la propiedad $sort
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
            ])
            ->plugin(
                BreezyCore::make()
                    ->avatarUploadComponent(fn ($fileUpload) => $fileUpload->disableLabel()->directory('foto_perfil'))
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
                    ->enableTwoFactorAuthentication(
                        force: false
                    )

            )
            ->plugin(FilamentOtpLoginPlugin::make())
            ->plugins([
                FilamentBackgroundsPlugin::make()
                    ->showAttribution(false)
                    ->imageProvider(
                        MyImages::make()
                            ->directory('\mis_imagenes\fondos')
                    ),
                BannerPlugin::make()
                    ->persistsBannersInDatabase()
                    ->title('Banner/Anuncios')
                    ->navigationGroup('Configuracion')
                    ->subheading('Anuncios para los administradores '),
                ChatifyPlugin::make()
                    ->customPage(CustomChatifyPage::class)
                    ->disableFloatingChatWidget(),
                FilamentShieldPlugin::make(),

            ]);
    }
}
