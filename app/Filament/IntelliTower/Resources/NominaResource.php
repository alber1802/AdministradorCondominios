<?php

namespace App\Filament\IntelliTower\Resources;

use App\Filament\IntelliTower\Resources\NominaResource\Pages;
use App\Models\Nomina;
use App\Models\User;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class NominaResource extends Resource
{
    protected static ?string $model = Nomina::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Finanzas';
    
    protected static ?string $navigationLabel = 'Mis Nóminas';
    
    protected static ?string $modelLabel = 'Nómina';
    
    protected static ?string $pluralModelLabel = 'Mis Nóminas';

    /**
     * Para no mostar el recuerso 
     */
    
   // protected static bool $shouldRegisterNavigation = false;

    /**
     *  para controlar si un asuario tenga acceao a este recurso 
     */

    public static function canAccess(): bool
    {
         return false;
         //auth()->check() && auth()->user()->rol != 'super_admin';
    }
    
    /**
     * Filter nominas to show only those belonging to the authenticated user
     */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
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
                            ->disabled()
                            ->columnSpanFull(),
                    ]),
                    
                Forms\Components\Section::make('Detalles de la Nómina')
                    ->schema([
                        Forms\Components\TextInput::make('mes')
                            ->label('Mes')
                            ->placeholder('Ej: Enero 2024')
                            ->maxLength(255)
                            ->disabled(),
                            
                        Forms\Components\TextInput::make('monto')
                            ->label('Monto')
                            ->numeric()
                            ->prefix('$')
                            ->step(0.01)
                            ->disabled(),
                            
                        Forms\Components\Select::make('estado')
                            ->label('Estado')
                            ->options([
                                'Pendiente' => 'Pendiente',
                                'Pagado' => 'Pagado',
                            ])
                            ->default('Pendiente')
                            ->native(false)
                            ->disabled(),
                            
                        Forms\Components\DatePicker::make('fecha_pago')
                            ->label('Fecha de Pago')
                            ->native(false)
                            ->disabled(),
                            
                        Forms\Components\FileUpload::make('comprobante_pdf')
                            ->label('Comprobante de Pago (PDF/Imagen)')
                            ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/jpg', 'image/png', 'image/gif'])
                            ->maxSize(5120) // 5MB
                            ->directory('comprobantes-nomina')
                            ->visibility('private')
                            ->columnSpanFull()
                            ->disabled(),
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
                Tables\Actions\Action::make('descargar_comprobante')
                    ->label('Descargar PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn ($record) => $record->comprobante_pdf ? asset('storage/' . $record->comprobante_pdf) : null)
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => !empty($record->comprobante_pdf)),
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
            'view' => Pages\ViewNomina::route('/{record}'),
            // No incluir create ni edit para usuarios
        ];
    }
}