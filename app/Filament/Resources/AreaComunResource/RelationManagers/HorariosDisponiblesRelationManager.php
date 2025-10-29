<?php

namespace App\Filament\Resources\AreaComunResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HorariosDisponiblesRelationManager extends RelationManager
{
    protected static string $relationship = 'horariosDisponibles';

    protected static ?string $title = 'Horarios Disponibles';

    protected static ?string $recordTitleAttribute = 'dia_semana';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Configuración de Horario')
                    ->description('Defina el horario de disponibilidad para este día')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Forms\Components\Select::make('dia_semana')
                            ->label('Día de la Semana')
                            ->required()
                            ->options([
                                1 => 'Lunes',
                                2 => 'Martes',
                                3 => 'Miércoles',
                                4 => 'Jueves',
                                5 => 'Viernes',
                                6 => 'Sábado',
                                0 => 'Domingo',
                            ])
                            ->native(false)
                            ->prefixIcon('heroicon-o-calendar')
                            ->columnSpanFull(),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TimePicker::make('hora_apertura')
                                    ->label('Hora de Apertura')
                                    ->required()
                                    ->seconds(false)
                                    ->prefixIcon('heroicon-o-arrow-right-circle')
                                    ->native(false)
                                    ->displayFormat('H:i')
                                    ->helperText('Hora en que abre el área'),
                                
                                Forms\Components\TimePicker::make('hora_cierre')
                                    ->label('Hora de Cierre')
                                    ->required()
                                    ->seconds(false)
                                    ->prefixIcon('heroicon-o-arrow-left-circle')
                                    ->native(false)
                                    ->displayFormat('H:i')
                                    ->helperText('Hora en que cierra el área')
                                    ->after('hora_apertura'),
                            ]),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('dia_semana')
            ->columns([
                Tables\Columns\TextColumn::make('dia_semana')
                    ->label('Día')
                    ->formatStateUsing(fn (int $state): string => match($state) {
                        0 => 'Domingo',
                        1 => 'Lunes',
                        2 => 'Martes',
                        3 => 'Miércoles',
                        4 => 'Jueves',
                        5 => 'Viernes',
                        6 => 'Sábado',
                        default => 'Desconocido',
                    })
                    ->badge()
                    ->color(fn (int $state): string => match($state) {
                        0, 6 => 'warning',
                        default => 'primary',
                    })
                    ->icon('heroicon-o-calendar')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('hora_apertura')
                    ->label('Apertura')
                    ->time('H:i')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->color('success')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('hora_cierre')
                    ->label('Cierre')
                    ->time('H:i')
                    ->icon('heroicon-o-arrow-left-circle')
                    ->color('danger')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('dia_semana')
                    ->label('Día de la Semana')
                    ->options([
                        1 => 'Lunes',
                        2 => 'Martes',
                        3 => 'Miércoles',
                        4 => 'Jueves',
                        5 => 'Viernes',
                        6 => 'Sábado',
                        0 => 'Domingo',
                    ])
                    ->multiple(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus-circle')
                    ->label('Agregar Horario')
                    ->color('success'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->color('warning')
                        ->icon('heroicon-o-pencil-square'),
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
                ]),
            ])
            ->emptyStateHeading('No hay horarios configurados')
            ->emptyStateDescription('Agregue los horarios de disponibilidad para esta área común.')
            ->emptyStateIcon('heroicon-o-clock')
            ->defaultSort('dia_semana', 'asc');
    }
}
