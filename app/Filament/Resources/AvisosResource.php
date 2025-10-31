<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\Facades\FilamentColor;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Guava\FilamentIconPicker\Forms\IconPicker;
use Rupadana\FilamentAnnounce\Models\Announcement;
use App\Filament\Resources\AvisosResource\Pages;

class AvisosResource extends Resource
{
    protected static ?string $model = Announcement::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationLabel = 'Avisos';

    protected static ?string $modelLabel = 'Aviso';

    protected static ?string $pluralModelLabel = 'Avisos';

    protected static ?string $navigationGroup = 'Configuracion';

    protected static ?int $navigationSort = 3;

    public static function canAccess(): bool
    {
          return auth()->user()->rol == 'super_admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información del Aviso')
                    ->description('Configura los detalles principales del aviso')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre Interno')
                            ->helperText('Nombre para identificar el aviso internamente')
                            ->minLength(5)
                            ->maxLength(255)
                            ->required()
                            ->placeholder('Ej: Aviso de Mantenimiento Enero 2025'),

                        TextInput::make('title')
                            ->label('Título del Aviso')
                            ->helperText('Título que verán los usuarios')
                            ->minLength(5)
                            ->maxLength(255)
                            ->required()
                            ->placeholder('Ej: ¡Atención Importante!'),

                        Textarea::make('body')
                            ->label('Mensaje')
                            ->helperText('Contenido completo del aviso')
                            ->minLength(20)
                            ->rows(4)
                            ->required()
                            ->placeholder('Escribe aquí el mensaje que deseas comunicar...'),
                    ])
                    ->columns(1)
                    ->collapsible(),

                Section::make('Apariencia')
                    ->description('Personaliza cómo se verá el aviso')
                    ->schema([
                        IconPicker::make('icon')
                            ->label('Icono')
                            ->helperText('Selecciona un icono para el aviso')
                            ->default('heroicon-o-megaphone')
                            ->columnSpanFull(),

                        Select::make('color')
                            ->label('Color')
                            ->helperText('Elige el color del aviso')
                            ->options([
                                ...collect(FilamentColor::getColors())->map(fn ($value, $key) => ucfirst($key))->toArray(),
                                'custom' => 'Personalizado',
                            ])
                            ->default('primary')
                            ->live()
                            ->required(),

                        ColorPicker::make('custom_color')
                            ->label('Color Personalizado')
                            ->helperText('Define un color RGB personalizado')
                            ->hidden(fn (Get $get) => $get('color') != 'custom')
                            ->requiredIf('color', 'custom')
                            ->rgb(),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('Destinatarios')
                    ->description('Selecciona quién verá este aviso')
                    ->schema([
                        Select::make('users')
                            ->label('Usuarios')
                            ->helperText('Selecciona "Todos" o usuarios específicos')
                            ->options(['all' => '✓ Todos los usuarios'] + User::all()->pluck('name', 'id')->toArray())
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->default(['all'])
                            ->required(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                TextColumn::make('body')
                    ->label('Mensaje')
                    ->limit(50)
                    ->wrap()
                    ->searchable(),

                IconColumn::make('icon')
                    ->label('Icono'),

                TextColumn::make('color')
                    ->label('Color')
                    ->badge()
                    ->formatStateUsing(fn ($state) => ucfirst($state)),

                TextColumn::make('users')
                    ->label('Destinatarios')
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        if (is_array($state) && in_array('all', $state)) {
                            return 'Todos';
                        }
                        return is_array($state) ? count($state) . ' usuarios' : '0 usuarios';
                    })
                    ->color('success'),

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Ver')
                    ->button()
                    ->color('info'),
                Tables\Actions\DeleteAction::make()
                    ->label('Eliminar')
                    ->button()
                    ->color('danger'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Eliminar seleccionados'),
                ]),
            ])
            ->emptyStateHeading('No hay avisos')
            ->emptyStateDescription('Crea tu primer aviso para comunicarte con los usuarios')
            ->emptyStateIcon('heroicon-o-megaphone');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAvisos::route('/'),
            'create' => Pages\CreateAvisos::route('/create'),
            'view' => Pages\ViewAvisos::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
