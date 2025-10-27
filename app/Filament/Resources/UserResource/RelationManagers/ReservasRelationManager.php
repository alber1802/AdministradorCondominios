<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

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

class ReservasRelationManager extends RelationManager
{
    protected static string $relationship = 'reservas';

    protected static ?string $recordTitleAttribute = 'fecha_inicio';

    protected static ?string $title = 'Reservas del Área';

    protected static ?string $modelLabel = 'Reserva';

    protected static ?string $pluralModelLabel = 'Reservas';

    protected function validateReservationAvailability(array $data, $action, ?int $excludeReservationId = null): void
    {
        //dd($data);
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
                                Forms\Components\Select::make('user_id')
                                    ->label('Usuario')
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->placeholder('Seleccione el usuario')
                                    ->prefixIcon('heroicon-o-user'),

                                Forms\Components\Select::make('estado')
                                    ->label('Estado de la Reserva')
                                    ->options([
                                        'pendiente' => 'Pendiente',
                                        'confirmada' => 'Confirmada',
                                        'cancelada' => 'Cancelada',
                                        'completada' => 'Completada',
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
                                Forms\Components\DateTimePicker::make('fecha_inicio')
                                    ->label('Fecha y Hora de Inicio')
                                    ->required()
                                    ->minDate(now()->startOfDay())
                                    ->seconds(false)
                                    //->minuteStep(15)
                                    ->prefixIcon('heroicon-o-play')
                                    ->live()
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        if ($state) {
                                            // Auto-set end time to 2 hours later if not set
                                            $endTime = \Carbon\Carbon::parse($state)->addHours(2);
                                            $set('fecha_fin', $endTime->format('Y-m-d H:i'));
                                        }
                                    }),

                                Forms\Components\DateTimePicker::make('fecha_fin')
                                    ->label('Fecha y Hora de Fin')
                                    ->required()
                                    ->minDate(now()->startOfDay())
                                    ->seconds(false)
                                    //->minuteStep(15)
                                    ->prefixIcon('heroicon-o-stop')
                                    ->after('fecha_inicio'),
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
                                $duration = $start->diffInHours($end);
                                
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
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuario')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->icon('heroicon-o-user')
                    ->color('primary'),

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
                        $horas = $inicio->diffInHours($fin);
                        $minutos = $inicio->diffInMinutes($fin) % 60;
                        
                        if ($horas > 0 && $minutos > 0) {
                            return "{$horas}h {$minutos}m";
                        } elseif ($horas > 0) {
                            return "{$horas}h";
                        } else {
                            return "{$minutos}m";
                        }
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
            ->emptyStateHeading('No hay reservas registradas')
            ->emptyStateDescription('Comience creando la primera reserva para esta área común.')
            ->emptyStateIcon('heroicon-o-calendar-days')
            ->defaultSort('fecha_inicio', 'desc');
    }
}
