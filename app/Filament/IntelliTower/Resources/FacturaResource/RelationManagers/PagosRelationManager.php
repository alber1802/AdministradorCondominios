<?php

namespace App\Filament\IntelliTower\Resources\FacturaResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PagosRelationManager extends RelationManager
{
    protected static string $relationship = 'pagos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Read-only form for viewing payment details
                Forms\Components\TextInput::make('tipo_pago')
                    ->label('Tipo de Pago')
                    ->disabled(),
                    
                Forms\Components\TextInput::make('monto_pagado')
                    ->label('Monto Pagado')
                    ->numeric()
                    ->prefix('$')
                    ->disabled(),
                    
                Forms\Components\DatePicker::make('fecha_pago')
                    ->label('Fecha de Pago')
                    ->disabled(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tipo_pago')
            ->columns([
                Tables\Columns\TextColumn::make('tipo_pago')
                    ->label('Tipo de Pago')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'tigo_money' => 'info',
                        'tarjeta' => 'warning',
                        'cripto' => 'success',
                        'efectivo' => 'primary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => $state ? ucfirst(str_replace('_', ' ', $state)) : ''),

                Tables\Columns\TextColumn::make('monto_pagado')
                    ->label('Monto Pagado')
                    ->money('USD')
                    ->sortable()
                    ->alignEnd()
                    ->weight('bold')
                    ->color('success'),
                    
                Tables\Columns\TextColumn::make('fecha_pago')
                    ->label('Fecha de Pago')
                    ->date('d/m/Y')
                    ->sortable()
                    ->icon('heroicon-o-calendar'),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo_pago')
                    ->label('Tipo de Pago')
                    ->options([
                        'tigo_money' => 'Tigo Money',
                        'tarjeta' => 'Tarjeta',
                        'cripto' => 'Criptomoneda',
                        'efectivo' => 'Efectivo',
                    ])
                    ->native(false),
            ])
            ->headerActions([
                // No create action for user panel - read only
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->color('info')
                    ->icon('heroicon-o-eye'),
                // No edit or delete actions for user panel - read only
            ])
            ->bulkActions([
                // No bulk actions for user panel - read only
            ])
            ->defaultSort('fecha_pago', 'desc');
    }
}