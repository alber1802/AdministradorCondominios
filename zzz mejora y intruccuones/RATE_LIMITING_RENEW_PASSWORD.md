# 🔐 Control de Intentos - Renovación de Contraseña

## ✅ Implementación Completada

Se ha implementado un sistema de control de intentos (rate limiting) para la renovación de contraseñas con las siguientes características:

## 🎯 Características

### 1. Límite de Intentos
- **Máximo de intentos:** 5 intentos por defecto
- **Tiempo de bloqueo:** 5 minutos (300 segundos) después de exceder los intentos
- **Contador de intentos:** Muestra cuántos intentos quedan

### 2. Notificaciones Visuales
- ⚠️ **Demasiados intentos:** Notificación persistente cuando se excede el límite
- ❌ **Contraseña incorrecta:** Muestra intentos restantes
- ⚠️ **Contraseña repetida:** Avisa si la nueva contraseña es igual a la actual
- ✅ **Éxito:** Confirmación cuando la contraseña se actualiza correctamente
- 🔐 **Información inicial:** Aviso sobre el límite de intentos al cargar la página

### 3. Seguridad
- Cada usuario tiene su propio contador de intentos (basado en email)
- Los intentos se resetean automáticamente después del tiempo de bloqueo
- Los intentos se limpian completamente después de un cambio exitoso
- Validación de que la nueva contraseña sea diferente a la actual

## 📁 Archivos Creados/Modificados

### Nuevos Archivos:
1. **`app/Livewire/CustomRenewPassword.php`**
   - Componente personalizado con rate limiting
   - Extiende la página original del plugin

2. **`config/renew_password_throttle.php`**
   - Configuración centralizada
   - Mensajes personalizables
   - Límites configurables

### Archivos Modificados:
1. **`app/Providers/Filament/IntelliTowerPanelProvider.php`**
   - Registra la página personalizada en el plugin

2. **`.env`**
   - Variables de configuración agregadas

## ⚙️ Configuración

### Variables de Entorno (.env)

```env
# Configuración de Rate Limiting para Renovación de Contraseña
RENEW_PASSWORD_MAX_ATTEMPTS=5          # Número máximo de intentos
RENEW_PASSWORD_DECAY_SECONDS=300       # Tiempo de bloqueo en segundos (5 minutos)
```

### Personalización

Puedes modificar los valores en `config/renew_password_throttle.php`:

```php
return [
    'max_attempts' => env('RENEW_PASSWORD_MAX_ATTEMPTS', 5),
    'decay_seconds' => env('RENEW_PASSWORD_DECAY_SECONDS', 300),
    
    'messages' => [
        'too_many_attempts' => '⚠️ Has excedido el límite...',
        'incorrect_password' => '❌ La contraseña actual es incorrecta...',
        // ... más mensajes
    ],
];
```

## 🧪 Cómo Probar

1. **Forzar renovación de contraseña a un usuario:**
```bash
php artisan tinker
```
```php
DB::table('users')->where('email', 'residente@admin.com')->update([
    'force_renew_password' => true,
    'last_renew_password_at' => null
]);
```

2. **Iniciar sesión con ese usuario** en el panel IntelliTower

3. **Intentar cambiar la contraseña** con contraseña actual incorrecta varias veces

4. **Observar:**
   - Contador de intentos restantes
   - Bloqueo después de 5 intentos
   - Mensaje de tiempo de espera

## 📊 Flujo de Funcionamiento

```
Usuario intenta renovar contraseña
    ↓
¿Excedió límite de intentos?
    ├─ SÍ → Mostrar tiempo de espera + Bloquear
    └─ NO → Continuar
         ↓
¿Contraseña actual correcta?
    ├─ NO → Incrementar contador + Mostrar intentos restantes
    └─ SÍ → Continuar
         ↓
¿Nueva contraseña diferente?
    ├─ NO → Mostrar advertencia
    └─ SÍ → Actualizar contraseña + Limpiar intentos + Éxito
```

## 🔄 Resetear Intentos Manualmente

Si necesitas resetear los intentos de un usuario manualmente:

```bash
php artisan tinker
```
```php
use Illuminate\Support\Facades\RateLimiter;
$email = 'usuario@ejemplo.com';
$key = strtolower($email) . '|renew-password';
RateLimiter::clear($key);
```

## 📝 Notas Importantes

- Los intentos son por usuario (basados en email)
- El bloqueo es temporal (5 minutos por defecto)
- Los intentos se resetean automáticamente después de un cambio exitoso
- Las notificaciones son visuales y amigables para el usuario
- Compatible con el plugin `yebor974/filament-renew-password`

## 🎨 Personalización de Mensajes

Todos los mensajes están en español y pueden ser personalizados en el archivo de configuración. Incluyen emojis para mejor experiencia visual.

## ✨ Beneficios

1. **Seguridad mejorada:** Previene ataques de fuerza bruta
2. **Experiencia de usuario:** Mensajes claros sobre intentos restantes
3. **Configurabilidad:** Fácil de ajustar según necesidades
4. **Integración perfecta:** Funciona con el plugin existente
5. **Sin impacto en rendimiento:** Usa el sistema de cache de Laravel
