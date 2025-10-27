<?php

return [
    'enabled' => true,
    
    // Configuración de persistencia en base de datos
    'persist_banners_in_database' => true,
    
    // Configuración del recurso de gestión de banners (solo para admin)
    'banner_manager_resource' => [
        'enabled' => true,
        'navigation_group' => 'Sistema',
        'navigation_sort' => 99,
        'navigation_label' => 'Gestión de Banners',
        'model_label' => 'Banner',
        'plural_model_label' => 'Banners',
        'allowed_panels' => ['admin'], // Solo el panel admin puede gestionar banners
    ],
    
    // Configuración específica por panel
    'panels' => [
        'admin' => [
            'enabled' => true,
            'can_manage_banners' => true, // Admin puede gestionar banners
            'resources' => [
                'AreaComun',
                'Departamento',
                'Pago', 
                'User',
                'Ticket',
                'Nomina',
                // También incluir recursos de intelliTower para gestión
                'Factura', // Del panel intelliTower
            ],
            'resource_classes' => [
                'App\\Filament\\Resources\\AreaComunResource',
                'App\\Filament\\Resources\\DepartamentoResource',
                'App\\Filament\\Resources\\PagoResource',
                'App\\Filament\\Resources\\UserResource',
                'App\\Filament\\Resources\\TicketResource',
                'App\\Filament\\Resources\\NominaResource',
                // También incluir clases de intelliTower para gestión completa
                'App\\Filament\\IntelliTower\\Resources\\FacturaResource',
            ],
        ],
        'intelliTower' => [
            'enabled' => true,
            'can_manage_banners' => false, // intelliTower NO puede gestionar banners
            'resources' => [
                'AreaComun',
                'Factura',
                'Ticket',
                'Nomina',
            ],
            'resource_classes' => [
                'App\\Filament\\IntelliTower\\Resources\\AreaComunResource',
                'App\\Filament\\IntelliTower\\Resources\\FacturaResource',
                'App\\Filament\\IntelliTower\\Resources\\TicketResource',
                'App\\Filament\\IntelliTower\\Resources\\NominaResource',
            ],
        ],
    ],
    
    // Configuración de renderizado
    'render_hook' => 'panels::body.start',
    
    // Configuración de estilos
    'banner_styles' => [
        'info' => 'bg-blue-100 border-blue-500 text-blue-700',
        'warning' => 'bg-yellow-100 border-yellow-500 text-yellow-700',
        'success' => 'bg-green-100 border-green-500 text-green-700',
        'danger' => 'bg-red-100 border-red-500 text-red-700',
    ],
];