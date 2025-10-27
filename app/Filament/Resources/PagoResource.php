<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PagoResource\Pages;
use App\Filament\Resources\PagoResource\RelationManagers;
use App\Models\Pago;
use App\Models\Factura;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PagoResource extends Resource

{
    protected static ?string $model = Pago::class;

    protected static ?string $navigationIcon =  'heroicon-o-currency-dollar';

    protected static ?string $modelLabel = 'Pagos Realizados';

    protected static ?string $navigationGroup = 'Finanzas';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('factura_id')
                    ->relationship('factura', 'id')
                    ->native(false)
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('tipo_pago')
                    ->label('Tipo de Pago')
                    ->required()
                    ->options([
                        'tigo_money' => 'Tigo Money',
                        'tarjeta' => 'Tarjeta',
                        'cripto' => 'Criptomoneda',
                        'efectivo' => 'Efectivo',
                    ])
                    ->native(false)
                    ->live()
                    ->prefixIcon('heroicon-o-credit-card'),
                Forms\Components\TextInput::make('monto_pagado')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('fecha_pago')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('factura.id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo_pago')
                    ->searchable(),
                Tables\Columns\TextColumn::make('monto_pagado')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_pago')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
             PagoResource\Widgets\PagoStatsWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPagos::route('/'),
            'create' => Pages\CreatePago::route('/create'),
            'edit' => Pages\EditPago::route('/{record}/edit'),
        ];
    }
}
