<?php

namespace App\Filament\IntelliTower\Resources\AreaComunResource\RelationManagers;

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
                Forms\Components\Section::make('Horario de Disponibilidad')
                    ->description('Consulte los horarios disponibles para reservar')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Forms\Components\Placeholder::make('dia_semana')
                            ->label('Día de la Semana')
                            ->content(fn ($record): string => match($record?->dia_semana) {
                                0 => 'Domingo',
                                1 => 'Lunes',
                                2 => 'Martes',
                                3 => 'Miércoles',
                                4 => 'Jueves',
                                5 => 'Viernes',
                                6 => 'Sábado',
                                default => 'N/A',
                            }),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Placeholder::make('hora_apertura')
                                    ->label('Hora de Apertura')
                                    ->content(fn ($record): string => $record?->hora_apertura ?? 'N/A'),
                                
                                Forms\Components\Placeholder::make('hora_cierre')
                                    ->label('Hora de Cierre')
                                    ->content(fn ($record): string => $record?->hora_cierre ?? 'N/A'),
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
                // Sin acciones de creación para residentes
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->color('info')
                    ->icon('heroicon-o-eye')
                    ->label('Ver'),
            ])
            ->bulkActions([
                // Sin acciones masivas para residentes
            ])
            ->emptyStateHeading('No hay horarios configurados')
            ->emptyStateDescription('El administrador aún no ha configurado los horarios para esta área.')
            ->emptyStateIcon('heroicon-o-clock')
            ->defaultSort('dia_semana', 'asc');
    }
}
