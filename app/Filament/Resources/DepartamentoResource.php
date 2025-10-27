<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartamentoResource\Pages;
use App\Filament\Resources\DepartamentoResource\RelationManagers;
use App\Filament\Resources\DepartamentoResource\RelationManagers\ConsumosRelationManager;
use App\Models\User;
use App\Models\Departamento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DepartamentoResource extends Resource
{
    protected static ?string $model = Departamento::class;

    protected static ?string $navigationLabel = 'Departamentos';
    
    protected static ?string $modelLabel = 'Departamento';
    
    protected static ?string $pluralModelLabel = 'Departamentos';

    protected static ?string $navigationGroup = 'Condominios';

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    
    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 50 ? 'warning' : 'primary';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Departamento')
                    ->description('Configura los datos básicos del departamento')
                    ->icon('heroicon-o-building-office-2')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('bloque')
                                    ->label('Bloque')
                                    ->options([
                                        'A' => 'Bloque A',
                                        'B' => 'Bloque B',
                                        'C' => 'Bloque C',
                                        'D' => 'Bloque D',
                                    ])
                                    ->native(false)
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function (callable $set, callable $get) {
                                        $set('piso', null);
                                        $set('numero', null);
                                    })
                                    ->placeholder('Selecciona un bloque')
                                    ->helperText('Selecciona el bloque del edificio'),

                                Forms\Components\Select::make('piso')
                                    ->label('Piso')
                                    ->options(function (callable $get) {
                                        $bloque = $get('bloque');
                                        if (!$bloque) return [];
                                        
                                        return [
                                            1 => 'Piso 1',
                                            2 => 'Piso 2',
                                            3 => 'Piso 3',
                                            4 => 'Piso 4',
                                            5 => 'Piso 5',
                                            6 => 'Piso 6',
                                            7 => 'Piso 7',
                                        ];
                                    })
                                    ->native(false)
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function (callable $set, callable $get) {
                                        $bloque = $get('bloque');
                                        $piso = $get('piso');
                                        
                                        if ($bloque && $piso) {
                                            // Generate available department numbers for this block and floor
                                            $existingNumbers = Departamento::where('bloque', $bloque)
                                                ->where('piso', "Piso {$piso}")
                                                ->pluck('numero')
                                                ->toArray();
                                            
                                            $availableNumbers = [];
                                            for ($i = 1; $i <= 5; $i++) { // Assuming max 10 apartments per floor
                                                $number = $bloque . $piso . str_pad($i, 2, '0', STR_PAD_LEFT);
                                                if (!in_array($number, $existingNumbers)) {
                                                    $availableNumbers[$number] = $number;
                                                }
                                            }
                                            
                                            // Auto-select the first available number
                                            if (!empty($availableNumbers)) {
                                                $set('numero', array_key_first($availableNumbers));
                                            }
                                        } else {
                                            $set('numero', null);
                                        }
                                    })
                                    ->placeholder('Selecciona un piso')
                                    ->disabled(fn (callable $get) => !$get('bloque'))
                                    ->helperText('Selecciona el piso del departamento'),

                                Forms\Components\Select::make('numero')
                                    ->label('Número de Departamento')
                                    ->options(function (callable $get) {
                                        $bloque = $get('bloque');
                                        $piso = $get('piso');
                                        
                                        if (!$bloque || !$piso) return [];
                                        
                                        // Get existing department numbers for this block and floor
                                        $existingNumbers = Departamento::where('bloque', $bloque)
                                            ->where('piso', "Piso {$piso}")
                                            ->pluck('numero')
                                            ->toArray();
                                        
                                        $availableNumbers = [];
                                        for ($i = 1; $i <= 5; $i++) { // Max 10 apartments per floor
                                            $number = $bloque . $piso . str_pad($i, 2, '0', STR_PAD_LEFT);
                                            if (!in_array($number, $existingNumbers)) {
                                                $availableNumbers[$number] = $number;
                                            }
                                        }
                                        
                                        return $availableNumbers;
                                    })
                                    ->native(false)
                                    ->required()
                                    ->placeholder('Se generará automáticamente')
                                    ->disabled(fn (callable $get) => !$get('bloque') || !$get('piso'))
                                    ->helperText('Número generado automáticamente basado en bloque y piso'),
                            ]),
                    ]),

                Forms\Components\Section::make('Asignación de Residente')
                    ->description('Asigna un residente al departamento (opcional)')
                    ->icon('heroicon-o-user-group')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Residente Asignado')
                            ->options(User::all()->pluck('name', 'id'))
                            ->searchable()
                            ->placeholder('Buscar residente...')
                            ->helperText('Opcional: Asigna un residente al departamento')
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('estado')
                            ->label('Estado')
                            ->default(false)
                            ->helperText('Si el departamento sigue ocupando por ese residente, marca esta casilla')
                            ->columnSpanFull(),
                    ]),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('numero')
                    ->label('Departamento')
                    ->sortable()
                    ->searchable()
                    ->weight('bold')
                    ->color('primary')
                    ->icon('heroicon-o-home')
                    ->copyable()
                    ->copyMessage('Número copiado')
                    ->copyMessageDuration(1500),
                    
                Tables\Columns\TextColumn::make('bloque')
                    ->label('Bloque')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'A' => 'success',
                        'B' => 'warning',
                        'C' => 'info',
                        'D' => 'danger',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('piso')
                    ->label('Piso')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('gray'),
                    
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Residente')
                    ->description(fn ($record) => $record->user?->email)
                    ->searchable(['name', 'email'])
                    ->default('🏠 Departamento libre')
                    ->placeholder('🏠 Departamento libre')
                    ->icon(fn ($record) => $record->user ? 'heroicon-o-user' : 'heroicon-o-home')
                    ->color(fn ($record) => $record->user ? 'success' : 'gray'),

                Tables\Columns\IconColumn::make('estado')
                    ->label('Estado')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->user_id !== null)
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color('gray'),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color('gray'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('bloque')
                    ->label('Filtrar por Bloque')
                    ->options([
                        'A' => '🏢 Bloque A',
                        'B' => '🏢 Bloque B',
                        'C' => '🏢 Bloque C',
                        'D' => '🏢 Bloque D',
                    ])
                    ->multiple()
                    ->placeholder('Todos los bloques'),
                    
                Tables\Filters\SelectFilter::make('piso')
                    ->label('Filtrar por Piso')
                    ->options([
                        'Piso 1' => '1️⃣ Piso 1',
                        'Piso 2' => '2️⃣ Piso 2',
                        'Piso 3' => '3️⃣ Piso 3',
                        'Piso 4' => '4️⃣ Piso 4',
                        'Piso 5' => '5️⃣ Piso 5',
                        'Piso 6' => '6️⃣ Piso 6',
                        'Piso 7' => '7️⃣ Piso 7',
                    ])
                    ->multiple()
                    ->placeholder('Todos los pisos'),
                    
                Tables\Filters\Filter::make('ocupado')
                    ->label('Estado de Ocupación')
                    ->form([
                        Forms\Components\Select::make('estado')
                            ->label('Estado')
                            ->options([
                                'ocupado' => '✅ Departamentos Ocupados',
                                'libre' => '❌ Departamentos Libres',
                            ])
                            ->placeholder('Todos los estados'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['estado'] === 'ocupado',
                                fn (Builder $query): Builder => $query->whereNotNull('user_id'),
                            )
                            ->when(
                                $data['estado'] === 'libre',
                                fn (Builder $query): Builder => $query->whereNull('user_id'),
                            );
                    }),
                    
                Tables\Filters\Filter::make('numero_range')
                    ->label('Rango de Departamentos')
                    ->form([
                        Forms\Components\TextInput::make('numero_desde')
                            ->label('Desde')
                            ->placeholder('Ej: A101'),
                        Forms\Components\TextInput::make('numero_hasta')
                            ->label('Hasta')
                            ->placeholder('Ej: A110'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['numero_desde'],
                                fn (Builder $query, $numero): Builder => $query->where('numero', '>=', $numero),
                            )
                            ->when(
                                $data['numero_hasta'],
                                fn (Builder $query, $numero): Builder => $query->where('numero', '<=', $numero),
                            );
                    }),
            ])  
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->color('info')
                        ->icon('heroicon-o-eye'),
                    Tables\Actions\EditAction::make()
                        ->color('warning')
                        ->icon('heroicon-o-pencil-square'),
                    Tables\Actions\Action::make('toggle_resident')
                        ->label(fn ($record) => $record->user_id ? 'Liberar' : 'Asignar Residente')
                        ->icon(fn ($record) => $record->user_id ? 'heroicon-o-x-mark' : 'heroicon-o-user-plus')
                        ->color(fn ($record) => $record->user_id ? 'danger' : 'success')
                        ->action(function ($record) {
                            if ($record->user_id) {
                                $record->update(['user_id' => null]);
                            } else {
                                // Redirect to edit form to assign resident
                                return redirect()->route('filament.admin.resources.departamentos.edit', $record);
                            }
                        })
                        ->requiresConfirmation(fn ($record) => $record->user_id !== null)
                        ->modalHeading(fn ($record) => $record->user_id ? 'Liberar Departamento' : 'Asignar Residente')
                        ->modalDescription(fn ($record) => $record->user_id 
                            ? 'Esta acción liberará el departamento y eliminará la asignación del residente actual.'
                            : 'Serás redirigido al formulario de edición para asignar un residente.'
                        ),
                    Tables\Actions\DeleteAction::make()
                        ->color('danger')
                        ->icon('heroicon-o-trash'),
                ])
                ->button()
                ->color('gray')
                ->icon('heroicon-o-ellipsis-vertical')
                ->tooltip('Acciones'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                    Tables\Actions\BulkAction::make('liberar_departamentos')
                        ->label('Liberar Departamentos')
                        ->icon('heroicon-o-home')
                        ->color('warning')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['user_id' => null]);
                            });
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Liberar Departamentos Seleccionados')
                        ->modalDescription('Esta acción liberará todos los departamentos seleccionados, eliminando la asignación de residentes.')
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Crear Primer Departamento')
                    ->icon('heroicon-o-plus'),
            ])
            ->defaultSort('numero')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }

    public static function getRelations(): array
    {
        return [
           
            RelationManagers\ConsumosRelationManager::class,
            
           
        ];
    }

    public static function getWidgets(): array
    {
        return [
            DepartamentoResource\Widgets\DepartamentoStatsWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDepartamentos::route('/'),
            'create' => Pages\CreateDepartamento::route('/create'),
            'view' => Pages\ViewDepartamento::route('/{record}'),
            'edit' => Pages\EditDepartamento::route('/{record}/edit'),
        ];
    }
}
