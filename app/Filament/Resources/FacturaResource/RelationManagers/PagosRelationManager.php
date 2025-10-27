<?php

namespace App\Filament\Resources\FacturaResource\RelationManagers;

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
        return null;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tipo_pago')
            ->columns([
                Tables\Columns\TextColumn::make('tipo_pago'),

                Tables\Columns\TextColumn::make('monto_pagado')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_pago')
                    ->date()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            // ->headerActions([
            //     Tables\Actions\CreateAction::make(),
            // ])
            ->actions([
                Tables\Actions\DeleteAction::make()
                ->color('warning')
                ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
