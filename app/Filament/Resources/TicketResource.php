<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers;
use App\Filament\Resources\TicketResource\RelationManagers\TicketAdjuntosRelationManager;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationGroup = 'Condominios';
    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationLabel = 'Tickets de Soporte';

    protected static ?string $modelLabel = 'Ticket';

    protected static ?string $pluralModelLabel = 'Tickets';

    public static function getNavigationBadge(): ?string { return static::getModel()::count(); }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Ticket')
                    ->description('Complete los datos básicos del ticket de soporte')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Forms\Components\Hidden::make('user_id')
                            ->default(auth()->id()),
                        Forms\Components\Select::make('titulo')
                            ->label('Tipo de Ticket')
                            ->options([
                                'Daños' => 'Daños',
                                'Problemas comunes' => 'Problemas comunes',
                                'Otro' => 'Otro',
                            ])
                            ->required()
                            ->native(false)
                            ->default('Daños')
                            ->placeholder('Seleccione el tipo de ticket')
                            ->helperText('Seleccione el tipo de problema')
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set) {
                                if ($state === 'Daños') {
                                    $set('estado', 'Abierto');
                                    $set('prioridad', 'Alta');
                                } elseif ($state === 'Problemas comunes') {
                                    $set('estado', 'Abierto');
                                    $set('prioridad', 'Media');
                                } elseif ($state === 'Otro') {
                                    $set('estado', 'Abierto');
                                    $set('prioridad', 'Baja');
                                }
                            }),
                        Forms\Components\Textarea::make('descripcion')
                            ->label('Descripción')
                            ->required()
                            ->rows(4)
                            ->placeholder('Describa detalladamente el problema...')
                            ->columnSpanFull()
                            ->helperText('Proporcione todos los detalles relevantes del problema'),
                    ])->columns(2),
                Forms\Components\Section::make('Estado y Prioridad')
                    ->description('Configure el estado y prioridad del ticket
                    (en caso de que quiera colocar un video,foto o algun archivo cree el ticket y despues editelo y ahy podra  adjuntos los archivos )')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->schema([
                        Forms\Components\Select::make('estado')
                            ->label('Estado')
                            ->options([
                                'Abierto' => 'Abierto',
                                'En proceso' => 'En proceso',
                                'Cerrado' => 'Cerrado',
                            ])
                            ->native(false)
                            ->default('Abierto')
                            ->required()
                            ->placeholder('Seleccione el estado')
                            ->helperText('Estado actual del ticket'),
                            // El estado se asigna automáticamente según el tipo
                        Forms\Components\Select::make('prioridad')
                            ->label('Prioridad')
                            ->options([
                                'Baja' => 'Baja',
                                'Media' => 'Media',
                                'Alta' => 'Alta',
                            ])
                            ->native(false)
                            ->default('Baja')
                            ->placeholder('Seleccione la prioridad')
                            ->helperText('La prioridad se asigna automáticamente, editable solo para tipo "Otro"')
                            ->disabled(fn (Get $get) => $get('titulo') !== 'Otro'), // Habilitado solo si título es "Otro"
                        
                            Forms\Components\Select::make('tecnico.name')
                            ->label('Técnico Asignado')
                            ->relationship('tecnico', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Asignar técnico (opcional)')
                            ->helperText('Técnico responsable de resolver el ticket')
                             // Los usuarios no pueden asignar técnicos
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable()
                    ->size('sm')
                    ->weight('bold')
                    ->color('primary'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuario')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-user')
                    ->color('gray'),
                Tables\Columns\TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    })
                    ->weight('medium'),
                Tables\Columns\BadgeColumn::make('estado')
                    ->label('Estado')
                    ->colors([
                        'warning' => 'Abierto',
                        'primary' => 'En proceso',
                        'success' => 'Cerrado',
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'Abierto',
                        'heroicon-o-cog-6-tooth' => 'En proceso',
                        'heroicon-o-check-circle' => 'Cerrado',
                    ]),
                Tables\Columns\BadgeColumn::make('prioridad')
                    ->label('Prioridad')
                    ->colors([
                        'success' => 'Baja',
                        'warning' => 'Media',
                        'danger' => 'Alta',
                     ])
                    ->icons([
                        'heroicon-o-arrow-down' => 'Baja',
                        'heroicon-o-minus' => 'Media',
                        'heroicon-o-arrow-up' => 'Alta',
                ]),
                Tables\Columns\TextColumn::make('tecnico.name')
                    ->label('Técnico')
                    ->description('tecnico.email')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->color('info')
                    ->placeholder('Sin asignar'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->icon('heroicon-o-calendar')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->icon('heroicon-o-clock')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        'Abierto' => 'Abierto',
                        'En proceso' => 'En proceso',
                        'Cerrado' => 'Cerrado',
                    ])
                    ->placeholder('Todos los estados'),
                Tables\Filters\SelectFilter::make('prioridad')
                    ->label('Prioridad')
                    ->options([
                        'Baja' => 'Baja',
                        'Media' => 'Media',
                        'Alta' => 'Alta',
                    ])
                    ->placeholder('Todas las prioridades'),
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Usuario')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder('Todos los usuarios'),
                Tables\Filters\SelectFilter::make('tecnico_id')
                    ->label('Técnico')
                    ->relationship('tecnico', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder('Todos los técnicos'),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Creado desde'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Creado hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->color('info')
                        ->icon('heroicon-o-eye'),
                    Tables\Actions\EditAction::make()
                        ->color('warning')
                        ->icon('heroicon-o-pencil'),
                    Tables\Actions\DeleteAction::make()
                        ->color('danger')
                        ->icon('heroicon-o-trash'),
                ])
                ->label('Acciones')
                ->color('info'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->icon('heroicon-o-trash'),
                    Tables\Actions\BulkAction::make('cambiar_estado')
                        ->label('Cambiar Estado')
                        ->icon('heroicon-o-arrow-path')
                        ->color('primary')
                        ->form([
                            Forms\Components\Select::make('estado')
                                ->label('Nuevo Estado')
                                ->options([
                                    'Abierto' => 'Abierto',
                                    'En proceso' => 'En proceso',
                                    'Cerrado' => 'Cerrado',
                                ])
                                ->required(),
                        ])
                        ->action(function (array $data, $records) {
                            $records->each(function ($record) use ($data) {
                                $record->update(['estado' => $data['estado']]);
                            });
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
        
    }

    public static function getRelations(): array
    {
        return [
              RelationManagers\TicketAdjuntosRelationManager::class,
        ];
    }	

    public static function getWidgets(): array
    {
        return [
             TicketResource\Widgets\TicketStatsWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'view' => Pages\ViewTicket::route('/{record}'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
