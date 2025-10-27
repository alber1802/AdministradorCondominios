<?php

namespace App\Filament\Widgets;

use App\Models\Pago;
use App\Models\User;
use Filament\Tables;
// importaciones usadas
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class FacturaPagos extends BaseWidget
{
    protected static ?string $heading = 'Pagos de Facturas Recientes';

    protected int|string|array $columnSpan = 'full';

     protected static ?int $sort = 30;

     public function getDisplayName(): string {  return "Tabla Facturas Con Pagos";   }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Pago::with(['factura.departamento', 'factura.user'])
                    ->orderBy('fecha_pago', 'desc')
                    ->limit(50) // Limitar para rendimiento
            )
            ->columns([
                Tables\Columns\TextColumn::make('fecha_pago')
                    ->label('Fecha de Pago')
                    ->date('d/m/Y')
                    ->sortable()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('monto_pagado')
                    ->label('Monto Pagado')
                    ->money('USD')
                    ->color('success')
                    ->weight('bold'),

                Tables\Columns\BadgeColumn::make('tipo_pago')
                    ->label('Tipo de Pago')
                    ->colors([
                        'primary' => 'tarjeta',
                        'success' => 'efectivo',
                        'warning' => 'tigo_money',
                        'danger' => 'cripto',
                        'info' => 'otro',
                    ])
                    ->icons([
                        'tarjeta' => 'heroicon-o-credit-card',
                        'efectivo' => 'heroicon-o-banknotes',
                        'tigo_money' => 'heroicon-o-device-phone-mobile',
                        'cripto' => 'heroicon-o-currency-dollar',
                    ]),

                Tables\Columns\TextColumn::make('factura.departamento.numero')
                    ->label('Departamento')
                    ->formatStateUsing(fn ($state, $record) => "Depto {$state} - Piso {$record->factura->departamento->piso} - Bloque {$record->factura->departamento->bloque}")
                    ->color('gray'),

                Tables\Columns\TextColumn::make('factura.user.name')
                    ->label('Usuario')
                    ->state(fn ($record) => $record->factura->user)
                    ->formatStateUsing(fn ($state) => "Usuario: {$state->name} - Email: {$state->email}")
                    ->color('info')
                    ->searchable(),

                Tables\Columns\TextColumn::make('factura.tipo')
                    ->label('Tipo Factura')
                    ->badge()
                    ->color('secondary'),

                Tables\Columns\BadgeColumn::make('factura.estado')
                    ->label('Estado Factura')
                    ->colors([
                        'success' => 'pagada',
                        'warning' => 'pendiente',
                        'danger' => 'vencida',
                        'gray' => 'cancelada',
                    ]),

                // Tables\Columns\TextColumn::make('factura.monto')
                //     ->label('Monto Factura')
                //     ->money('USD')
                //     ->color('warning'),

                Tables\Columns\TextColumn::make('factura.fecha_emision')
                    ->label('Fecha Emisión')
                    ->date('d/m/Y')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('factura.fecha_vencimiento')
                    ->label('Fecha Vencimiento')
                    ->date('d/m/Y')
                    ->color('danger')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipo_factura')
                    ->label('Tipo de Factura')
                    ->options([
                        'agua' => 'Agua',
                        'luz' => 'Luz',
                        'gas' => 'Gas',
                        'mantenimiento' => 'Mantenimiento',
                        'otro' => 'Otro',
                    ])
                    ->attribute('factura.tipo'),

                Tables\Filters\SelectFilter::make('estado_factura')
                    ->label('Estado de Factura')
                    ->options([
                        'pagada' => 'Pagada',
                        'pendiente' => 'Pendiente',
                        'vencida' => 'Vencida',
                        'cancelada' => 'Cancelada',
                    ])
                    ->attribute('factura.estado'),

                Tables\Filters\SelectFilter::make('usuario')
                    ->label('Usuario')
                    ->options(User::all()->pluck('name', 'id'))
                    ->attribute('factura.user_id'),

            ])
            ->defaultSort('fecha_pago', 'desc')
            ->striped()
            ->paginated([10, 25, 50])
            ->poll('30s'); // Actualizar cada 30 segundos
    }
}
