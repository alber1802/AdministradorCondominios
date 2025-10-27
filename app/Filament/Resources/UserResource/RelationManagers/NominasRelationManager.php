<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NominasRelationManager extends RelationManager
{
    protected static string $relationship = 'nominas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('mes')
                    ->options([
                        'enero' => 'Enero',
                        'febrero' => 'Febrero',
                        'marzo' => 'Marzo',
                        'abril' => 'Abril',
                        'mayo' => 'Mayo',
                        'junio' => 'Junio',
                        'julio' => 'Julio',
                        'agosto' => 'Agosto',
                        'septiembre' => 'Septiembre',
                        'octubre' => 'Octubre',
                        'noviembre' => 'Noviembre',
                        'diciembre' => 'Diciembre',
                    ])
                    ->required(),
                
                Forms\Components\DatePicker::make('fecha_pago')
                    ->date()
                    ->format('d/m/Y')
                    ->disabled(fn (Forms\Get $get): bool => $get('estado') === 'pendiente')
                    ->dehydrated(fn (Forms\Get $get): bool => $get('estado') !== 'pendiente')
                    ->required(fn (Forms\Get $get): bool => $get('estado') === 'pagado'),
                Forms\Components\TextInput::make('monto')
                    ->required()
                    ->numeric()
                    ->step(0.01)
                    ->inputMode('decimal'),
                Forms\Components\Select::make('estado')
                    ->options([
                        'Pendiente' => 'Pendiente',
                        'Pagado' => 'Pagado',
                    ])
                    ->required()
                    ->native(false)
                    ->default('pendiente')
                    ->live()
                    ->afterStateUpdated(function (Forms\Set $set, $state) {
                        if ($state === 'pendiente') {
                            $set('fecha_pago', null);
                        }
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
         // Define qué campo se usa como título/nombre para identificar cada registro en la relación
            ->recordTitleAttribute('mes')
            ->columns([
                Tables\Columns\TextColumn::make('mes'),
                Tables\Columns\TextColumn::make('fecha_pago'),
                Tables\Columns\TextColumn::make('monto'),
                Tables\Columns\TextColumn::make('estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pendiente' => 'warning',
                        'Pagado' => 'success',
                    }	)
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
