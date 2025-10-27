<?php

namespace App\Filament\IntelliTower\Resources;

use App\Filament\IntelliTower\Resources\MascotaResource\Pages;
use App\Filament\IntelliTower\Resources\MascotaResource\RelationManagers;
use App\Models\Mascota;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MascotaResource extends Resource
{
    protected static ?string $model = Mascota::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?string $navigationLabel = 'Mis Mascotas';
    protected static ?string $modelLabel = 'Mascota';
    protected static ?string $pluralModelLabel = 'Mis Mascotas';
    protected static ?string $navigationGroup = 'Mi Hogar';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('🐾 Información de tu Mascota')
                    ->description('Registra los datos básicos de tu compañero')
                    ->schema([
                        Forms\Components\TextInput::make('nombre')
                            ->label('Nombre')
                            ->placeholder('Ej: Max, Luna, Firulais')
                            ->required()
                            ->maxLength(100)
                            ->prefixIcon('heroicon-o-identification')
                            ->columnSpan(2),
                        
                        Forms\Components\Select::make('especie')
                            ->label('Especie')
                            ->options([
                                'Perro' => '🐕 Perro',
                                'Gato' => '🐈 Gato',
                                'Ave' => '🦜 Ave',
                                'Conejo' => '🐰 Conejo',
                                'Hamster' => '🐹 Hamster',
                                'Pez' => '🐠 Pez',
                                'Tortuga' => '🐢 Tortuga',
                                'Otro' => '🦎 Otro',
                            ])
                            ->required()
                            ->native(false)
                            ->searchable()
                            ->columnSpan(1),
                        
                        Forms\Components\TextInput::make('raza')
                            ->label('Raza')
                            ->placeholder('Ej: Labrador, Persa, Canario')
                            ->maxLength(100)
                            ->prefixIcon('heroicon-o-sparkles')
                            ->columnSpan(1),
                        
                        Forms\Components\Select::make('sexo')
                            ->label('Sexo')
                            ->options([
                                'Macho' => '♂ Macho',
                                'Hembra' => '♀ Hembra',
                            ])
                            ->native(false)
                            ->columnSpan(1),
                        
                        Forms\Components\Select::make('color')
                            ->label('Color Principal')
                            ->options([
                                'Negro' => '⚫ Negro',
                                'Blanco' => '⚪ Blanco',
                                'Marrón' => '🟤 Marrón',
                                'Gris' => '⚫ Gris',
                                'Dorado' => '🟡 Dorado',
                                'Atigrado' => '🐯 Atigrado',
                                'Manchado' => '🐄 Manchado',
                                'Tricolor' => '🎨 Tricolor',
                                'Naranja' => '🟠 Naranja',
                                'Crema' => '🟨 Crema',
                                'Otro' => '🌈 Otro',
                            ])
                            ->native(false)
                            ->searchable()
                            ->columnSpan(1),
                    ])
                    ->columns(3)
                    ->collapsible(),

                Forms\Components\Section::make('📊 Datos Físicos y Salud')
                    ->description('Información sobre edad, peso y estado de salud')
                    ->schema([
                        Forms\Components\DatePicker::make('fecha_nacimiento')
                            ->label('Fecha de Nacimiento')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->maxDate(now())
                            ->prefixIcon('heroicon-o-cake')
                            ->helperText('Nos ayuda a calcular su edad')
                            ->columnSpan(1),
                        
                        Forms\Components\TextInput::make('peso_kg')
                            ->label('Peso')
                            ->numeric()
                            ->step(0.01)
                            ->minValue(0)
                            ->maxValue(999.99)
                            ->suffix('kg')
                            ->placeholder('Ej: 15.5')
                            ->prefixIcon('heroicon-o-scale')
                            ->columnSpan(1),
                        
                        Forms\Components\Select::make('estado')
                            ->label('Estado de Salud')
                            ->options([
                                'Saludable' => '✅ Saludable',
                                'En tratamiento' => '💊 En tratamiento',
                                'Vacunación pendiente' => '💉 Vacunación pendiente',
                                'Requiere atención' => '⚠️ Requiere atención',
                            ])
                            ->native(false)
                            ->default('Saludable')
                            ->prefixIcon('heroicon-o-heart')
                            ->columnSpan(1),
                    ])
                    ->columns(3)
                    ->collapsible(),

                Forms\Components\Section::make('📝 Notas Adicionales')
                    ->description('Alergias, comportamiento, cuidados especiales, etc.')
                    ->schema([
                        Forms\Components\Textarea::make('observaciones')
                            ->label('Observaciones')
                            ->placeholder('Ej: Alérgico al pollo, le gusta jugar con pelotas, necesita medicación diaria...')
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
                Tables\Columns\ImageColumn::make('especie')
                    ->label('')
                    ->getStateUsing(fn ($record) => match($record->especie) {
                        'Perro' => '🐕',
                        'Gato' => '🐈',
                        'Ave' => '🦜',
                        'Conejo' => '🐰',
                        'Hamster' => '🐹',
                        'Pez' => '🐠',
                        'Tortuga' => '🐢',
                        default => '🐾',
                    })
                    ->size(40)
                    ->circular(),
                
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->size('lg')
                    ->description(fn ($record) => $record->raza ?? 'Sin raza especificada'),
                
                Tables\Columns\BadgeColumn::make('especie')
                    ->label('Especie')
                    ->colors([
                        'primary' => 'Perro',
                        'success' => 'Gato',
                        'warning' => 'Ave',
                        'info' => 'Conejo',
                        'danger' => 'Hamster',
                        'gray' => fn ($state) => !in_array($state, ['Perro', 'Gato', 'Ave', 'Conejo', 'Hamster']),
                    ])
                    ->formatStateUsing(fn ($state) => match($state) {
                        'Perro' => '🐕 Perro',
                        'Gato' => '🐈 Gato',
                        'Ave' => '🦜 Ave',
                        'Conejo' => '🐰 Conejo',
                        'Hamster' => '🐹 Hamster',
                        'Pez' => '🐠 Pez',
                        'Tortuga' => '🐢 Tortuga',
                        default => '🐾 ' . $state,
                    }),
                
                Tables\Columns\BadgeColumn::make('sexo')
                    ->label('Sexo')
                    ->colors([
                        'info' => 'Macho',
                        'danger' => 'Hembra',
                    ])
                    ->formatStateUsing(fn ($state) => $state === 'Macho' ? '♂ Macho' : ($state === 'Hembra' ? '♀ Hembra' : '-')),
                
                Tables\Columns\TextColumn::make('color')
                    ->label('Color')
                    ->badge()
                    ->colors([
                        'gray' => fn ($state) => in_array($state, ['Negro', 'Gris']),
                        'warning' => fn ($state) => in_array($state, ['Dorado', 'Naranja', 'Crema']),
                        'success' => 'Blanco',
                        'primary' => fn ($state) => in_array($state, ['Marrón', 'Atigrado', 'Manchado', 'Tricolor']),
                    ])
                    ->formatStateUsing(fn ($state) => match($state) {
                        'Negro' => '⚫ Negro',
                        'Blanco' => '⚪ Blanco',
                        'Marrón' => '🟤 Marrón',
                        'Gris' => '⚫ Gris',
                        'Dorado' => '🟡 Dorado',
                        'Atigrado' => '🐯 Atigrado',
                        'Manchado' => '🐄 Manchado',
                        'Tricolor' => '🎨 Tricolor',
                        'Naranja' => '🟠 Naranja',
                        'Crema' => '🟨 Crema',
                        default => $state ?? '-',
                    }),
                
                Tables\Columns\TextColumn::make('fecha_nacimiento')
                    ->label('Edad')
                    ->date('d/m/Y')
                    ->description(function ($record) {
                            if (!$record->fecha_nacimiento) {
                                return null;
                            }

                            $nacimiento = \Carbon\Carbon::parse($record->fecha_nacimiento);
                            $ahora = now();

                            // Evita edades negativas si la fecha es futura
                            if ($nacimiento->isFuture()) {
                                return 'Fecha inválida';
                            }

                            $diferencia = $ahora->diff($nacimiento);
                            $anos = $diferencia->y;
                            $meses = $diferencia->m;

                            $partes_edad = [];

                            // Añadir años solo si es mayor que cero
                            if ($anos > 0) {
                                $partes_edad[] = $anos . ($anos === 1 ? ' año' : ' años');
                            }

                            // Añadir meses solo si es mayor que cero
                            if ($meses > 0) {
                                $partes_edad[] = $meses . ($meses === 1 ? ' mes' : ' meses');
                            }

                            // Si no hay ni años ni meses (tiene días de nacido)
                            if (empty($partes_edad)) {
                                return '🎂 Menos de un mes';
                            }

                            return '🎂 ' . implode(', ', $partes_edad);
                        })

                    ->toggleable(),
                Tables\Columns\TextColumn::make('peso_kg')
                    ->label('Peso')
                    ->formatStateUsing(fn ($state) => $state ? '⚖️ ' . number_format($state, 1) . ' kg' : '-')
                    ->toggleable(),
                
                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado de Salud')
                    ->colors([
                        'success' => 'Saludable',
                        'warning' => 'En tratamiento',
                        'info' => 'Vacunación pendiente',
                        'danger' => 'Requiere atención',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'Saludable',
                        'heroicon-o-beaker' => 'En tratamiento',
                        'heroicon-o-shield-check' => 'Vacunación pendiente',
                        'heroicon-o-exclamation-triangle' => 'Requiere atención',
                    ])
                    ->formatStateUsing(fn ($state) => match($state) {
                        'Saludable' => '✅ Saludable',
                        'En tratamiento' => '💊 En tratamiento',
                        'Vacunación pendiente' => '💉 Vacunación',
                        'Requiere atención' => '⚠️ Atención',
                        default => $state ?? '-',
                    }),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registrado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('especie')
                    ->label('Especie')
                    ->options([
                        'Perro' => '🐕 Perro',
                        'Gato' => '🐈 Gato',
                        'Ave' => '🦜 Ave',
                        'Conejo' => '🐰 Conejo',
                        'Hamster' => '🐹 Hamster',
                        'Pez' => '🐠 Pez',
                        'Tortuga' => '🐢 Tortuga',
                        'Otro' => '🐾 Otro',
                    ])
                    ->native(false)
                    ->multiple(),
                
                Tables\Filters\SelectFilter::make('sexo')
                    ->label('Sexo')
                    ->options([
                        'Macho' => '♂ Macho',
                        'Hembra' => '♀ Hembra',
                    ])
                    ->native(false),
                
                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado de Salud')
                    ->options([
                        'Saludable' => '✅ Saludable',
                        'En tratamiento' => '💊 En tratamiento',
                        'Vacunación pendiente' => '💉 Vacunación pendiente',
                        'Requiere atención' => '⚠️ Requiere atención',
                    ])
                    ->native(false)
                    ->multiple(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->color('warning')
                    ->label('Editar'),
                Tables\Actions\DeleteAction::make()
                    ->label('Eliminar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No tienes mascotas registradas')
            ->emptyStateDescription('Registra tu primera mascota para llevar un control de su información.')
            ->emptyStateIcon('heroicon-o-heart')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Registrar mi primera mascota')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success'),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->poll('30s');
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
            'index' => Pages\ListMascotas::route('/'),
            'create' => Pages\CreateMascota::route('/create'),
            'edit' => Pages\EditMascota::route('/{record}/edit'),
        ];
    }

    // Filtrar solo las mascotas del usuario autenticado
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('propietario_id', auth()->id());
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('propietario_id', auth()->id())->count();
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        $count = static::getModel()::where('propietario_id', auth()->id())->count();
        return $count > 0 ? 'success' : 'gray';
    }
}
