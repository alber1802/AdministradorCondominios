<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\RelationManagers\DepartamentosRelationManager;

use App\Filament\Resources\UserResource\RelationManagers\NominasRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\PropietarioRelationManager;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

//adicciones de import
use App\Filament\Imports\UserImporter;
use HayderHatem\FilamentExcelImport\Actions\FullImportAction;
use HayderHatem\FilamentExcelImport\Actions\Concerns\CanImportExcelRecords;


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = 'Usuarios';

    protected static ?string $modelLabel = 'Usuario';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {   
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Usuario')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->maxLength(255)
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(User::class, 'email', ignoreRecord: true),
                        Forms\Components\TextInput::make('telefono')
                            ->tel(),
                        Forms\Components\TextInput::make('carnet_identidad')
                            ->label('Carnet de Identidad(CI)')
                            ->placeholder('Ingrese el número de carnet')
                            ->prefixIcon('heroicon-o-identification')
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('password')
                            ->label('Contraseña')
                            ->password()
                            ->minLength(8)
                            ->placeholder('Ingrese la contraseña')
                            ->prefixIcon('heroicon-o-lock-closed')
                            ->visible(fn ($operation) => $operation === 'create')
                            ->dehydrated(fn ($operation) => $operation === 'create')
                            ->required(fn ($operation) => $operation === 'create')
                            ->columnSpan(1),
                        Forms\Components\Select::make('rol')
                            ->default('inquilino')
                            ->options([
                                'super_admin' => 'Super Admin',
                                'residente' => 'Residente',
                                'inquilino' => 'Inquilino',
                                'admin' => 'Admin',
                            ])
                            ->prefixIcon('heroicon-o-shield-check')
                            ->required(),
                        Forms\Components\Toggle::make('is_blocked')
                            ->label('Usuario Bloqueado')
                            ->helperText('Activar para desbloquear el acceso del usuario al sistema')
                            ->default(false)
                            ->inline(false)
                            ->onIcon('heroicon-m-lock-closed')
                            ->offIcon('heroicon-m-lock-open')
                            ->onColor('danger')
                            ->offColor('success')
                            ->columnSpan(1),
                        Forms\Components\Toggle::make('force_renew_password')
                            ->label('Forzar Renovación de Contraseña')
                            ->helperText('El usuario deberá cambiar su contraseña en el próximo inicio de sesión')
                            ->default(true)
                            ->inline(false)
                            ->onIcon('heroicon-m-key')
                            ->offIcon('heroicon-m-lock-open')
                            ->onColor('warning')
                            ->offColor('gray')
                            ->columnSpan(1),

                        Forms\Components\FileUpload::make('avatar_url')
                            ->label('Foto de Perfil')
                            ->image()
                            ->imageEditor()->directory('foto_perfil')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->placeholder('Suba una foto de perfil')
                            ->helperText('Formatos permitidos: JPG, PNG, WEBP. Tamaño máximo: 2MB'),
                    ])->columns(2),
                
                
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->rowIndex()
                    ->weight('medium')
                    ->alignCenter(),
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->label('Foto')
                    ->circular()
                    // ->defaultImageUrl('/Imagenes/icono_default.jpg')
                    ->size(60)
                    ->visibility('private')
                    ->visible(fn ($record) => !empty($record->avatar_url)),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->description(fn($record) => $record->email)
                    ->searchable(),
                 Tables\Columns\TextColumn::make('carnet_identidad')
                    ->label('Carnet de Identidad')
                    ->searchable()
                    ->formatStateUsing(fn ($state) => $state ?: 'No definido'),
                Tables\Columns\TextColumn::make('telefono')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rol')
                    ->label('Rol')
                    ->badge()
                    ->searchable()
                    ->color(fn (string $state): string => match ($state) {
                        'super_admin' => 'danger',
                        'residente' => 'success',
                        'inquilino' => 'warning',
                        'admin' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                
                Tables\Columns\IconColumn::make('is_blocked')
                    ->label('Bloqueado')
                    ->boolean()
                    ->trueIcon('heroicon-o-lock-closed')
                    ->falseIcon('heroicon-o-lock-open')
                    ->trueColor('danger')
                    ->falseColor('success')
                    ->tooltip(fn ($record) => $record->is_blocked ? 'Usuario Bloqueado' : 'Usuario Desbloqueado'),

                Tables\Columns\TextColumn::make('last_renew_password_at')
                    ->label('Última Renovación')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Filters\SelectFilter::make('rol')
                    ->label('Filtrar por Rol')
                    ->options([
                        'super_admin' => 'Super Admin',
                        'residente' => 'Residente',
                        'inquilino' => 'Inquilino',
                        'admin' => 'Admin',
                    ])
                    ->placeholder('Todos los roles'),
                Tables\Filters\SelectFilter::make('is_blocked')
                    ->label('Filtrar por Estado ')
                    ->options([
                        '0' => 'Desbloqueado',
                        '1' => 'Bloqueado',
                    ])
                    ->placeholder('Todos los estados'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('cambiar_rol')
                        ->label('Cambiar Rol')
                        ->icon('heroicon-o-shield-check')
                        ->form([
                            Forms\Components\Select::make('rol')
                                ->label('Nuevo Rol')
                                ->options([
                                    'super_admin' => 'Super Admin',
                                    'residente' => 'Residente',
                                    'inquilino' => 'Inquilino',
                                    'admin' => 'Admin',])
                                ->prefixIcon('heroicon-o-shield-check')
                                ->default(fn ($record) => $record->rol)
                                ->required(),
                        ])

                        ->action(function (array $data, $record) {
                            $record->update(['rol' => $data['rol']]);
                        })
                        ->color('warning')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Rol Actualizada correctamente')
                                ->duration(5000)
                        ),
                    Tables\Actions\Action::make('cambiar_password')
                        ->label('Cambiar Contraseña')
                        ->icon('heroicon-o-lock-closed')
                        ->form([
                            Forms\Components\TextInput::make('password')
                                ->label('Nueva Contraseña')
                                ->password()
                                ->minLength(8)
                                ->required(),
                        ])
                        ->action(function (array $data, $record) {
                            $record->update(['password' => bcrypt($data['password'])]);
                        })
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('¡Contraseña Actualizada correctamente')
                                ->duration(5000)
                        )
                        ->color('info'),
                    Tables\Actions\DeleteAction::make(),
                ])
                ->button()
                ,

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
            RelationManagers\DepartamentosRelationManager::class,
            RelationManagers\NominasRelationManager::class,
            RelationManagers\PropietarioRelationManager::class,
            
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            
        ];
    }
    
}
