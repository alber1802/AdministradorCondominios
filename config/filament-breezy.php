<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuración de Autenticación de Dos Factores (2FA)
    |--------------------------------------------------------------------------
    */
    'enable_2fa' => true,
    
    /*
    |--------------------------------------------------------------------------
    | Configuración de Perfil de Usuario
    |--------------------------------------------------------------------------
    */
    'enable_profile_page' => true,
    'show_profile_page_in_navbar' => true,
    'profile_page_group' => 'Settings',
    'profile_page_icon' => 'heroicon-o-user-circle',
    
    /*
    |--------------------------------------------------------------------------
    | Configuración de Avatar
    |--------------------------------------------------------------------------
    */
    'enable_avatar_upload' => true,
    'avatar_disk' => 'public',
    'avatar_directory' => 'avatars',
    'default_avatar_provider' => 'ui-avatars', // 'ui-avatars', 'gravatar', or null
    
    /*
    |--------------------------------------------------------------------------
    | Configuración de Sesiones
    |--------------------------------------------------------------------------
    */
    'enable_sanctum_tokens' => false,
    'show_personal_access_tokens' => false,
    
    /*
    |--------------------------------------------------------------------------
    | Configuración de Registro
    |--------------------------------------------------------------------------
    */
    'enable_registration' => true,
    'registration_rate_limiting' => [
        'enabled' => true,
        'max_attempts' => 5,
        'decay_minutes' => 1,
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Configuración de Login
    |--------------------------------------------------------------------------
    */
    'login_rate_limiting' => [
        'enabled' => true,
        'max_attempts' => 5,
        'decay_minutes' => 1,
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Configuración de Recuperación de Contraseña
    |--------------------------------------------------------------------------
    */
    'enable_password_reset' => true,
    'password_reset_rate_limiting' => [
        'enabled' => true,
        'max_attempts' => 5,
        'decay_minutes' => 1,
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Configuración de Verificación de Email
    |--------------------------------------------------------------------------
    */
    'enable_email_verification' => false,
    
    /*
    |--------------------------------------------------------------------------
    | Configuración de Campos del Perfil
    |--------------------------------------------------------------------------
    */
    'profile_page_fields' => [
        'name' => [
            'type' => 'text',
            'label' => 'Nombre',
            'required' => true,
        ],
        'email' => [
            'type' => 'email',
            'label' => 'Email',
            'required' => true,
        ],
        'password' => [
            'type' => 'password',
            'label' => 'Nueva Contraseña',
            'required' => false,
            'confirmed' => true,
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Configuración de Middleware
    |--------------------------------------------------------------------------
    */
    'middleware' => [
        'auth' => 'auth',
        'guest' => 'guest',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Configuración de Rutas
    |--------------------------------------------------------------------------
    */
    'route_group_prefix' => null,
    'route_group_middleware' => ['web'],
];