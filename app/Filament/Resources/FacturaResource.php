<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FacturaResource\Pages;
use App\Filament\Resources\FacturaResource\RelationManagers;
use app\Filament\Resources\FacturaResource\RelationManagers\PagosRelationManager;
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
    
    protected static ?string $navigationLabel = 'Facturas';
    
    protected static ?string $modelLabel = 'Factura';
    
    protected static ?string $pluralModelLabel = 'Facturas';
    protected static ?string $navigationGroup = 'Finanzas';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
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
                                        ->live(),
                                        
                                    Forms\Components\Select::make('departamento_id')
                                        ->label('Departamento')
                                        ->options(function (callable $get) {
                                            $userId = $get('user_id');
                                            if (!$userId) {
                                                return [];
                                            }
                                            return Departamento::where('user_id', $userId)->pluck('numero', 'id');
                                        })
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->prefixIcon('heroicon-o-building-office')
                                        ->disabled(fn (callable $get) => !$get('user_id')),
                                        
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
                                        ->live()
                                        ->afterStateUpdated(function ($state, callable $set) {
                                            // Establecer monto según el tipo
                                            $montos = [
                                                'mantenimiento' => 500,
                                                'expensas' => 800,
                                                'alquiler' => 1200,
                                                'servicios' => 300,
                                            ];
                                            
                                            if (isset($montos[$state])) {
                                                $set('monto', $montos[$state]);
                                            }
                                            
                                            // Establecer descripción según el tipo
                                            $descripciones = [
                                                'mantenimiento' => '<p><strong>Mantenimiento mensual que incluye:</strong></p><ul><li>Mantenimiento de ascensores</li><li>Mantenimiento de bombas de agua</li><li>Mantenimiento de cámaras de seguridad</li><li>Mantenimiento de garaje y portones</li><li>Revisión de instalaciones eléctricas</li></ul>',
                                                'expensas' => '<p><strong>Expensas mensuales que cubren:</strong></p><ul><li>Productos de limpieza</li><li>Pago a trabajadores de mantenimiento</li><li>Servicios de seguridad</li><li>Gastos administrativos</li><li>Consumo de áreas comunes</li></ul>',
                                                'alquiler' => '<p><strong>Alquiler mensual del departamento</strong></p><p>Incluye el uso del inmueble y acceso a todas las áreas comunes del edificio.</p>',
                                                'servicios' => '<p><strong>Servicios básicos mensuales:</strong></p><ul><li>Agua</li><li>Luz de áreas comunes</li><li>Internet del edificio</li><li>Recolección de basura</li></ul>',
                                            ];
                                            
                                            if (isset($descripciones[$state])) {
                                                $set('descripcion', $descripciones[$state]);
                                            } else {
                                                $set('descripcion', '');
                                            }
                                        }),
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
                                        ->disabled(fn (callable $get) => in_array($get('tipo'), ['mantenimiento', 'expensas', 'alquiler', 'servicios']))
                                        ->dehydrated(true)
                                        ->helperText(fn (callable $get) => match($get('tipo')) {
                                            'mantenimiento' => 'Monto fijo: $500',
                                            'expensas' => 'Monto fijo: $800',
                                            'alquiler' => 'Monto fijo: $1,200',
                                            'servicios' => 'Monto fijo: $300',
                                            default => 'Ingrese el monto correspondiente'
                                        }),
                                        
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
                                        ->live(),
                                        
                                    Forms\Components\Select::make('tipo_pago')
                                        ->label('Tipo de Pago')
                                        ->options([
                                            'tigo_money' => 'Tigo Money',
                                            'tarjeta' => 'Tarjeta',
                                            'cripto' => 'Criptomoneda',
                                            'efectivo' => 'Efectivo',
                                        ])
                                        ->native(false)
                                        
                                        ->prefixIcon('heroicon-o-credit-card')
                                        ->visible(fn (callable $get) => $get('estado') === 'pagado')
                                        ->live(),
                                ])
                                ->columns(3),
                                
                            Forms\Components\Section::make('Fechas')
                                ->icon('heroicon-o-calendar-days')
                                ->schema([
                                    Forms\Components\DatePicker::make('fecha_emision')
                                        ->label('Fecha de Emisión')
                                        ->native(false)
                                        ->minDate(now())
                                        ->required()
                                        ->default(now())
                                        ->prefixIcon('heroicon-o-calendar'),
                                        
                                    Forms\Components\DatePicker::make('fecha_vencimiento')
                                        ->label('Fecha de Vencimiento')
                                        ->minDate(now())
                                        ->maxDate(now()->addDays(30))
                                        ->required()
                                        ->native(false)
                                        ->prefixIcon('heroicon-o-clock'),
                                ])
                                ->columns(2),
                                
                            Forms\Components\Section::make('Información Adicional')
                                ->icon('heroicon-o-document-text')
                                ->schema([
                                    Forms\Components\RichEditor::make('descripcion')
                                        ->label('Descripción')
                                        ->toolbarButtons([
                                            'bold',
                                            'italic',
                                            'underline',
                                            'bulletList',
                                            'orderedList',
                                            'h2',
                                            'h3',
                                        ])
                                        ->columnSpanFull()
                                        ->helperText('Puede editar y personalizar la descripción según sea necesario'),
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
                                        ->label('Código QR Actual')
                                        ->view('filament.components.qr-display')
                                        ->viewData(fn ($record) => [
                                            'qr_code' => $record?->qr_code ?? 'mis_imagenes/icono-codigo-qr.jpg'
                                        ])
                                        ->helperText('Este es el código QR actual de la factura'),
                                        
                                    Forms\Components\Toggle::make('actualizar_qr')
                                        ->label('Actualizar Código QR')
                                        ->helperText('Active esta opción para subir un nuevo código QR')
                                        ->live()
                                        ->default(false),
                                        
                                    Forms\Components\FileUpload::make('qr_code_upload')
                                        ->label('Subir Nuevo QR')
                                        ->image()
                                        ->imageEditor()
                                        ->directory('qr_codes')
                                        ->visibility('public')
                                        ->maxSize(2048)
                                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                        ->placeholder('Suba un nuevo código QR')
                                        ->helperText('Formatos permitidos: JPG, PNG, WEBP. Tamaño máximo: 2MB')
                                        ->visible(fn (callable $get) => $get('actualizar_qr') === true),
                                ])
                                ->columns(2),
                        ]),
                        
                    // Tab para Pago con Tarjeta
                    Tabs\Tab::make('Pago Tarjeta')
                        ->icon('heroicon-o-credit-card')
                        ->visible(fn (callable $get) => $get('estado') === 'pagado' && $get('tipo_pago') === 'tarjeta')
                        ->schema([
                            Forms\Components\Section::make('Información de Pago con Tarjeta')
                                ->icon('heroicon-o-credit-card')
                                ->schema([
                                    Forms\Components\Card::make()
                                    ->schema([
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('numero_tarjeta')
                                                    ->label('Número de Tarjeta')
                                        ->required()
                                                    ->mask('9999 9999 9999 9999')
                                                    ->placeholder('1234 5678 9012 3456')
                                                    ->prefixIcon('heroicon-o-credit-card'),
                                                Forms\Components\TextInput::make('cvv')
                                                    ->label('CVV/CVC')
                                        ->required()
                                                    ->mask('999')
                                                    ->maxLength(4)
                                                    ->placeholder('123')
                                                    ->prefixIcon('heroicon-o-shield-check'),
                                            ]),
                                        Forms\Components\Grid::make(2)
                                            ->schema([
                                                Forms\Components\DatePicker::make('fecha_vencimiento_tarjeta')
                                                    ->label('Fecha de Vencimiento')
                                                    ->required()
                                                    ->native(false)
                                                    ->displayFormat('m/Y')
                                                    ->prefixIcon('heroicon-o-calendar'),
                                        
                                                Forms\Components\TextInput::make('banco')
                                                    ->label('Banco Emisor')
                                        ->required()
                                                    ->placeholder('Ej: Banco Nacional')
                                                    ->prefixIcon('heroicon-o-building-library'),
                                            ]),
                                    
                                        Forms\Components\TextInput::make('nombre_titular')
                                            ->label('Nombre del Titular')
                                            ->required()
                                            ->placeholder('Como aparece en la tarjeta')
                                            ->prefixIcon('heroicon-o-user')
                                            ->columnSpanFull(),
                                    ]),
                                    
                                ])
                                ->columns(1),
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
                                        ->required()
                                        ->prefixIcon('heroicon-o-wallet'),
                                        
                                    Forms\Components\TextInput::make('wallet_destino')
                                        ->label('Wallet Destino')
                                        ->default('qwe13wqe123200309231w13ldnuha')
                                        ->required()
                                        ->prefixIcon('heroicon-o-wallet'),
                                        
                                    Forms\Components\Select::make('moneda')
                                        ->label('Moneda')
                                        ->options([
                                            'BTC' => 'Bitcoin (BTC)',
                                            'ETH' => 'Ethereum (ETH)',
                                            'USDT' => 'Tether (USDT)',
                                            'USDC' => 'USD Coin (USDC)',
                                            'BNB' => 'Binance Coin (BNB)',
                                        ])
                                        ->required()
                                        ->native(false)
                                        ->prefixIcon('heroicon-o-banknotes'),
                                        
                                    Forms\Components\TextInput::make('hash_transaccion')
                                        ->label('Hash de Transacción')
                                        ->required()
                                        ->prefixIcon('heroicon-o-hashtag')
                                        ->columnSpanFull(),
                                ])
                                ->columns(3),
                        ]),
                        
                    // Tab para Pago en Efectivo
                    Tabs\Tab::make('Pago Efectivo')
                        ->icon('heroicon-o-banknotes')
                        ->visible(fn (callable $get) => $get('estado') === 'pagado' && $get('tipo_pago') === 'efectivo')
                        ->schema([
                            Forms\Components\Section::make('Información de Pago en Efectivo')
                                ->icon('heroicon-o-banknotes')
                                ->schema([
                                    Forms\Components\Select::make('user_receptor_id')
                                        ->label('Usuario que Recibió el Pago')
                                        ->options(User::all()->pluck('name', 'id'))
                                        ->searchable()
                                        ->preload()
                                        ->required()
                                        ->prefixIcon('heroicon-o-user'),
                                        
                                    Forms\Components\Textarea::make('observacion')
                                        ->label('Observación')
                                        ->rows(3)
                                        ->columnSpanFull(),
                                ])
                                ->columns(1),
                        ]),
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
                    
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuario')
                    ->description(fn ($record) => $record->user->email ?? '')
                    ->sortable()
                    ->searchable()
                    ->weight('medium')
                    ->icon('heroicon-o-user'),
                    
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
                    
                Tables\Columns\TextColumn::make('pagos.tipo_pago')
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
                        ->visible(fn ($record) => $record->fecha_vencimiento >= now()->toDateString()),
                  
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
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make(),
            //     ]),
            // ])
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

    public static function getWidgets(): array
    {
        return [
             FacturaResource\Widgets\FacturaStatsWidget::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFacturas::route('/'),
            'create' => Pages\CreateFactura::route('/create'),
            'view' => Pages\ViewFactura::route('/{record}'),
            'edit' => Pages\EditFactura::route('/{record}/edit'),
        ];
    }
}