<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AreaComunResource\Pages;
use App\Filament\Resources\AreaComunResource\RelationManagers;
use App\Filament\Resources\AreaComunResource\RelationManagers\HorariosDisponiblesRelationManager;
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
                                    ->prefixIcon('heroicon-o-tag')
                                    ->columnSpan(1),
                                
                                Forms\Components\TextInput::make('capacidad')
                                    ->label('Capacidad')
                                    ->required()
                                    ->maxLength(100)
                                    ->placeholder('Ej: 50 personas, 20 vehículos')
                                    ->prefixIcon('heroicon-o-users')
                                    ->columnSpan(1),
                            ]),
                        
                        Forms\Components\Textarea::make('descripcion')
                            ->label('Descripción')
                            ->required()
                            ->rows(4)
                            ->placeholder('Describe las características, servicios y normas del área común...')
                            ->columnSpanFull(),
                    ]),
                
                Forms\Components\Section::make('Estado del Área')
                    ->description('Configure el estado actual del área')
                    ->icon('heroicon-o-signal')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('estado')
                                    ->label('Estado')
                                    ->required()
                                    ->options([
                                        'Disponible' => 'Disponible',
                                        'Mantenimiento' => 'Mantenimiento',
                                        'Fuera de Servicio' => 'Fuera de Servicio',
                                        'Reservado' => 'Reservado',
                                    ])
                            ->default('Disponible')
                            ->native(false)
                            ->prefixIcon('heroicon-o-signal')
                                    ->helperText('Seleccione el estado actual del área común')
                                    ->columnSpan(1),
                                
                                Forms\Components\TextInput::make('precio_por_hora')
                                    ->label('Precio por Hora')
                                    ->required()
                                    ->numeric()
                                    ->step(0.01)
                                    ->minValue(0)
                                    ->prefix('$')
                                    ->placeholder('0.00')
                                    ->helperText('Ingrese el precio por hora de uso del área común')
                                    ->columnSpan(1),
                            ]),
                        
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
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-users')
                    ->color('info')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('precio_por_hora')
                    ->label('Precio por Hora')
                    ->money('USD')
                    ->sortable()
                    ->searchable()
                    ->icon('heroicon-o-currency-dollar')
                    ->color('success')
                    ->alignCenter(),
                
                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->colors([
                        'success' => 'Disponible',
                        'warning' => 'Mantenimiento',
                        'danger' => 'Fuera de Servicio',
                        'primary' => 'Reservado',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'Disponible',
                        'heroicon-o-wrench-screwdriver' => 'Mantenimiento',
                        'heroicon-o-x-circle' => 'Fuera de Servicio',
                        'heroicon-o-clock' => 'Reservado',
                    ])
                    ->sortable()
                    ->searchable()
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
                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        'Disponible' => 'Disponible',
                        'Mantenimiento' => 'Mantenimiento',
                        'Fuera de Servicio' => 'Fuera de Servicio',
                        'Reservado' => 'Reservado',
                    ])
                    ->multiple()
                    ->placeholder('Todos los estados'),
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
            RelationManagers\HorariosDisponiblesRelationManager::class,
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
