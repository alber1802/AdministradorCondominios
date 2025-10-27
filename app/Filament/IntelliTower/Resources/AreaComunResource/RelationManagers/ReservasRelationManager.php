<?php

namespace App\Filament\IntelliTower\Resources\AreaComunResource\RelationManagers;

use App\Models\Reserva;
use App\Models\AreaComun;
use App\Models\User;
use Filament\Forms;

use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Validation\ValidationException;

use Filament\Forms\Components\DateTimePicker;

class ReservasRelationManager extends RelationManager
{

    protected static ?string $model = Reserva::class;

    protected static string $relationship = 'reservas'; 

    protected static ?string $recordTitleAttribute = 'fecha_inicio';

    protected static ?string $title = 'Mis Reservas';

    protected static ?string $modelLabel = 'Reserva';

    protected static ?string $pluralModelLabel = 'Reservas';

    /**
     * Filter reservas to show only those belonging to the authenticated user
     */
    public function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    /**
     * Validate if the area is available for the requested time slot
     */
    protected function validateReservationAvailability(array $data, $action, ?int $excludeReservationId = null): void
    {
        // Check if area is available
        if (!$this->ownerRecord->disponibilidad) {
            Notification::make()
                ->title('Área no disponible')
                ->body('Esta área común no está disponible para reservas en este momento.')
                ->danger()
                ->send();
            
            $action->halt();
            return;
        }

        $fechaInicio = \Carbon\Carbon::parse($data['fecha_inicio']);
        $fechaFin = \Carbon\Carbon::parse($data['fecha_fin']);

        // Validate that the reservation is not in the past (only check date, not time)
        if ($fechaInicio->startOfDay()->lt(now()->startOfDay())) {
            Notification::make()
                ->title('Fecha no válida')
                ->body('No se pueden crear reservas en fechas pasadas.')
                ->danger()
                ->send();
            
            $action->halt();
            return;
        }

        // Validate that end time is after start time
        if ($fechaFin <= $fechaInicio) {
            Notification::make()
                ->title('Error en las fechas')
                ->body('La fecha de fin debe ser posterior a la fecha de inicio.')
                ->danger()
                ->send();
            
            $action->halt();
            return;
        }

        // Verificar si existen reservas que se superponen con el horario solicitado
        // Esta consulta busca conflictos de horario en la base de datos
        $conflictingReservations = Reserva::where('area_id', $this->ownerRecord->id)
            // Solo considerar reservas que no estén canceladas
            ->where('estado', '!=', 'cancelada')
            // Si estamos editando una reserva existente, excluirla de la búsqueda
            ->when($excludeReservationId, function ($query) use ($excludeReservationId) {
                return $query->where('id', '!=', $excludeReservationId);
            })
            // Buscar cualquier tipo de superposición de horarios
            ->where(function ($query) use ($fechaInicio, $fechaFin) {
                $query->where(function ($q) use ($fechaInicio, $fechaFin) {
                    // Caso 1: La nueva reserva inicia durante una reserva existente
                    // Ejemplo: Existente [10:00-14:00], Nueva [12:00-16:00]
                    $q->where('fecha_inicio', '<=', $fechaInicio)
                      ->where('fecha_fin', '>', $fechaInicio);
                })->orWhere(function ($q) use ($fechaInicio, $fechaFin) {
                    // Caso 2: La nueva reserva termina durante una reserva existente
                    // Ejemplo: Existente [12:00-16:00], Nueva [10:00-14:00]
                    $q->where('fecha_inicio', '<', $fechaFin)
                      ->where('fecha_fin', '>=', $fechaFin);
                })->orWhere(function ($q) use ($fechaInicio, $fechaFin) {
                    // Caso 3: La nueva reserva contiene completamente a una reserva existente
                    // Ejemplo: Existente [12:00-14:00], Nueva [10:00-16:00]
                    $q->where('fecha_inicio', '>=', $fechaInicio)
                      ->where('fecha_fin', '<=', $fechaFin);
                });
            })
            // Obtener solo la primera reserva conflictiva encontrada
            ->first();

        if ($conflictingReservations) {
            $conflictStart = \Carbon\Carbon::parse($conflictingReservations->fecha_inicio)->format('d/m/Y H:i');
            $conflictEnd = \Carbon\Carbon::parse($conflictingReservations->fecha_fin)->format('d/m/Y H:i');
            
            Notification::make()
                ->title('Horario no disponible')
                ->body("Ya existe una reserva en este horario desde {$conflictStart} hasta {$conflictEnd}. Por favor, seleccione otro horario.")
                ->danger()
                ->persistent()
                ->send();
            
            $action->halt();
            return;
        }

        // Additional validation: Maximum 8 hours per reservation
        $duration = ceil((($fechaInicio->diffInMinutes($fechaFin))/60)/4.5);
       // dd($duration);
        if ($duration >= 8) {
            Notification::make()
                ->title('Duración excesiva')
                ->body('Las reservas no pueden exceder las 8 horas de duración.')
                ->warning()
                ->send();
            
            $action->halt();
            return;
        }

        // Success notification
        Notification::make()
            ->title('Horario disponible')
            ->body('El horario seleccionado está disponible para la reserva.')
            ->success()
            ->send();
    }


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información de la Reserva')
                    ->description('Complete los datos de la reserva del área común')
                    ->icon('heroicon-o-calendar-days')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                // Hidden field for user_id - automatically set to authenticated user
                                Forms\Components\Hidden::make('user_id')
                                    ->default(auth()->id()),

                                Forms\Components\Select::make('estado')
                                    ->label('Estado de la Reserva')
                                    ->options([
                                        'pendiente' => 'Pendiente',
                                        'confirmada' => 'Confirmada',
                                        'cancelada' => 'Cancelada',
                                    ])
                                    ->default('pendiente')
                                    ->required()
                                    ->prefixIcon('heroicon-o-flag'),
                            ]),
                    ]),
                
                

                Forms\Components\Section::make('Horario de la Reserva')
                    ->description('Defina el período de tiempo para la reserva')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                DatetimePicker::make('fecha_inicio')
                                    ->hourMode(12)
                                    ->default(now())
                                    ->prefixIcon('heroicon-o-play')
                                    ->displayFormat('d/m/y h:i A')
                                    ->label('Fecha y Hora de Inicio')
                                    ->minDate(now()->startOfDay())
                                    ->maxDate(now()->addWeek()->endOfDay())
                                    ->required()
                                    ->live() // Hace que el campo sea reactivo en tiempo real, actualizando automáticamente otros campos cuando cambia su valor
                                    ->seconds(false), // 15/02/24 11:10 PM

                                DatetimePicker::make('fecha_fin')
                                    ->hourMode(12)
                                    ->minDate(now()->startOfDay())
                                    ->maxDate(now()->addWeek()->endOfDay())
                                    ->prefixIcon('heroicon-o-play')
                                    ->displayFormat('d/m/y h:i A')
                                    ->label('Fecha y Hora de Inicio')
                                    ->required()
                                    ->live() // Hace que el campo sea reactivo en tiempo real, actualizando automáticamente otros campos cuando cambia su valor
                                    ->seconds(false), // 15/02/24 11:10 PM
                            ]),

                        Forms\Components\Placeholder::make('duracion_info')
                            ->label('Información de Duración')
                            ->content(function (Forms\Get $get): string {
                                $inicio = $get('fecha_inicio');
                                $fin = $get('fecha_fin');
                                
                                if (!$inicio || !$fin) {
                                    return 'Seleccione las fechas para ver la duración';
                                }
                                
                                $start = \Carbon\Carbon::parse($inicio);
                                $end = \Carbon\Carbon::parse($fin);
                                $duration = round($start->diffInHours($end), 1);
                                
                                if($duration > 24) {
                                    return "La reserva no puede durar más de 24 horas:  ({$duration} hora(s))";
                                }
                                if($duration <= 1) {
                                    return "La reserva no puede durar menos de 1 horas:  ({$duration} hora(s))";
                                }
                                    
                                return "Duración: {$duration} hora(s)";
                            })
                            ->columnSpanFull()
                            ->dehydrated(false),
                    ]),
            ])
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('fecha_inicio')
            ->columns([
                Tables\Columns\TextColumn::make('fecha_inicio')
                    ->label('Fecha y Hora de Inicio')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->icon('heroicon-o-play')
                    ->color('success'),

                Tables\Columns\TextColumn::make('fecha_fin')
                    ->label('Fecha y Hora de Fin')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->icon('heroicon-o-stop')
                    ->color('warning'),

                Tables\Columns\TextColumn::make('duracion')
                    ->label('Duración')
                    ->getStateUsing(function ($record) {
                        $inicio = \Carbon\Carbon::parse($record->fecha_inicio);
                        $fin = \Carbon\Carbon::parse($record->fecha_fin);
                        $totalMinutos = $inicio->diffInMinutes($fin);
                        $horas = round($totalMinutos / 60, 1);
                        
                        return "{$horas}h";
                    })
                     ->icon('heroicon-o-clock')
                    ->alignCenter(),

                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'pendiente',
                        'success' => 'confirmada',
                        'danger' => 'cancelada',
                        'info' => 'completada',
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'pendiente',
                        'heroicon-o-check-circle' => 'confirmada',
                        'heroicon-o-x-circle' => 'cancelada',
                        'heroicon-o-check-badge' => 'completada',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon('heroicon-o-calendar'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'confirmada' => 'Confirmada',
                        'cancelada' => 'Cancelada',
                        'completada' => 'Completada',
                    ])
                    ->multiple(),

                Tables\Filters\Filter::make('fecha_inicio')
                    ->form([
                        Forms\Components\DatePicker::make('desde')
                            ->label('Desde'),
                        Forms\Components\DatePicker::make('hasta')
                            ->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['desde'],
                                fn (Builder $query, $date): Builder => $query->whereDate('fecha_inicio', '>=', $date),
                            )
                            ->when(
                                $data['hasta'],
                                fn (Builder $query, $date): Builder => $query->whereDate('fecha_inicio', '<=', $date),
                            );
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Nueva Reserva')
                    ->icon('heroicon-o-plus')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['area_id'] = $this->ownerRecord->id;
                        $data['user_id'] = auth()->id(); // Ensure user_id is set to authenticated user
                        return $data;
                    })
                    ->before(function (Tables\Actions\CreateAction $action, array $data) {
                        $this->validateReservationAvailability($data, $action);
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->color('info')
                        ->icon('heroicon-o-eye'),
                    Tables\Actions\EditAction::make()
                        ->color('warning')
                        ->icon('heroicon-o-pencil-square')
                        ->mutateFormDataUsing(function (array $data): array {
                            $data['area_id'] = $this->ownerRecord->id;
                            $data['user_id'] = auth()->id(); // Ensure user_id remains with authenticated user
                            return $data;
                        })
                        ->before(function (Tables\Actions\EditAction $action, array $data, $record) {
                            $this->validateReservationAvailability($data, $action, $record->id);
                        }),
                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-o-trash'),
                ])
                ->label('Acciones')
                ->icon('heroicon-o-ellipsis-vertical')
                ->size('sm')
                ->color('gray')
                ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->icon('heroicon-o-trash'),
                ])
                ->label('Acciones en lote'),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Crear primera reserva')
                    ->icon('heroicon-o-plus')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['area_id'] = $this->ownerRecord->id;
                        $data['user_id'] = auth()->id(); // Ensure user_id is set to authenticated user
                        return $data;
                    })
                    ->before(function (Tables\Actions\CreateAction $action, array $data) {
                        $this->validateReservationAvailability($data, $action);
                    }),
            ])
            ->emptyStateHeading('No hay reservas registradas')
            ->emptyStateDescription('Comience creando su primera reserva para esta área común.')
            ->emptyStateIcon('heroicon-o-calendar-days')
            ->defaultSort('fecha_inicio', 'desc');
    }
}