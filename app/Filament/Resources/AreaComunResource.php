<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AreaComunResource\Pages;
use App\Filament\Resources\AreaComunResource\RelationManagers;
use App\Filament\Resources\AreaComunResource\RelationManagers\ReservasRelationManager;
use App\Models\AreaComun;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AreaComunResource extends Resource
{
    protected static ?string $model = AreaComun::class;

    protected static ?string $navigationGroup = 'Condominios';

    protected static ?string $modelLabel = 'Área Común';

    protected static ?string $pluralModelLabel = 'Áreas Comunes';

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?int $navigationSort = 3;

    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Área Común')
                    ->description('Complete los datos básicos del área común')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('nombre')
                                    ->label('Nombre del Área')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Ej: Salón de Eventos, Gimnasio, Piscina')
                                    ->prefixIcon('heroicon-o-tag'),
                                
                                Forms\Components\TextInput::make('capacidad')
                                    ->label('Capacidad Máxima')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(1000)
                                    ->placeholder('Número de personas')
                                    ->prefixIcon('heroicon-o-users')
                                    ->suffix('personas'),
                            ]),
                        
                        Forms\Components\Textarea::make('descripcion')
                            ->label('Descripción')
                            ->required()
                            ->rows(3)
                            ->placeholder('Describe las características y servicios del área común...')
                            ->columnSpanFull(),
                    ]),
                
                Forms\Components\Section::make('Estado y Disponibilidad')
                    ->description('Configure la disponibilidad del área')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Forms\Components\Toggle::make('disponibilidad')
                            ->label('Área Disponible')
                            ->helperText('Marque si el área está disponible para reservas')
                            ->onIcon('heroicon-o-check-circle')
                            ->offIcon('heroicon-o-x-circle')
                            ->onColor('success')
                            ->offColor('danger')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre del Área')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->icon('heroicon-o-building-office-2')
                    ->color('primary'),
                
                Tables\Columns\TextColumn::make('descripcion')
                    ->label('Descripción')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    })
                    ->wrap(),
                
                Tables\Columns\TextColumn::make('capacidad')
                    ->label('Capacidad')
                    ->numeric()
                    ->sortable()
                    ->suffix(' personas')
                    ->icon('heroicon-o-users')
                    ->color('info')
                    ->alignCenter(),
                
                Tables\Columns\IconColumn::make('disponibilidad')
                    ->label('Estado')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->alignCenter(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon('heroicon-o-calendar'),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon('heroicon-o-pencil'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('disponibilidad')
                    ->label('Disponibilidad')
                    ->placeholder('Todas las áreas')
                    ->trueLabel('Solo disponibles')
                    ->falseLabel('Solo no disponibles'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->color('info')
                        ->icon('heroicon-o-eye'),
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
                ])
                ->label('Acciones en lote'),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus')
                    ->label('Crear primera área común'),
            ])
            ->emptyStateHeading('No hay áreas comunes registradas')
            ->emptyStateDescription('Comience creando su primera área común para el edificio.')
            ->emptyStateIcon('heroicon-o-building-office-2')
            ->striped()
            ->defaultSort('nombre', 'asc');
    }
    public static function getRelations(): array
    {
        return [
            //
            RelationManagers\ReservasRelationManager::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [
            AreaComunResource\Widgets\ReservaStatsWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAreaComuns::route('/'),
            'create' => Pages\CreateAreaComun::route('/create'),
            'view' => Pages\ViewAreaComun::route('/{record}'),
            'edit' => Pages\EditAreaComun::route('/{record}/edit'),
        ];
    }
}
