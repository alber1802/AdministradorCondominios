<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NominaResource\Pages;
use App\Filament\Resources\NominaResource\RelationManagers;
use App\Models\Nomina;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NominaResource extends Resource
{
    protected static ?string $model = Nomina::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Finanzas';
    
    protected static ?string $navigationLabel = 'Nóminas';
    
    protected static ?string $modelLabel = 'Nómina';
    
    protected static ?string $pluralModelLabel = 'Nóminas';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Empleado')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Empleados')
                            ->options(User::where('rol','admin')->pluck('name','id'))
                            ->searchable('name', 'email')
                            ->native(false)
                            ->preload()
                            ->required()
                            ->columnSpanFull(),
                    ]),
                    
                Forms\Components\Section::make('Detalles de la Nómina')
                    ->schema([
                        Forms\Components\TextInput::make('mes')
                            ->label('Mes')
                            ->placeholder('Ej: Enero 2024')
                            ->required()
                            ->maxLength(255),
                            
                        Forms\Components\TextInput::make('monto')
                            ->label('Monto')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->step(0.01),
                            
                        Forms\Components\Select::make('estado')
                            ->label('Estado')
                            ->options([
                                'Pendiente' => 'Pendiente',
                                'Pagado' => 'Pagado',
                                
                            ])
                            ->required()
                            ->default('Pendiente')
                            ->native(false),
                            
                        Forms\Components\DatePicker::make('fecha_pago')
                            ->label('Fecha de Pago')
                            ->native(false),
                            
                        Forms\Components\FileUpload::make('comprobante_pdf')
                            ->label('Comprobante de Pago (PDF/Imagen)')
                            ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/jpg', 'image/png', 'image/gif'])
                            ->maxSize(5120) // 5MB
                            ->directory('comprobantes-nomina')
                            ->visibility('private')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Empleado')
                    ->description(fn ($record) => $record->user->email ?? '')
                    ->sortable()
                    ->searchable()
                    ->weight('medium'),
                    
                Tables\Columns\TextColumn::make('mes')
                    ->label('Mes')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                    
                Tables\Columns\TextColumn::make('monto')
                    ->label('Monto')
                    ->money('USD')
                    ->sortable()
                    ->alignEnd()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pendiente' => 'warning',
                        'Pagado' => 'success',
                        'pendiente' => 'warning',
                        'pagado' => 'success',
                        default => 'gray',
                    })
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('fecha_pago')
                    ->label('Fecha de Pago')
                    ->date('d/m/Y')
                    ->sortable()
                    ->placeholder('Aún no pagado')
                    ->color(fn ($state) => $state ? 'success' : 'warning'),
                    
                Tables\Columns\TextColumn::make('comprobante_pdf')
                    ->label('Comprobante')
                    ->formatStateUsing(function ($state) {
                        if ($state) {
                            return '📄 PDF disponible';
                        }
                        return '';
                    })
                    ->color(fn ($state) => $state ? 'success' : null)
                    ->alignCenter(),
                    
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
                        'Pendiente' => 'Pendiente',
                        'Pagado' => 'Pagado',
                    ])
                    ->native(false),
                    
                Tables\Filters\Filter::make('fecha_pago')
                    ->form([
                        Forms\Components\DatePicker::make('fecha_desde')
                            ->label('Fecha desde'),
                        Forms\Components\DatePicker::make('fecha_hasta')
                            ->label('Fecha hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['fecha_desde'],
                                fn (Builder $query, $date): Builder => $query->whereDate('fecha_pago', '>=', $date),
                            )
                            ->when(
                                $data['fecha_hasta'],
                                fn (Builder $query, $date): Builder => $query->whereDate('fecha_pago', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->color('info'),
                Tables\Actions\EditAction::make()
                    ->color('warning'),
                Tables\Actions\Action::make('descargar_comprobante')
                    ->label('Descargar PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn ($record) => $record->comprobante_pdf ? asset('storage/' . $record->comprobante_pdf) : null)
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => !empty($record->comprobante_pdf)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
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
            'index' => Pages\ListNominas::route('/'),
            'create' => Pages\CreateNomina::route('/create'),
            'view' => Pages\ViewNomina::route('/{record}'),
            'edit' => Pages\EditNomina::route('/{record}/edit'),
        ];
    }
}
