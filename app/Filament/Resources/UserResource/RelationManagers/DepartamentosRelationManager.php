<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use App\Models\Departamento;
use App\Models\User;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DepartamentosRelationManager extends RelationManager
{
    protected static string $relationship = 'departamentos';

    public function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Grid::make(2)
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
                                    ->afterStateUpdated(fn (callable $set) => $set('piso', null) && $set('numero', null))
                                    ->placeholder('Selecciona un bloque'),
                                Forms\Components\Select::make('piso')
                                    ->label('Piso')
                                    ->options(function (callable $get) {
                                        $bloque = $get('bloque');
                                        if (!$bloque) return [];
                                        
                                        return [
                                            'Piso 1' => 'Piso 1',
                                            'Piso 2' => 'Piso 2',
                                            'Piso 3' => 'Piso 3',
                                            'Piso 4' => 'Piso 4',
                                        ];
                                    })
                                    ->native(false)
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(fn (callable $set) => $set('numero', null))
                                    ->placeholder('Selecciona un piso')
                                    ->disabled(fn (callable $get) => !$get('bloque')),
                           

                        Forms\Components\Select::make('numero')
                            ->label('Número de Departamento')
                            ->options(function (callable $get) {
                                $bloque = $get('bloque');
                                $piso = $get('piso');
                            
                                if (!$bloque || !$piso) return [];
                                
                                $pisoNumber = substr($piso, -1);
                                return [
                                    "D{$bloque}{$pisoNumber}" => "D{$bloque}{$pisoNumber}",
                                ];
                            })
                            ->native(false)
                            ->required()
                            ->placeholder('Selecciona bloque y piso primero')
                            ->disabled(fn (callable $get) => !$get('bloque') || !$get('piso')),

                        ]),
           
                    ])
                    ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('numero')
            ->columns([
                Tables\Columns\TextColumn::make('numero')
                    ->label('Número')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('bloque')
                    ->label('Bloque')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('piso')
                    ->label('Piso')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Residente')
                    ->sortable()
                    ->searchable()
                    ->placeholder('Sin asignar'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('bloque')
                    ->label('Bloque')
                    ->options([
                        'A' => 'Bloque A',
                        'B' => 'Bloque B',
                        'C' => 'Bloque C',
                        'D' => 'Bloque D',
                    ]),
                Tables\Filters\SelectFilter::make('piso')
                    ->label('Piso')
                    ->options([
                        'Piso 1' => 'Piso 1',
                        'Piso 2' => 'Piso 2',
                        'Piso 3' => 'Piso 3',
                        'Piso 4' => 'Piso 4',
                    ]),
                Tables\Filters\Filter::make('sin_residente')
                    ->label('Sin Residente')
                    ->query(fn (Builder $query): Builder => $query->whereNull('user_id')),
                Tables\Filters\Filter::make('con_residente')
                    ->label('Con Residente')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('user_id')),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Crear Departamento'),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label('Editar')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (Departamento $record): string => route('filament.admin.resources.departamentos.edit', $record)),
                Tables\Actions\ViewAction::make()
                    ->label('Ver'),
                Tables\Actions\DeleteAction::make()
                    ->label('Eliminar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Eliminar seleccionados'),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Crear primer departamento'),
            ]);
    }
}
