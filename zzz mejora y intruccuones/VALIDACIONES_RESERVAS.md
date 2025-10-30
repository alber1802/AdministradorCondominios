# Validaciones de Reservas

## Resumen
Se han implementado validaciones completas para el sistema de reservas de áreas comunes. Estas validaciones se ejecutan antes de crear o editar una reserva.

## Validaciones Implementadas

### 1. Validación de Área Común
- **Existencia**: Verifica que el área común seleccionada exista en la base de datos
- **Estado**: Valida que el área común tenga estado "disponible"
- **Mensaje**: "El área '{nombre}' no está disponible para reservas en este momento"

### 2. Validación de Fechas y Horarios

#### Fecha de Inicio
- Debe ser posterior a la fecha y hora actual (solo en creación)
- No se permite crear reservas en el pasado

#### Fecha de Fin
- Debe ser posterior a la fecha de inicio
- No puede ser igual o anterior a la fecha de inicio

#### Duración de la Reserva
- **Mínimo**: 15 minutos
- **Máximo**: 24 horas
- Para reservas más largas, se deben crear múltiples reservas

### 3. Validación de Horarios Disponibles

#### Verificación por Día de la Semana
- Consulta la tabla `horarios_disponibles` según el día de la semana
- Días: 0 = Domingo, 1 = Lunes, ..., 6 = Sábado

#### Horario de Apertura y Cierre
- La hora de inicio debe estar dentro del horario de apertura
- La hora de fin debe estar dentro del horario de cierre
- No se permiten reservas que crucen la medianoche

#### Mensajes de Error
- "El área no está disponible los días {día}"
- "La hora de inicio está fuera del horario disponible"
- "La hora de fin está fuera del horario disponible"

### 4. Validación de Conflictos de Reservas

#### Detección de Solapamiento
Se verifica que no existan reservas confirmadas o pendientes que se solapen con el horario solicitado:

- Reservas que inicien durante el período solicitado
- Reservas que terminen durante el período solicitado
- Reservas que contengan completamente el período solicitado

#### Estados Considerados
Solo se validan conflictos con reservas en estado:
- `pendiente`
- `confirmada`

Las reservas `cancelada` o `completada` no generan conflictos.

#### Mensaje de Error
Muestra una lista detallada de las reservas en conflicto:
```
Horario Ocupado
Ya existen reservas en el horario seleccionado:

• 29/10/2025 14:00 - 16:00 (Residente: Juan Pérez)
• 29/10/2025 15:30 - 17:00 (Residente: María García)
```

### 5. Validaciones Especiales en Edición

Al editar una reserva:
- Se excluye la reserva actual de la validación de conflictos
- No se permite cambiar una reserva futura a una fecha pasada
- Se mantienen todas las demás validaciones

## Archivos Modificados

### CreateReserva.php
- `mutateFormDataBeforeCreate()`: Valida datos antes de crear
- `validarHorarioDisponible()`: Verifica horarios del área común
- `validarConflictosReservas()`: Detecta solapamientos
- `getNombreDia()`: Convierte número de día a nombre

### EditReserva.php
- `mutateFormDataBeforeSave()`: Valida datos antes de actualizar
- Mismos métodos de validación que CreateReserva
- Validación adicional para evitar cambios a fechas pasadas

## Flujo de Validación

```
Usuario intenta crear/editar reserva
    ↓
1. Validar área común existe y está disponible
    ↓
2. Validar fechas (inicio < fin, duración válida)
    ↓
3. Validar horario disponible del área común
    ↓
4. Validar conflictos con otras reservas
    ↓
Si todas las validaciones pasan → Crear/Actualizar reserva
Si alguna falla → Mostrar notificación y detener proceso
```

## Notificaciones

Todas las validaciones muestran notificaciones de Filament:
- **Warning** (amarillo): Para validaciones de negocio
- **Danger** (rojo): Para conflictos de horario
- **Success** (verde): Cuando la reserva se crea/actualiza exitosamente

Las notificaciones de conflicto son persistentes y requieren que el usuario las cierre manualmente.

## Recomendaciones de Uso

1. **Configurar Horarios Disponibles**: Asegúrese de que cada área común tenga horarios configurados para todos los días que estará disponible

2. **Mantener Estados Actualizados**: Actualice el estado de las áreas comunes cuando no estén disponibles (mantenimiento, etc.)

3. **Gestionar Reservas Pasadas**: Cambie el estado de reservas completadas para mantener el sistema limpio

4. **Capacitación de Usuarios**: Informe a los usuarios sobre las restricciones de duración y horarios

## Posibles Mejoras Futuras

- Validación de capacidad máxima del área común
- Restricciones por tipo de usuario o rol
- Límite de reservas simultáneas por usuario
- Validación de días festivos o cierres especiales
- Sistema de lista de espera para horarios ocupados
- Notificaciones por email o SMS
