<?php

namespace App\Filament\IntelliTower\Resources\TicketResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TicketAdjuntosRelationManager extends RelationManager
{
    protected static string $relationship = 'ticketAdjuntos';

    protected static ?string $title = 'Archivos Adjuntos';

    protected static ?string $modelLabel = 'Archivo Adjunto';

    protected static ?string $pluralModelLabel = 'Archivos Adjuntos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Archivo')
                    ->description('Seleccione el tipo de archivo y súbalo')
                    ->icon('heroicon-o-document-arrow-up')
                    ->schema([
                        Forms\Components\Select::make('tipo')
                            ->label('Tipo de Archivo')
                            ->required()
                            ->live()
                            ->options([
                                'imagen' => 'Imagen',
                                'video' => 'Video',
                                'documento' => 'Documento',
                            ])
                            ->placeholder('Seleccione el tipo de archivo')
                            ->helperText('Elija el tipo de archivo que desea subir'),

                Forms\Components\FileUpload::make('archivo')
                            ->label('Archivo Adjunto')
                            ->required()
                            ->directory('tickets_adjuntos')
                    ->visibility('public')
                            ->live()
                            ->acceptedFileTypes(function (callable $get) {
                                $tipo = $get('tipo');
                                return match ($tipo) {
                                    'imagen' => ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
                                    'video' => ['video/mp4', 'video/avi', 'video/mov', 'video/wmv'],
                                    'documento' => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain'],
                                    default => []
                                };
                            })
                            ->maxSize(function (callable $get) {
                                $tipo = $get('tipo');
                                return match ($tipo) {
                                    'imagen' => 2048, // 2MB
                                    'video' => 51200, // 50MB
                                    'documento' => 10240, // 10MB
                                    default => 2048
                                };
                            })
                            ->placeholder(function (callable $get) {
                                $tipo = $get('tipo');
                                return match ($tipo) {
                                    'imagen' => 'Suba una imagen relacionada al ticket',
                                    'video' => 'Suba un video explicativo del problema',
                                    'documento' => 'Suba un documento con información adicional',
                                    default => 'Seleccione primero el tipo de archivo'
                                };
                            })
                            ->helperText(function (callable $get) {
                                $tipo = $get('tipo');
                                return match ($tipo) {
                                    'imagen' => 'Formatos: JPG, PNG, WEBP, GIF. Tamaño máximo: 2MB',
                                    'video' => 'Formatos: MP4, AVI, MOV, WMV. Tamaño máximo: 50MB',
                                    'documento' => 'Formatos: PDF, DOC, DOCX, TXT. Tamaño máximo: 10MB',
                                    default => 'Seleccione el tipo de archivo para ver los formatos permitidos'
                                };
                            })
                            ->imageEditor(function (callable $get) {
                                return $get('tipo') === 'imagen';
                            })
                            ->image(function (callable $get) {
                                return $get('tipo') === 'imagen';
                            }),
               
                     ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('archivo')
            ->description('Gestione los archivos adjuntos del ticket')
            ->columns([
                Tables\Columns\ImageColumn::make('archivo')
                    ->label('Vista Previa')
                    ->circular()
                    ->size(60)
                    ->visibility('public'),
                    

                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'imagen' => 'success',
                        'video' => 'warning',
                        'documento' => 'info',
                        default => 'gray'
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'imagen' => 'heroicon-o-photo',
                        'video' => 'heroicon-o-video-camera',
                        'documento' => 'heroicon-o-document-text',
                        default => 'heroicon-o-document'
                    }),

                Tables\Columns\TextColumn::make('archivo')
                    ->label('Nombre del Archivo')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->tooltip(function ($record) {
                        return $record->archivo;
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Subida')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo')
                    ->label('Filtrar por Tipo')
                    ->options([
                        'imagen' => 'Imágenes',
                        'video' => 'Videos',
                        'documento' => 'Documentos',
                    ])
                    ->multiple(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Subir Archivo')
                    ->icon('heroicon-o-plus-circle')
                    ->modalHeading('Subir Nuevo Archivo Adjunto')
                    ->modalDescription('Agregue un archivo relacionado con este ticket')
                    ->modalWidth('lg'),
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->label('Descargar')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn ($record) => asset('storage/' . $record->archivo))
                    ->openUrlInNewTab(),

                Tables\Actions\ViewAction::make()
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->modalWidth('lg'),

                Tables\Actions\EditAction::make()
                    ->label('Editar')
                    ->icon('heroicon-o-pencil-square')
                    ->modalHeading('Editar Archivo Adjunto')
                    ->modalWidth('lg'),

                Tables\Actions\DeleteAction::make()
                    ->label('Eliminar')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->modalHeading('Eliminar Archivo')
                    ->modalDescription('¿Está seguro de que desea eliminar este archivo? Esta acción no se puede deshacer.')
                    ->modalSubmitActionLabel('Sí, eliminar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Eliminar Seleccionados')
                        ->requiresConfirmation()
                        ->modalHeading('Eliminar Archivos Seleccionados')
                        ->modalDescription('¿Está seguro de que desea eliminar los archivos seleccionados? Esta acción no se puede deshacer.')
                        ->modalSubmitActionLabel('Sí, eliminar todos'),
                ]),
            ])
            ->emptyStateHeading('No hay archivos adjuntos')
            ->emptyStateDescription('Comience subiendo el primer archivo relacionado con este ticket.')
            ->emptyStateIcon('heroicon-o-document-plus')
            ->defaultSort('created_at', 'desc');
}
}