<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Kenepa\Banner\BannerPlugin;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\View;

class BannerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Registrar configuraciones adicionales si es necesario
    }

    public function boot(): void
    {
        // Configurar el banner para que funcione con múltiples paneles
        $this->configureBannerForMultiplePanels();
        
        // Registrar view composers para pasar datos del panel actual
        $this->registerViewComposers();
    }

    protected function configureBannerForMultiplePanels(): void
    {
        // Extender la configuración del banner para soportar múltiples paneles
        $this->app->afterResolving('config', function () {
            $bannerConfig = config('banner', []);
            
            // Asegurar que la configuración incluya todos los paneles
            if (!isset($bannerConfig['panels'])) {
                $bannerConfig['panels'] = [];
            }
            
            // Configurar panel admin con permisos completos
            if (!isset($bannerConfig['panels']['admin'])) {
                $bannerConfig['panels']['admin'] = [
                    'enabled' => true,
                    'can_manage_banners' => true,
                    'resources' => $this->getResourcesForPanel('admin'),
                    'resource_classes' => $this->getResourceClassesForPanel('admin'),
                ];
            }
            
            // Configurar panel intelliTower solo para mostrar banners
            if (!isset($bannerConfig['panels']['intelliTower'])) {
                $bannerConfig['panels']['intelliTower'] = [
                    'enabled' => true,
                    'can_manage_banners' => false,
                    'resources' => $this->getResourcesForPanel('intelliTower'),
                    'resource_classes' => $this->getResourceClassesForPanel('intelliTower'),
                ];
            }
            
            config(['banner' => $bannerConfig]);
        });
    }

    protected function registerViewComposers(): void
    {
        // Registrar view composer para pasar el panel actual a las vistas
        View::composer('*', function ($view) {
            $currentPanel = session('current_filament_panel', 'admin');
            $view->with('currentFilamentPanel', $currentPanel);
        });
    }

    protected function getResourcesForPanel(string $panelId): array
    {
        $resourceMap = [
            'admin' => [
                // Recursos del panel admin
                'AreaComun',
                'Departamento',
                'Pago',
                'User',
                'Ticket',
                'Nomina',
                // También incluir recursos de intelliTower para gestión completa
                'Factura', // Del panel intelliTower
            ],
            'intelliTower' => [
                'AreaComun',
                'Factura',
                'Ticket',
                'Nomina',
            ],
        ];

        return $resourceMap[$panelId] ?? [];
    }

    protected function getResourceClassesForPanel(string $panelId): array
    {
        $resourceClassMap = [
            'admin' => [
                // Recursos del panel admin
                'App\\Filament\\Resources\\AreaComunResource',
                'App\\Filament\\Resources\\DepartamentoResource',
                'App\\Filament\\Resources\\PagoResource',
                'App\\Filament\\Resources\\UserResource',
                'App\\Filament\\Resources\\TicketResource',
                'App\\Filament\\Resources\\NominaResource',
                // También incluir recursos de intelliTower para gestión completa
                'App\\Filament\\IntelliTower\\Resources\\AreaComunResource',
                'App\\Filament\\IntelliTower\\Resources\\FacturaResource',
                'App\\Filament\\IntelliTower\\Resources\\TicketResource',
                'App\\Filament\\IntelliTower\\Resources\\NominaResource',
            ],
            'intelliTower' => [
                'App\\Filament\\IntelliTower\\Resources\\AreaComunResource',
                'App\\Filament\\IntelliTower\\Resources\\FacturaResource',
                'App\\Filament\\IntelliTower\\Resources\\TicketResource',
                'App\\Filament\\IntelliTower\\Resources\\NominaResource',
            ],
        ];

        return $resourceClassMap[$panelId] ?? [];
    }
}