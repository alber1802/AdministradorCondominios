<?php

namespace App\Filament\Resources\DepartamentoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConsumosRelationManager extends RelationManager
{
    protected static string $relationship = 'consumos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(4)
                ->schema([
                    Forms\Components\Select::make('tipo')
                        ->label('Tipo de Servicio')
                        ->options([
                            'agua' => 'Agua',
                            'luz' => 'Luz',
                            'gas' => 'Gas',
                        ])
                        ->required()
                        ->native(false)
                        ->reactive(),
                    Forms\Components\TextInput::make('lectura')
                        ->label('Consumo')
                        ->numeric()
                        ->minValue(0)
                        ->required()
                        ->suffix(function (callable $get) {
                            $tipo = $get('tipo');
                            return match($tipo) {
                                'agua' => 'm³',
                                'luz' => 'kWh',
                                'gas' => 'm³',
                                default => ''
                            };
                        }),
                    Forms\Components\Select::make('unidad')
                        ->label('Unidad')
                        ->disabled()
                        ->options(function (callable $get) {
                            $tipo = $get('tipo');
                            return match($tipo) {
                                'agua' => ['m³' => 'm³'],
                                'luz' => ['kWh' => 'kWh'],
                                'gas' => ['m³' => 'm³'],
                                default => []
                            };
                        })
                        ->required()
                        ->native(false)
                        ->disabled(fn (callable $get) => !$get('tipo')),
                    Forms\Components\TextInput::make('costo_unitario')
                        ->label('Costo unitario')
                        ->numeric()
                        ->prefix('$')
                        ->required(),
                ]),
            Forms\Components\Grid::make(2)
                ->schema([
                    Forms\Components\DatePicker::make('fecha')
                        ->label('Fecha de Lectura')
                        ->required()
                        ->default(now()),
                    Forms\Components\Toggle::make('alerta')
                        ->label('Alerta')
                        ->helperText('Activar si hay observaciones importantes'),
                ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tipo')
            ->columns([
                Tables\Columns\BadgeColumn::make('tipo')
                    ->label('Tipo de Servicio')
                    ->colors([
                        'primary' => 'agua',
                        'warning' => 'luz',
                        'success' => 'gas',
                    ])
                    ->icons([
                        'heroicon-o-beaker' => 'agua',
                        'heroicon-o-bolt' => 'luz',
                        'heroicon-o-fire' => 'gas',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'agua' => 'Agua',
                        'luz' => 'Luz',
                        'gas' => 'Gas',
                        default => $state,
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha')
                    ->label('Fecha de Lectura')
                    ->date('F')
                    ->sortable()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('lectura')
                    ->label('Consumo')
                    ->numeric(decimalPlaces: 2)
                    ->suffix(function ($record) {
                        return match($record->tipo) {
                            'agua' => ' m³',
                            'luz' => ' kWh',
                            'gas' => ' m³',
                            default => ''
                        };
                    })
                    ->color('info')
                    ->weight('bold')
                    ->sortable(),
                Tables\Columns\TextColumn::make('costo_unitario')
                    ->label('Costo Unitario')
                    ->money('USD')
                    ->color('success')
                    ->weight('semibold')
                    ->sortable(),
                Tables\Columns\TextColumn::make('costo_total')
                    ->label('Costo Total')
                    ->getStateUsing(fn ($record) => $record->lectura * $record->costo_unitario)
                    ->money('USD')
                    ->color('danger')
                    ->weight('bold')
                    ->sortable(),
                Tables\Columns\IconColumn::make('alerta')
                    ->label('Alerta')
                    ->boolean()
                    ->trueIcon('heroicon-o-exclamation-triangle')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('warning')
                    ->falseColor('success'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo')
                    ->label('Tipo de Servicio')
                    ->options([
                        'agua' => 'Agua',
                        'luz' => 'Luz',
                        'gas' => 'Gas',
                    ]),
                Tables\Filters\Filter::make('alerta')
                    ->label('Con Alerta')
                    ->query(fn (Builder $query): Builder => $query->where('alerta', true)),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus-circle')
                    ->color('success'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning'),
                Tables\Actions\DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->color('danger'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->striped()
            ->defaultSort('fecha', 'desc');
    }
}
