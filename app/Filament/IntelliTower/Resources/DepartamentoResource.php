<?php

namespace App\Filament\IntelliTower\Resources;

use App\Filament\IntelliTower\Resources\DepartamentoResource\Pages;

use App\Filament\IntelliTower\Resources\DepartamentoResource\RelationManagers;
use App\Filament\IntelliTower\Resources\DepartamentoResource\RelationManagers\ConsumosRelationManager;
use App\Models\Departamento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DepartamentoResource extends Resource
{
    protected static ?string $model = Departamento::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationLabel = 'Mis Departamentos';

    protected static ?string $modelLabel = 'Departamento';

    protected static ?string $pluralModelLabel = 'Mis Departamentos';


     public static function getNavigationBadge(): ?string { return static::getEloquentQuery()->count(); }

    // Filtrar solo los departamentos del usuario autenticado
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('numero')
                    ->label('Número')
                    ->required()
                    ->maxLength(255)
                    ->disabled(),

                Forms\Components\TextInput::make('piso')
                    ->label('Piso')
                    ->required()
                    ->maxLength(255)
                    ->disabled(),

                Forms\Components\TextInput::make('bloque')
                    ->label('Bloque')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('numero')
                    ->label('Número')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('piso')
                    ->label('Piso')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('bloque')
                    ->label('Bloque')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Residente')
                    ,
                    

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->color('info')
                    ->icon('heroicon-o-eye'),
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ConsumosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDepartamentos::route('/'),
            'view' => Pages\ViewDepartamento::route('/{record}'),
        ];
    }
}