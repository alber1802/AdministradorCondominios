# Guía Completa de Configuración de Filament Breezy

## 📋 Índice
1. [Instalación y Configuración Inicial](#instalación-y-configuración-inicial)
2. [Configuración de Autenticación 2FA](#configuración-de-autenticación-2fa)
3. [Configuración de Secciones del Perfil](#configuración-de-secciones-del-perfil)
4. [Personalización de Componentes](#personalización-de-componentes)
5. [Publicación y Modificación de Vistas](#publicación-y-modificación-de-vistas)
6. [Configuración Avanzada](#configuración-avanzada)

## 🚀 Instalación y Configuración Inicial

### Paso 1: Verificar Instalación
```bash
# El paquete ya está instalado en composer.json
composer show jeffgreco13/filament-breezy
```

### Paso 2: Publicar Configuraciones
```bash
# Publicar todas las configuraciones necesarias
php artisan vendor:publish --all
```

### Paso 3: Ejecutar Migraciones
```bash
# Ejecutar las migraciones para crear las tablas necesarias
php artisan migrate
```

## 🔐 Configuración de Autenticación 2FA

### Paso 1: Instalar Dependencias de 2FA
```bash
# Instalar el paquete para códigos QR
composer require bacon/bacon-qr-code

# Instalar el paquete para TOTP
composer require pragmarx/google2fa
```

### Paso 2: Configurar el Modelo User
El modelo User ya está configurado con:
- Trait `TwoFactorAuthenticatable`
- Campos necesarios para 2FA
- Implementación de `FilamentUser`

### Paso 3: Configurar Variables de Entorno
Agregar al archivo `.env`:
```env
# Configuración de 2FA
BREEZY_FORCE_2FA=false
BREEZY_2FA_ISSUER="Tu Aplicación"

# Configuración de Avatar
FILESYSTEM_DISK=public
```##
 📱 Configuración de Secciones del Perfil

### Secciones Disponibles por Defecto:
1. **Información Personal** - Editar nombre, email, etc.
2. **Actualizar Contraseña** - Cambiar contraseña
3. **Autenticación de Dos Factores** - Configurar 2FA
4. **Tokens de Sanctum** - Gestionar tokens de API

### Personalizar Secciones en AdminPanelProvider:
```php
->myProfileComponents([
    'personal_info' => \Jeffgreco13\FilamentBreezy\Livewire\PersonalInfo::class,
    'update_password' => \Jeffgreco13\FilamentBreezy\Livewire\UpdatePassword::class,
    'two_factor_authentication' => \Jeffgreco13\FilamentBreezy\Livewire\TwoFactorAuthentication::class,
    'sanctum_tokens' => \Jeffgreco13\FilamentBreezy\Livewire\SanctumTokens::class,
    // Agregar componentes personalizados aquí
])
```

## 🎨 Personalización de Componentes

### Paso 1: Crear Componente Personalizado
```bash
# Crear un nuevo componente Livewire
php artisan make:livewire CustomProfileSection
```

### Paso 2: Extender Componente Base
```php
<?php

namespace App\Livewire;

use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

class CustomProfileSection extends MyProfileComponent
{
    protected string $view = "livewire.custom-profile-section";
    
    public array $data = [];
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('custom_field')
                    ->label('Campo Personalizado')
                    ->required(),
            ])
            ->statePath('data');
    }
    
    public function submit()
    {
        $data = $this->form->getState();
        
        // Lógica para guardar datos
        auth()->user()->update($data);
        
        $this->notify('success', 'Datos actualizados correctamente');
    }
}
```

### Paso 3: Crear Vista del Componente
```bash
# Crear la vista
touch resources/views/livewire/custom-profile-section.blade.php
```

Contenido de la vista:
```blade
<x-filament::section>
    <x-slot name="heading">
        Sección Personalizada
    </x-slot>
    
    <form wire:submit="submit">
        {{ $this->form }}
        
        <div class="mt-4">
            <x-filament::button type="submit">
                Guardar
            </x-filament::button>
        </div>
    </form>
</x-filament::section>
```## 
📄 Publicación y Modificación de Vistas

### Paso 1: Publicar Vistas de Breezy
```bash
# Publicar solo las vistas de Breezy
php artisan vendor:publish --tag=filament-breezy-views
```

### Paso 2: Ubicación de las Vistas Publicadas
Las vistas se publican en:
```
resources/views/vendor/filament-breezy/
├── livewire/
│   ├── personal-info.blade.php
│   ├── update-password.blade.php
│   ├── two-factor-authentication.blade.php
│   └── sanctum-tokens.blade.php
└── pages/
    └── my-profile.blade.php
```

### Paso 3: Personalizar Vistas
Puedes modificar cualquier vista publicada para personalizar:
- Estilos CSS
- Estructura HTML
- Textos y etiquetas
- Validaciones del frontend

### Ejemplo de Personalización:
```blade
{{-- resources/views/vendor/filament-breezy/livewire/personal-info.blade.php --}}
<x-filament::section>
    <x-slot name="heading">
        {{ __('Información Personal') }}
    </x-slot>
    
    <x-slot name="description">
        {{ __('Actualiza tu información personal y dirección de email.') }}
    </x-slot>
    
    {{-- Tu contenido personalizado aquí --}}
    <form wire:submit="submit">
        {{ $this->form }}
        
        <div class="mt-6">
            <x-filament::button type="submit" wire:loading.attr="disabled">
                <x-filament::loading-indicator class="h-4 w-4" wire:loading wire:target="submit" />
                {{ __('Guardar Cambios') }}
            </x-filament::button>
        </div>
    </form>
</x-filament::section>
```

## ⚙️ Configuración Avanzada

### Configurar Rate Limiting
En `config/filament-breezy.php`:
```php
'login_rate_limiting' => [
    'enabled' => true,
    'max_attempts' => 5,
    'decay_minutes' => 1,
],

'registration_rate_limiting' => [
    'enabled' => true,
    'max_attempts' => 3,
    'decay_minutes' => 5,
],
```

### Configurar Campos Personalizados del Perfil
```php
'profile_page_fields' => [
    'name' => [
        'type' => 'text',
        'label' => 'Nombre Completo',
        'required' => true,
    ],
    'email' => [
        'type' => 'email',
        'label' => 'Correo Electrónico',
        'required' => true,
    ],
    'phone' => [
        'type' => 'tel',
        'label' => 'Teléfono',
        'required' => false,
    ],
],
```#
## Configurar Middleware Personalizado
```php
'middleware' => [
    'auth' => 'auth:web',
    'guest' => 'guest',
    '2fa' => \App\Http\Middleware\EnsureTwoFactorEnabled::class,
],
```

### Configurar Proveedores de Avatar
```php
'default_avatar_provider' => 'ui-avatars', // 'ui-avatars', 'gravatar', or null
'avatar_disk' => 'public',
'avatar_directory' => 'avatars',
```

## 🔧 Comandos Útiles

### Limpiar Configuraciones
```bash
# Limpiar cache de configuración
php artisan config:clear

# Limpiar cache de vistas
php artisan view:clear

# Limpiar cache de rutas
php artisan route:clear
```

### Regenerar Assets
```bash
# Regenerar assets de Filament
php artisan filament:assets

# Crear enlace simbólico para storage
php artisan storage:link
```

### Testing
```bash
# Ejecutar tests
php artisan test

# Ejecutar tests específicos de autenticación
php artisan test --filter=AuthenticationTest
```

## 🚨 Solución de Problemas Comunes

### Error: "Class not found"
```bash
# Regenerar autoload
composer dump-autoload

# Limpiar cache
php artisan optimize:clear
```

### Error: "2FA not working"
1. Verificar que las migraciones se ejecutaron
2. Verificar que el trait está en el modelo User
3. Verificar configuración en `.env`

### Error: "Avatar upload not working"
1. Verificar permisos de storage
2. Crear enlace simbólico: `php artisan storage:link`
3. Verificar configuración de disco en `config/filesystems.php`

## 📚 Recursos Adicionales

- [Documentación Oficial de Filament Breezy](https://github.com/jeffgreco13/filament-breezy)
- [Documentación de Filament](https://filamentphp.com/docs)
- [Laravel Authentication](https://laravel.com/docs/authentication)

## 🎯 Próximos Pasos

1. Ejecutar las migraciones: `php artisan migrate`
2. Instalar dependencias de 2FA: `composer require bacon/bacon-qr-code pragmarx/google2fa`
3. Configurar variables de entorno
4. Probar la funcionalidad en `/admin`
5. Personalizar según necesidades específicas

---

**¡Configuración completada!** Tu aplicación ahora tiene un sistema completo de gestión de perfiles con autenticación de dos factores.