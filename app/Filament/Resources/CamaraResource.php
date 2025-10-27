<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CamaraResource\Pages;
use App\Filament\Resources\CamaraResource\RelationManagers;
use App\Models\Camara;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CamaraResource extends Resource
{
    protected static ?string $model = Camara::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationGroup = 'Configuracion';
    protected static ?string $navigationLabel = 'Cámaras';
    protected static ?string $modelLabel = 'Cámara';
    protected static ?string $pluralModelLabel = 'Cámaras';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información General')
                    ->description('Datos básicos de la cámara de seguridad')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Forms\Components\TextInput::make('nombre')
                            ->label('Nombre de la Cámara')
                            ->placeholder('Ej: Cámara Lobby Principal')
                            ->required()
                            ->maxLength(100)
                            ->columnSpan(2),
                        
                        Forms\Components\Select::make('estado')
                            ->label('Estado')
                            ->options([
                                'activa' => 'Activa',
                                'inactiva' => 'Inactiva',
                                'mantenimiento' => 'En Mantenimiento',
                            ])
                            ->default('activa')
                            ->required()
                            ->native(false)
                            ->columnSpan(1),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Especificaciones Técnicas')
                    ->description('Detalles del hardware de la cámara')
                    ->icon('heroicon-o-cpu-chip')
                    ->schema([
                        Forms\Components\TextInput::make('marca')
                            ->label('Marca')
                            ->placeholder('Ej: Hikvision, Dahua')
                            ->maxLength(50),
                        
                        Forms\Components\TextInput::make('modelo')
                            ->label('Modelo')
                            ->placeholder('Ej: DS-2CD2143G0-I')
                            ->maxLength(50),
                        
                        Forms\Components\TextInput::make('numero_serie')
                            ->label('Número de Serie')
                            ->placeholder('Ej: ABC123456789')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(100),
                        
                        Forms\Components\Select::make('tipo')
                            ->label('Tipo de Cámara')
                            ->options([
                                'IP' => 'IP',
                                'Domo' => 'Domo',
                                'Bala' => 'Bala (Bullet)',
                                'PTZ' => 'PTZ (Pan-Tilt-Zoom)',
                                'Fisheye' => 'Ojo de Pez (Fisheye)',
                                'Turret' => 'Turret',
                            ])
                            ->native(false)
                            ->placeholder('Seleccione el tipo'),
                        
                        Forms\Components\Select::make('resolucion')
                            ->label('Resolución')
                            ->options([
                                '720p' => '720p (HD)',
                                '1080p' => '1080p (Full HD)',
                                '2K' => '2K (1440p)',
                                '4K' => '4K (Ultra HD)',
                                '5MP' => '5 Megapíxeles',
                                '8MP' => '8 Megapíxeles',
                            ])
                            ->native(false)
                            ->placeholder('Seleccione la resolución'),
                        
                        Forms\Components\DatePicker::make('fecha_instalacion')
                            ->label('Fecha de Instalación')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->maxDate(now()),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Ubicación y Red')
                    ->description('Información de ubicación física y configuración de red')
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        Forms\Components\TextInput::make('ubicacion')
                            ->label('Ubicación')
                            ->placeholder('Ej: Pasillo Piso 2, Ala Norte')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),
                        
                        Forms\Components\TextInput::make('direccion_ip')
                            ->label('Dirección IP')
                            ->placeholder('Ej: 192.168.1.100')
                            ->unique(ignoreRecord: true)
                            ->maxLength(45)
                            ->suffixIcon('heroicon-o-globe-alt')
                            ->columnSpan(1),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Observaciones')
                    ->description('Notas adicionales sobre la cámara')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Forms\Components\Textarea::make('observaciones')
                            ->label('Observaciones')
                            ->placeholder('Ingrese cualquier información adicional relevante...')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-video-camera')
                    ->weight('bold'),
                
                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->colors([
                        'success' => 'activa',
                        'danger' => 'inactiva',
                        'warning' => 'mantenimiento',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'activa',
                        'heroicon-o-x-circle' => 'inactiva',
                        'heroicon-o-wrench' => 'mantenimiento',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'activa' => 'Activa',
                        'inactiva' => 'Inactiva',
                        'mantenimiento' => 'Mantenimiento',
                        default => $state,
                    }),
                
                Tables\Columns\TextColumn::make('ubicacion')
                    ->label('Ubicación')
                    ->searchable()
                    ->icon('heroicon-o-map-pin')
                    ->limit(30),
                
                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge()
                    ->color('info')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('resolucion')
                    ->label('Resolución')
                    ->badge()
                    ->color('gray')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('marca')
                    ->label('Marca')
                    ->searchable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('modelo')
                    ->label('Modelo')
                    ->searchable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('numero_serie')
                    ->label('N° Serie')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Número de serie copiado')
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('direccion_ip')
                    ->label('IP')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('IP copiada')
                    ->icon('heroicon-o-globe-alt')
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('fecha_instalacion')
                    ->label('Instalación')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        'activa' => 'Activa',
                        'inactiva' => 'Inactiva',
                        'mantenimiento' => 'Mantenimiento',
                    ])
                    ->native(false),
                
                Tables\Filters\SelectFilter::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'IP' => 'IP',
                        'Domo' => 'Domo',
                        'Bala' => 'Bala',
                        'PTZ' => 'PTZ',
                        'Fisheye' => 'Fisheye',
                        'Turret' => 'Turret',
                    ])
                    ->native(false),
                
                Tables\Filters\Filter::make('fecha_instalacion')
                    ->form([
                        Forms\Components\DatePicker::make('instalada_desde')
                            ->label('Instalada desde')
                            ->native(false),
                        Forms\Components\DatePicker::make('instalada_hasta')
                            ->label('Instalada hasta')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['instalada_desde'],
                                fn (Builder $query, $date): Builder => $query->whereDate('fecha_instalacion', '>=', $date),
                            )
                            ->when(
                                $data['instalada_hasta'],
                                fn (Builder $query, $date): Builder => $query->whereDate('fecha_instalacion', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->color('warning'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped();
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCamaras::route('/'),
            'create' => Pages\CreateCamara::route('/create'),
            'edit' => Pages\EditCamara::route('/{record}/edit'),
        ];
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 10 ? 'success' : 'warning';
    }
}
