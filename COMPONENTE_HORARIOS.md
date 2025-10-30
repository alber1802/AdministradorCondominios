# Componente de Horarios Disponibles

## Descripción
Componente visual responsive que muestra los horarios disponibles de un área común de forma elegante y adaptable a diferentes dispositivos.

## Características

### 🎨 Diseño Responsive
- **Desktop (lg+)**: Grid de 7 columnas con tarjetas individuales por día
- **Tablet (md-lg)**: Lista horizontal con información compacta
- **Mobile (sm)**: Lista vertical con diseño optimizado para pantallas pequeñas

### 🌓 Soporte Dark/Light Mode
- Colores adaptativos que cambian automáticamente según el tema
- Gradientes y sombras optimizados para ambos modos
- Contraste mejorado para mejor legibilidad

### ✨ Características Visuales
- **Animaciones suaves**: Transiciones y hover effects
- **Iconos intuitivos**: Checkmarks para disponible, X para cerrado
- **Código de colores**:
  - Verde (success): Días disponibles
  - Gris: Días cerrados
- **Estados visuales claros**: Badges, bordes y fondos diferenciados

## Estructura de Archivos

```
resources/views/filament/components/
└── horarios-disponibles.blade.php    # Vista del componente

app/Filament/Forms/Components/
└── HorariosDisponibles.php            # Clase del componente (opcional)
```

## Uso en Formularios

### Opción 1: ViewField (Recomendado)
```php
use Filament\Forms\Get;

Forms\Components\ViewField::make('horarios_info')
    ->label('')
    ->view('filament.components.horarios-disponibles')
    ->viewData(fn (Get $get) => [
        'areaId' => $get('area_comun_id'),
    ])
    ->columnSpanFull()
```

### Opción 2: Componente Personalizado
```php
use App\Filament\Forms\Components\HorariosDisponibles;

HorariosDisponibles::make('horarios')
    ->label('')
    ->areaId(fn (Get $get) => $get('area_comun_id'))
    ->columnSpanFull()
```

## Integración en ReservaResource

El componente se ha integrado en la sección "Información de la Reserva" y se actualiza automáticamente cuando el usuario selecciona un área común diferente gracias al sistema `live()` de Filament.

```php
Forms\Components\Section::make('Información de la Reserva')
    ->schema([
        Forms\Components\Select::make('area_comun_id')
            ->live()  // Importante para actualizar el componente
            // ... otras configuraciones
        
        Forms\Components\ViewField::make('horarios_info')
            ->view('filament.components.horarios-disponibles')
            ->viewData(fn (Get $get) => [
                'areaId' => $get('area_comun_id'),
            ])
            ->columnSpanFull(),
    ])
```

## Estados del Componente

### 1. Sin Área Seleccionada
Muestra un mensaje amigable pidiendo al usuario que seleccione un área común.

### 2. Área Sin Horarios
Muestra un estado vacío indicando que el área no tiene horarios configurados.

### 3. Área Con Horarios
Muestra una cuadrícula/lista con todos los días de la semana y sus horarios.

## Diseño Visual

### Desktop View
```
┌─────────────────────────────────────────────────────┐
│  🕐 Horarios Disponibles - Piscina                  │
├─────────────────────────────────────────────────────┤
│  ┌───┐ ┌───┐ ┌───┐ ┌───┐ ┌───┐ ┌───┐ ┌───┐       │
│  │Lun│ │Mar│ │Mié│ │Jue│ │Vie│ │Sáb│ │Dom│       │
│  │ ✓ │ │ ✓ │ │ ✓ │ │ ✓ │ │ ✓ │ │ ✓ │ │ ✗ │       │
│  │8:00│ │8:00│ │8:00│ │8:00│ │8:00│ │9:00│ │---│       │
│  │20:00│ │20:00│ │20:00│ │20:00│ │20:00│ │18:00│ │---│       │
│  └───┘ └───┘ └───┘ └───┘ └───┘ └───┘ └───┘       │
└─────────────────────────────────────────────────────┘
```

### Mobile View
```
┌─────────────────────────────┐
│ 🕐 Horarios Disponibles     │
│    Piscina                  │
├─────────────────────────────┤
│ ✓ Lunes      [Disponible]   │
│   🕐 08:00 - 20:00          │
├─────────────────────────────┤
│ ✓ Martes     [Disponible]   │
│   🕐 08:00 - 20:00          │
├─────────────────────────────┤
│ ✗ Domingo    [Cerrado]      │
└─────────────────────────────┘
```

## Colores y Temas

### Light Mode
- **Disponible**: Verde success (#10b981)
- **Cerrado**: Gris neutro (#6b7280)
- **Fondo**: Blanco con bordes sutiles
- **Header**: Gradiente azul primary

### Dark Mode
- **Disponible**: Verde success más brillante (#34d399)
- **Cerrado**: Gris oscuro (#4b5563)
- **Fondo**: Gris oscuro (#1f2937)
- **Header**: Gradiente azul primary oscuro

## Datos Requeridos

El componente espera que la tabla `horarios_disponibles` tenga:
- `area_comun_id`: ID del área común
- `dia_semana`: Número del día (0=Domingo, 1=Lunes, ..., 6=Sábado)
- `hora_apertura`: Hora de apertura (formato TIME)
- `hora_cierre`: Hora de cierre (formato TIME)

## Personalización

### Cambiar Colores
Edita las clases de Tailwind en `horarios-disponibles.blade.php`:
```php
// Cambiar color de disponible de verde a azul
'border-success-500' → 'border-primary-500'
'bg-success-50' → 'bg-primary-50'
```

### Agregar Más Información
Puedes extender el componente para mostrar:
- Capacidad del área
- Precio por hora
- Reservas actuales del día
- Disponibilidad en tiempo real

### Ejemplo de Extensión
```php
@if($disponible)
    <div class="mt-2 text-xs text-gray-600 dark:text-gray-400">
        Capacidad: {{ $areaComun->capacidad }} personas
    </div>
@endif
```

## Ventajas del Componente

1. **UX Mejorada**: Los usuarios ven inmediatamente qué días están disponibles
2. **Prevención de Errores**: Reduce intentos de reserva en días cerrados
3. **Información Clara**: Horarios visibles antes de seleccionar fechas
4. **Responsive**: Funciona perfectamente en cualquier dispositivo
5. **Accesible**: Colores con buen contraste y estructura semántica

## Compatibilidad

- ✅ Filament v3.x
- ✅ Laravel 10+
- ✅ Tailwind CSS 3.x
- ✅ Alpine.js (incluido en Filament)
- ✅ Todos los navegadores modernos

## Troubleshooting

### El componente no se actualiza al cambiar el área
Asegúrate de que el Select tenga `->live()`:
```php
Forms\Components\Select::make('area_comun_id')
    ->live()  // ← Importante
```

### Los colores no se ven bien en dark mode
Verifica que tu tema de Filament tenga dark mode habilitado en `tailwind.config.js`:
```js
darkMode: 'class',
```

### El componente no aparece
Verifica que la vista esté en la ruta correcta:
```
resources/views/filament/components/horarios-disponibles.blade.php
```
