<?php

namespace App\Filament\IntelliTower\Resources;

use App\Filament\IntelliTower\Resources\FacturaResource\Pages;
use App\Filament\IntelliTower\Resources\FacturaResource\RelationManagers;
use App\Models\Factura;
use App\Models\Departamento;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Tabs;

class FacturaResource extends Resource
{
    protected static ?string $model = Factura::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationLabel = 'Mis Facturas';
    
    protected static ?string $modelLabel = 'Factura';
    
    protected static ?string $pluralModelLabel = 'Mis Facturas';
    
    protected static ?string $navigationGroup = 'Finanzas';

    public static function getNavigationBadge(): ?string { return static::getEloquentQuery()->count(); }

    // Filtrar solo las facturas del usuario autenticado
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Tabs::make('Tabs')
                ->columnSpanFull()
                ->tabs([
                    Tabs\Tab::make('Factura')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Forms\Components\Section::make('Información General')
                                ->icon('heroicon-o-information-circle')
                                ->schema([
                                    Forms\Components\Select::make('user_id')
                                        ->label('Usuario')
                                        ->options(User::all()->pluck('name', 'id'))
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->prefixIcon('heroicon-o-user')
                                        ->live()
                                        ->disabled(), // Deshabilitar para usuarios del panel
                                        
                                    Forms\Components\Select::make('departamento_id')
                                        ->label('Departamento')
                                        ->options(function (callable $get) {
                                            $userId = auth()->id(); // Usar el usuario autenticado
                                            return Departamento::where('user_id', $userId)->pluck('numero', 'id');
                                        })
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->prefixIcon('heroicon-o-building-office')
                                        ->disabled(), // Solo lectura para usuarios
                                        
                                    Forms\Components\Select::make('tipo')
                                        ->label('Tipo de Factura')
                                        ->options([
                                            'mantenimiento' => 'Mantenimiento',
                                            'expensas' => 'Expensas',
                                            'alquiler' => 'Alquiler',
                                            'servicios' => 'Servicios',
                                            'otros' => 'Otros',
                                        ])
                                        ->required()
                                        ->native(false)
                                        ->prefixIcon('heroicon-o-tag')
                                        ->disabled(), // Solo lectura para usuarios
                                ])
                                ->columns(3),
                                
                            Forms\Components\Section::make('Detalles Financieros')
                                ->icon('heroicon-o-currency-dollar')
                                ->schema([
                                    Forms\Components\TextInput::make('monto')
                                        ->label('Monto')
                                        ->required()
                                        ->numeric()
                                        ->prefix('$')
                                        ->step(0.01)
                                        ->prefixIcon('heroicon-o-banknotes')
                                        ->disabled(), // Solo lectura para usuarios
                                        
                                    Forms\Components\Select::make('estado')
                                        ->label('Estado')
                                        ->options([
                                            'pendiente' => 'Pendiente',
                                            'pagado' => 'Pagado',
                                            'anulado' => 'Anulado',
                                        ])
                                        ->required()
                                        ->default('pendiente')
                                        ->native(false)
                                        ->prefixIcon('heroicon-o-check-circle')
                                        ->live()
                                        ->disabled(), // Solo lectura para usuarios
                                        
                                    Forms\Components\Select::make('tipo_pago')
                                        ->label('Tipo de Pago')
                                        ->options([
                                            'tigo_money' => 'Tigo Money',
                                            'tarjeta' => 'Tarjeta',
                                            'cripto' => 'Criptomoneda',
                                            
                                        ])
                                        ->required()
                                        ->native(false)
                                        ->prefixIcon('heroicon-o-credit-card')
                                        ->visible(fn (callable $get) => $get('estado') === 'pagado')
                                        ->live(), // Habilitado para edición
                                ])
                                ->columns(3),
                                
                            Forms\Components\Section::make('Fechas')
                                ->icon('heroicon-o-calendar-days')
                                ->schema([
                                    Forms\Components\DatePicker::make('fecha_emision')
                                        ->label('Fecha de Emisión')
                                        ->native(false)
                                        ->required()
                                        ->default(now())
                                        ->prefixIcon('heroicon-o-calendar')
                                        ->disabled(), // Solo lectura para usuarios
                                        
                                    Forms\Components\DatePicker::make('fecha_vencimiento')
                                        ->label('Fecha de Vencimiento')
                                        ->required()
                                        ->native(false)
                                        ->prefixIcon('heroicon-o-clock')
                                        ->disabled(), // Solo lectura para usuarios
                                ])
                                ->columns(2),
                                
                            Forms\Components\Section::make('Información Adicional')
                                ->icon('heroicon-o-document-text')
                                ->schema([
                                    Forms\Components\Textarea::make('descripcion')
                                        ->label('Descripción')
                                        ->rows(3)
                                        ->columnSpanFull()
                                        ->disabled(), // Solo lectura para usuarios
                                ]),
                        ]),
                        
                    // Tab para Pago con Tigo Money
                    Tabs\Tab::make('Pago Tigo Money')
                        ->icon('heroicon-o-device-phone-mobile')
                        ->visible(fn (callable $get) => $get('estado') === 'pagado' && $get('tipo_pago') === 'tigo_money')
                        ->schema([
                            Forms\Components\Section::make('Información de Pago Tigo Money')
                                ->icon('heroicon-o-device-phone-mobile')
                                ->schema([
                                    Forms\Components\ViewField::make('qr_code_display')
                                        ->label('Código QR')
                                        ->view('filament.components.qr-display')
                                        ->viewData(fn ($record) => [
                                            'qr_code' => $record?->qr_code ?? 'mis_imagenes/icono-codigo-qr.jpg'
                                        ])
                                        ->helperText('Código QR para el pago'),
                                ])
                                ->columns(1),
                        ]),
                        
                    // Tab para Pago con Tarjeta
                    Tabs\Tab::make('Pago Tarjeta')
                        ->icon('heroicon-o-credit-card')
                        ->visible(fn (callable $get) => $get('estado') === 'pagado' && $get('tipo_pago') === 'tarjeta')
                        ->schema([
                            Forms\Components\Section::make('Información de Pago con Tarjeta')
                                ->icon('heroicon-o-credit-card')
                                ->schema([
                                    Forms\Components\TextInput::make('numero_tarjeta')
                                        ->label('Número de Tarjeta')
                                        ->mask('9999 9999 9999 9999')
                                        ->placeholder('1234 5678 9012 3456')
                                        ->prefixIcon('heroicon-o-credit-card'),
                                        
                                    Forms\Components\TextInput::make('cvv')
                                        ->label('CVV/CVC')
                                        ->mask('999')
                                        ->maxLength(4)
                                        ->placeholder('123')
                                        ->prefixIcon('heroicon-o-shield-check')
                                        ,
                                        
                                    Forms\Components\DatePicker::make('fecha_vencimiento_tarjeta')
                                        ->label('Fecha de Vencimiento')
                                        ->native(false)
                                        ->displayFormat('m/Y')
                                        ->prefixIcon('heroicon-o-calendar')
                                        ,
                                
                                    Forms\Components\TextInput::make('banco')
                                        ->label('Banco Emisor')
                                        ->placeholder('Ej: Banco Nacional')
                                        ->prefixIcon('heroicon-o-building-library')
                                        ,
                                        
                                    Forms\Components\TextInput::make('nombre_titular')
                                        ->label('Nombre del Titular')
                                        ->placeholder('Como aparece en la tarjeta')
                                        ->prefixIcon('heroicon-o-user')
                                        ->columnSpanFull()
                                        ,
                                ])
                                ->columns(2),
                        ]),
                        
                    // Tab para Pago con Criptomoneda
                    Tabs\Tab::make('Pago Cripto')
                        ->icon('heroicon-o-currency-dollar')
                        ->visible(fn (callable $get) => $get('estado') === 'pagado' && $get('tipo_pago') === 'cripto')
                        ->schema([
                            Forms\Components\Section::make('Información de Pago con Criptomoneda')
                                ->icon('heroicon-o-currency-dollar')
                                ->schema([
                                    Forms\Components\TextInput::make('wallet_origen')
                                        ->label('Wallet Origen')
                                        ->prefixIcon('heroicon-o-wallet')
                                        ,
                                        
                                    Forms\Components\TextInput::make('wallet_destino')
                                        ->label('Wallet Destino')
                                        ->prefixIcon('heroicon-o-wallet')
                                        ,
                                        
                                    Forms\Components\Select::make('moneda')
                                        ->label('Moneda')
                                        ->options([
                                            'BTC' => 'Bitcoin (BTC)',
                                            'ETH' => 'Ethereum (ETH)',
                                            'USDT' => 'Tether (USDT)',
                                            'USDC' => 'USD Coin (USDC)',
                                            'BNB' => 'Binance Coin (BNB)',
                                        ])
                                        ->native(false)
                                        ->prefixIcon('heroicon-o-banknotes')
                                        ,
                                        
                                    Forms\Components\TextInput::make('hash_transaccion')
                                        ->label('Hash de Transacción')
                                        ->prefixIcon('heroicon-o-hashtag')
                                        ->columnSpanFull()
                                        ,
                                ])
                                ->columns(3),
                        ]),
                        
                    // Tab para Pago en Efectivo
                ])
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
                    ->badge()
                    ->color('gray'),
                    
                Tables\Columns\TextColumn::make('departamento.numero')
                    ->label('Departamento')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color('info')
                    ->icon('heroicon-o-building-office'),
                    
                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo')
                    ->searchable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'mantenimiento' => 'warning',
                        'expensas' => 'info',
                        'alquiler' => 'success',
                        'servicios' => 'primary',
                        'otros' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                    
                Tables\Columns\TextColumn::make('monto')
                    ->label('Monto')
                    ->money('USD')
                    ->sortable()
                    ->alignEnd()
                    ->weight('bold')
                    ->color('success'),
                    
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->searchable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pendiente' => 'warning',
                        'pagado' => 'success',
                        'anulado' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->icon(fn (string $state): string => match ($state) {
                        'pendiente' => 'heroicon-o-clock',
                        'pagado' => 'heroicon-o-check-circle',
                        'anulado' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-question-mark-circle',
                    }),
                    
                Tables\Columns\TextColumn::make('tipo_pago')
                    ->label('Tipo Pago')
                    ->searchable()
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'tigo_money' => 'info',
                        'tarjeta' => 'warning',
                        'cripto' => 'success',
                        'efectivo' => 'primary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => $state ? ucfirst(str_replace('_', ' ', $state)) : '')
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('fecha_emision')
                    ->label('F. Emisión')
                    ->date('d/m/Y')
                    ->sortable()
                    ->icon('heroicon-o-calendar'),
                    
                Tables\Columns\TextColumn::make('fecha_vencimiento')
                    ->label('F. Vencimiento')
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(fn ($state) => $state && $state < now() ? 'danger' : 'gray')
                    ->icon('heroicon-o-clock'),
                    
                Tables\Columns\ImageColumn::make('qr_code')
                    ->label('QR')
                    ->square()
                    ->size(40)
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('comprobante_pdf')
                    ->label('Comprobante')
                    ->formatStateUsing(function ($state) {
                        if ($state) {
                            return '📄 PDF';
                        }
                        return '';
                    })
                    ->color(fn ($state) => $state ? 'success' : null)
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('descripcion')
                    ->label('Descripción')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 30) {
                            return null;
                        }
                        return $state;
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                    
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
                Tables\Filters\SelectFilter::make('tipo')
                    ->label('Tipo')
                    ->options([
                        'mantenimiento' => 'Mantenimiento',
                        'expensas' => 'Expensas',
                        'alquiler' => 'Alquiler',
                        'servicios' => 'Servicios',
                        'otros' => 'Otros',
                    ])
                    ->native(false),
                    
                Tables\Filters\SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'pagado' => 'Pagado',
                        'anulado' => 'Anulado',
                    ])
                    ->native(false),
                    
                Tables\Filters\SelectFilter::make('tipo_pago')
                    ->label('Tipo de Pago')
                    ->options([
                        'tigo_money' => 'Tigo Money',
                        'tarjeta' => 'Tarjeta',
                        'cripto' => 'Criptomoneda',
                        'efectivo' => 'Efectivo',
                    ])
                    ->native(false),
                    
                Tables\Filters\Filter::make('fecha_vencimiento')
                    ->form([
                        Forms\Components\DatePicker::make('vence_desde')
                            ->label('Vence desde'),
                        Forms\Components\DatePicker::make('vence_hasta')
                            ->label('Vence hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['vence_desde'],
                                fn (Builder $query, $date): Builder => $query->whereDate('fecha_vencimiento', '>=', $date),
                            )
                            ->when(
                                $data['vence_hasta'],
                                fn (Builder $query, $date): Builder => $query->whereDate('fecha_vencimiento', '<=', $date),
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
                        ->icon('heroicon-o-pencil')
                        ->visible(fn ($record) => $record->estado === 'pendiente'),
                  
                    Tables\Actions\Action::make('descargar_comprobante')
                        ->label('PDF')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->url(fn ($record) => $record->comprobante_pdf ? asset('storage/' . $record->comprobante_pdf) : null)
                        ->openUrlInNewTab()
                        ->visible(fn ($record) => !empty($record->comprobante_pdf)),
                ])
                ->button()
                ->color('info')
                ->label('Acciones'),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PagosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFacturas::route('/'),
            'view' => Pages\ViewFactura::route('/{record}'),
            'edit' => Pages\EditFactura::route('/{record}/edit'),
        ];
    }
}