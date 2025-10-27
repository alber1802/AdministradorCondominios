<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class NotificacionesPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-bell';
    
    protected static ?string $navigationLabel = 'Notificaciones';
    
    protected static ?string $title = 'Mis Notificaciones';

    protected static string $view = 'filament.pages.notificaciones-page';

    protected static ?int $navigationSort = 1;
    
    protected static ?string $navigationGroup = 'Configuracion';

    
    public function table(Table $table): Table
    {
            // Debug: verificar comentarios del usuario autenticado
            $comentariosCount = Auth::user()->notifications()->count();
            \Log::info("Comentarios del usuario: " . $comentariosCount);
        
        return $table
            ->query(
                Auth::user()->notifications()->getQuery()
            )
            ->columns([
                Tables\Columns\IconColumn::make('read_at')
                    ->label('Estado')
                    ->boolean()
                    ->trueIcon('heroicon-o-envelope-open')
                    ->falseIcon('heroicon-o-envelope')
                    ->trueColor('success')
                    ->falseColor('warning')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('data.title')
                    ->label('Título')
                    ->searchable()
                    ->weight('bold')
                    ->wrap(),

                Tables\Columns\TextColumn::make('data.body')
                    ->label('Mensaje')
                    ->searchable()
                    ->limit(50)
                    ->wrap(),

                Tables\Columns\BadgeColumn::make('type')
                    ->label('Tipo')
                    ->formatStateUsing(function ($state) {
                        return match($state) {
                            'App\\Notifications\\FacturaCreada' => 'Factura',
                            'App\\Notifications\\FacturaPagada' => 'Pago',
                            'App\\Notifications\\NominaPagada' => 'Nómina',
                            'App\\Notifications\\TicketCreado' => 'Ticket',
                            'App\\Notifications\\TicketActualizado' => 'Ticket',
                            'App\\Notifications\\ReservaCreada' => 'Reserva',
                            'App\\Notifications\\ConsumoCreado' => 'Consumo',
                            'App\\Notifications\\AnuncioCreado' => 'Anuncio',
                            'App\\Notifications\\ComentarioCreado' => 'Comentario',
                            default => 'General',
                        };
                    })
                    ->color(function ($state) {
                        return match($state) {
                            'App\\Notifications\\FacturaCreada' => 'info',
                            'App\\Notifications\\FacturaPagada' => 'success',
                            'App\\Notifications\\NominaPagada' => 'success',
                            'App\\Notifications\\TicketCreado' => 'warning',
                            'App\\Notifications\\TicketActualizado' => 'warning',
                            'App\\Notifications\\ReservaCreada' => 'primary',
                            'App\\Notifications\\ConsumoCreado' => 'gray',
                            'App\\Notifications\\AnuncioCreado' => 'secondary',
                            'App\\Notifications\\ComentarioCreado' => 'secondary',
                            default => 'gray',
                        };
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                Tables\Filters\Filter::make('no_leidas')
                    ->label('Solo no leídas')
                    ->query(fn (Builder $query): Builder => $query->whereNull('read_at'))
                    ->toggle(),

                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipo de Notificación')
                    ->options([
                        'App\\Notifications\\FacturaCreada' => 'Facturas',
                        'App\\Notifications\\FacturaPagada' => 'Pagos',
                        'App\\Notifications\\NominaPagada' => 'Nóminas',
                        'App\\Notifications\\TicketCreado' => 'Tickets',
                        'App\\Notifications\\TicketActualizado' => 'Tickets Actualizados',
                        'App\\Notifications\\ReservaCreada' => 'Reservas',
                        'App\\Notifications\\ConsumoCreado' => 'Consumos',
                        'App\\Notifications\\AnuncioCreado' => 'Anuncios',
                        'App\\Notifications\\ComentarioCreado' => 'Comentarios',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('marcar_leida')
                    ->label('Marcar como leída')
                    ->icon('heroicon-m-check')
                    ->color('success')
                    ->visible(fn ($record) => is_null($record->read_at))
                    ->action(function ($record) {
                        $record->markAsRead();
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Notificación marcada como leída')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('ir_a_recurso')
                    ->label('Ver Recurso')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->color('primary')
                    ->url(function ($record) {
                        $data = $record->data;
                        if (isset($data['actions'][0]['url'])) {
                            return $data['actions'][0]['url'];
                        }
                        return null;
                    })
                    ->visible(function ($record) {
                        $data = $record->data;
                        return isset($data['actions'][0]['url']);
                    })
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('marcar_todas_leidas')
                    ->label('Marcar como leídas')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->action(function ($records) {
                        foreach ($records as $record) {
                            $record->markAsRead();
                        }
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Notificaciones marcadas como leídas')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\DeleteBulkAction::make()
                    ->label('Eliminar seleccionadas'),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->paginated([10, 25, 50]);
    }

    public function getHeaderWidgets(): array
    {
        return [
            // \App\Filament\Widgets\NotificacionesWidget::class,
        ];
    }
}