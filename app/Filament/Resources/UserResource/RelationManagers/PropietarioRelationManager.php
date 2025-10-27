<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PropietarioRelationManager extends RelationManager
{
    protected static string $relationship = 'mascotas';
    protected static ?string $title = 'Mascotas';
    protected static ?string $icon = 'heroicon-o-heart';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información de la Mascota')
                    ->description('Datos básicos de la mascota')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        Forms\Components\TextInput::make('nombre')
                            ->label('Nombre')
                            ->placeholder('Ej: Max, Luna, Firulais')
                            ->required()
                            ->maxLength(100)
                            ->columnSpan(2),
                        
                        Forms\Components\Select::make('especie')
                            ->label('Especie')
                            ->options([
                                'Perro' => 'Perro 🐕',
                                'Gato' => 'Gato 🐈',
                                'Ave' => 'Ave 🦜',
                                'Conejo' => 'Conejo 🐰',
                                'Hamster' => 'Hamster 🐹',
                                'Pez' => 'Pez 🐠',
                                'Tortuga' => 'Tortuga 🐢',
                                'Otro' => 'Otro',
                            ])
                            ->required()
                            ->native(false)
                            ->searchable()
                            ->columnSpan(1),
                        
                        Forms\Components\TextInput::make('raza')
                            ->label('Raza')
                            ->placeholder('Ej: Labrador, Persa, Canario')
                            ->maxLength(100)
                            ->columnSpan(1),
                        
                        Forms\Components\Select::make('sexo')
                            ->label('Sexo')
                            ->options([
                                'Macho' => 'Macho ♂',
                                'Hembra' => 'Hembra ♀',
                            ])
                            ->native(false)
                            ->columnSpan(1),
                        
                        Forms\Components\Select::make('color')
                            ->label('Color')
                            ->options([
                                'Negro' => 'Negro',
                                'Blanco' => 'Blanco',
                                'Marrón' => 'Marrón',
                                'Gris' => 'Gris',
                                'Dorado' => 'Dorado',
                                'Atigrado' => 'Atigrado',
                                'Manchado' => 'Manchado',
                                'Tricolor' => 'Tricolor',
                                'Otro' => 'Otro',
                            ])
                            ->native(false)
                            ->searchable()
                            ->columnSpan(1),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Datos Físicos y Salud')
                    ->description('Información sobre edad, peso y estado')
                    ->icon('heroicon-o-heart')
                    ->schema([
                        Forms\Components\DatePicker::make('fecha_nacimiento')
                            ->label('Fecha de Nacimiento')
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->maxDate(now())
                            ->columnSpan(1),
                        
                        Forms\Components\TextInput::make('peso_kg')
                            ->label('Peso (kg)')
                            ->numeric()
                            ->step(0.01)
                            ->minValue(0)
                            ->maxValue(999.99)
                            ->suffix('kg')
                            ->placeholder('Ej: 15.5')
                            ->columnSpan(1),
                        
                        Forms\Components\Select::make('estado')
                            ->label('Estado de Salud')
                            ->options([
                                'Saludable' => 'Saludable',
                                'En tratamiento' => 'En tratamiento',
                                'Vacunación pendiente' => 'Vacunación pendiente',
                                'Requiere atención' => 'Requiere atención',
                            ])
                            ->native(false)
                            ->placeholder('Seleccione el estado')
                            ->columnSpan(1),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Observaciones')
                    ->description('Notas adicionales sobre la mascota')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Forms\Components\Textarea::make('observaciones')
                            ->label('Observaciones')
                            ->placeholder('Alergias, comportamiento, cuidados especiales, etc.')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nombre')
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-o-heart'),
                
                Tables\Columns\BadgeColumn::make('especie')
                    ->label('Especie')
                    ->colors([
                        'primary' => 'Perro',
                        'success' => 'Gato',
                        'warning' => 'Ave',
                        'info' => fn ($state) => !in_array($state, ['Perro', 'Gato', 'Ave']),
                    ])
                    ->icons([
                        'heroicon-o-heart' => fn ($state) => true,
                    ]),
                
                Tables\Columns\TextColumn::make('raza')
                    ->label('Raza')
                    ->searchable()
                    ->toggleable()
                    ->placeholder('No especificada'),
                
                Tables\Columns\BadgeColumn::make('sexo')
                    ->label('Sexo')
                    ->colors([
                        'info' => 'Macho',
                        'danger' => 'Hembra',
                    ])
                    ->formatStateUsing(fn ($state) => $state === 'Macho' ? '♂ Macho' : '♀ Hembra'),
                
                Tables\Columns\TextColumn::make('color')
                    ->label('Color')
                    ->badge()
                    ->color('gray')
                    ->toggleable(),
                
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
                    ->suffix(' kg')
                    ->numeric(2)
                    ->toggleable(),
                
                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
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
                    ->toggleable(),
                
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
                        'Perro' => 'Perro',
                        'Gato' => 'Gato',
                        'Ave' => 'Ave',
                        'Conejo' => 'Conejo',
                        'Hamster' => 'Hamster',
                        'Pez' => 'Pez',
                        'Tortuga' => 'Tortuga',
                        'Otro' => 'Otro',
                    ])
                    ->native(false),
                
                Tables\Filters\SelectFilter::make('sexo')
                    ->label('Sexo')
                    ->options([
                        'Macho' => 'Macho',
                        'Hembra' => 'Hembra',
                    ])
                    ->native(false),
                
                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado de Salud')
                    ->options([
                        'Saludable' => 'Saludable',
                        'En tratamiento' => 'En tratamiento',
                        'Vacunación pendiente' => 'Vacunación pendiente',
                        'Requiere atención' => 'Requiere atención',
                    ])
                    ->native(false),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Registrar Mascota')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success'),
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
            ->emptyStateHeading('No hay mascotas registradas')
            ->emptyStateDescription('Registre la primera mascota de este residente.')
            ->emptyStateIcon('heroicon-o-heart')
            ->defaultSort('created_at', 'desc')
            ->striped();
    }
}
