<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservaResource\Pages;
use App\Filament\Resources\ReservaResource\RelationManagers;
use App\Filament\Forms\Components\HorariosDisponibles;
use App\Models\Reserva;
use App\Models\AreaComun;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReservaResource extends Resource
{
    protected static ?string $model = Reserva::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Condominios';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 10 ? 'info' : 'success';
    }

    protected static function calcularCosto(Set $set, Get $get): void
    {
        $areaId = $get('area_comun_id');
        $inicio = $get('fecha_hora_inicio');
        $fin = $get('fecha_hora_fin');

        if (!$areaId || !$inicio || !$fin) {
            $set('costo_total_calculado', 0);
            return;
        }

        $area = AreaComun::find($areaId);
        if (!$area) {
            $set('costo_total_calculado', 0);
            return;
        }

        $inicioCarbon = \Carbon\Carbon::parse($inicio);
        $finCarbon = \Carbon\Carbon::parse($fin);
        $horas = $finCarbon->diffInHours($inicioCarbon, true);
        $costo = $horas * $area->precio_por_hora;

        $set('costo_total_calculado', number_format($costo, 2, '.', ''));
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información de la Reserva')
                    ->description('Seleccione el área común y el residente para la reserva')
                    ->schema([
                        Forms\Components\Select::make('area_comun_id')
                            ->label('Área Común')
                            ->relationship('areaComun', 'nombre')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                self::calcularCosto($set, $get);
                            })
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nombre} - \${$record->precio_por_hora}/hora")
                            ->columnSpan(1),
                        
                        Forms\Components\Select::make('residente_id')
                            ->label('Residente')
                            ->relationship('residente', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(1),
                        
                        Forms\Components\ViewField::make('horarios_info')
                            ->label('')
                            ->view('filament.components.horarios-disponibles')
                            ->viewData(fn (Get $get) => [
                                'areaId' => $get('area_comun_id'),
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible(),
                
                Forms\Components\Section::make('Horario de la Reserva')
                    ->description('Defina el período de tiempo para la reserva')
                    ->schema([
                        Forms\Components\DateTimePicker::make('fecha_hora_inicio')
                            ->label('Fecha y Hora de Inicio')
                            ->native(false)
                            ->maxDate(now()->addDays(7))
                            ->minDate(now())
                            ->displayFormat('d/m/Y H:i')
                            ->seconds(false)
                            ->minutesStep(15)
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                self::calcularCosto($set, $get);
                            })
                            ->columnSpan(1),
                        
                        Forms\Components\DateTimePicker::make('fecha_hora_fin')
                            ->label('Fecha y Hora de Fin')
                            ->native(false)
                            ->maxDate(now()->addDays(7))
                             ->minDate(now())
                            ->displayFormat('d/m/Y H:i')
                            ->seconds(false)
                            ->minutesStep(15)
                            ->required()
                            ->after('fecha_hora_inicio')
                            ->live()
                            ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                self::calcularCosto($set, $get);
                            })
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsible(),
                
                Forms\Components\Section::make('Detalles de la Reserva')
                    ->schema([
                        Forms\Components\Placeholder::make('costo_info')
                            ->label('Información de Costo')
                            ->content(function (Get $get): string {
                                $areaId = $get('area_comun_id');
                                $inicio = $get('fecha_hora_inicio');
                                $fin = $get('fecha_hora_fin');
                                
                                if (!$areaId || !$inicio || !$fin) {
                                    return 'Seleccione el área común y las fechas para calcular el costo';
                                }
                                
                                $area = AreaComun::find($areaId);
                                if (!$area) {
                                    return 'Área común no encontrada';
                                }
                                
                                $inicioCarbon = \Carbon\Carbon::parse($inicio);
                                $finCarbon = \Carbon\Carbon::parse($fin);
                                $horas = $finCarbon->diffInHours($inicioCarbon, true);
                                $costo = $horas * $area->precio_por_hora;
                                
                                return "Precio por hora: \${$area->precio_por_hora} × {$horas} horas = \$" . number_format($costo, 2);
                            })
                            ->columnSpan(1),
                        
                        Forms\Components\TextInput::make('costo_total_calculado')
                            ->label('Costo Total')
                            ->numeric()
                            ->prefix('$')
                            ->readOnly()
                            ->dehydrated()
                            ->columnSpan(1),
                        
                        Forms\Components\Select::make('estado_reserva')
                            ->label('Estado')
                            ->options([
                                'pendiente' => 'Pendiente',
                                'confirmada' => 'Confirmada',
                                'cancelada' => 'Cancelada',
                                'completada' => 'Completada',
                            ])
                            ->default('pendiente')
                            
                            ->required()
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('areaComun.nombre')
                    ->label('Área Común')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('residente.name')
                    ->label('Residente')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('fecha_hora_inicio')
                    ->label('Inicio')
                    ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->locale('es')->isoFormat('dddd D [de] MMMM [a las] HH:mm') : '-')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('fecha_hora_fin')
                    ->label('Fin')
                    ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->locale('es')->isoFormat('dddd D [de] MMMM [a las] HH:mm') : '-')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('costo_total_calculado')
                    ->label('Costo')
                    ->money('USD')
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('estado_reserva')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'pendiente',
                        'success' => 'confirmada',
                        'danger' => 'cancelada',
                        'secondary' => 'completada',
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'pendiente',
                        'heroicon-o-check-circle' => 'confirmada',
                        'heroicon-o-x-circle' => 'cancelada',
                        'heroicon-o-check-badge' => 'completada',
                    ])
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado_reserva')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'confirmada' => 'Confirmada',
                        'cancelada' => 'Cancelada',
                        'completada' => 'Completada',
                    ]),
                
                Tables\Filters\SelectFilter::make('area_comun_id')
                    ->label('Área Común')
                    ->relationship('areaComun', 'nombre')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->color('info')
                        ->icon('heroicon-o-eye')
                        ->label('Ver Detalles'),
                    Tables\Actions\EditAction::make()
                        ->color('warning')
                        ->icon('heroicon-o-pencil-square')
                        ->label('Editar'),
                    Tables\Actions\DeleteAction::make()
                        ->color('danger')
                        ->icon('heroicon-o-trash')
                        ->label('Eliminar')
                        ->requiresConfirmation()
                        ->modalHeading('Eliminar Reserva')
                        ->modalDescription('¿Está seguro de que desea eliminar esta reserva? Esta acción no se puede deshacer.')
                        ->modalSubmitActionLabel('Sí, eliminar'),
            ])
                ->label('Acciones')
                ->icon('heroicon-m-ellipsis-vertical')
                ->size('sm')
                ->color('info')
                ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('fecha_hora_inicio', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReservas::route('/'),
            'calendario' => Pages\CalendarioReservas::route('/calendario'),
            'create' => Pages\CreateReserva::route('/create'),
            'view' => Pages\ViewReserva::route('/{record}'),
            'edit' => Pages\EditReserva::route('/{record}/edit'),
            
        ];
    }
}
