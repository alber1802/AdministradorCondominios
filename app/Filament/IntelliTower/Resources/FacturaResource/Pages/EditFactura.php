<?php

namespace App\Filament\IntelliTower\Resources\FacturaResource\Pages;

use App\Filament\IntelliTower\Resources\FacturaResource;
use App\Http\Controllers\Generate\GenerateController;
use App\Mail\FacturaMail;
use App\Models\Pago;
use App\Models\Factura;
use App\Models\User;
use App\Models\PagoCripto;
use App\Models\PagoEfectivo;
use App\Models\PagoTarjeta;
use App\Models\PagoTigoMoney;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class EditFactura extends EditRecord
{
    protected static string $resource = FacturaResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Cambiar automáticamente el estado de "pendiente" a "pagado" al abrir para edición
        if ($data['estado'] === 'pendiente') {
            $data['estado'] = 'pagado';
        }
        
        // Cargar datos existentes del pago si existe
        $factura = $this->record;
        if ($factura->pagos()->exists()) {
            $pago = $factura->pagos()->first();
            $data['tipo_pago'] = $pago->tipo_pago;
            
            // Cargar datos específicos según el tipo de pago
            switch ($pago->tipo_pago) {
                case 'tigo_money':
                    if ($pago->pagoTigoMoney) {
                        $data['numero_telefono'] = $pago->pagoTigoMoney->numero_telefono;
                        $data['referencia_transaccion'] = $pago->pagoTigoMoney->referencia_transaccion;
                    }
                    break;
                case 'tarjeta':
                    if ($pago->pagoTarjeta) {
                        $data['payment_token'] = $pago->pagoTarjeta->payment_token;
                    }
                    break;
                case 'cripto':
                    if ($pago->pagoCripto) {
                        $data['wallet_origen'] = $pago->pagoCripto->wallet_origen;
                        $data['wallet_destino'] = $pago->pagoCripto->wallet_destino;
                        $data['moneda'] = $pago->pagoCripto->moneda;
                        $data['hash_transaccion'] = $pago->pagoCripto->hash_transaccion;
                    }
                    break;
                case 'efectivo':
                    if ($pago->pagoEfectivo) {
                        $data['user_receptor_id'] = $pago->pagoEfectivo->user_id;
                        $data['observacion'] = $pago->pagoEfectivo->observacion;
                    }
                    break;
            }
        }
        
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Asegurar que el estado se mantenga como "pagado"
        $data['estado'] = 'pagado';
        
        return $data;
    }
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Solo actualizar el estado de la factura a "pagado"
        $record->update(['estado' => 'pagado']);

        // Si hay tipo de pago seleccionado, crear el pago correspondiente
       // Si el estado es pagado, crear el pago correspondiente
        if ($data['estado'] === 'pagado' && isset($data['tipo_pago'])) {
            $this->createPago($factura, $pagoData, $data);

            $recipient = $factura->user;

            Notification::make()
            ->title("Nueva Factura Creada")
            ->body("Se ha creado una nueva factura con estado {$factura->estado} para tu  departamento {$factura->departamento->numero} por un monto de {$factura->monto} y tipo {$factura->tipo}.")
            ->icon('heroicon-o-bell')
            ->color("primary")
            ->actions([
                \Filament\Notifications\Actions\Action::make('ver')
                    ->label('Ver Factura')
                    ->color('info')
                    ->url("/storage/{$factura->comprobante_pdf}")
                    ->button(),
            ])
            ->duration(5000)
            ->sendToDatabase($recipient);

            // para  el admin super_admin
        
            $recipient2 = User::where('role', 'super_admin')->first();

            Notification::make()
            ->title("Nueva Factura Creada")
            ->body("Se ha creado una nueva factura con estado {$factura->estado} para el  departamento {$factura->departamento->numero} por un monto de {$factura->monto} y tipo {$factura->tipo}.")
            ->icon('heroicon-o-bell')
            ->color("primary")
            ->actions([
                \Filament\Notifications\Actions\Action::make('ver')
                    ->label('Ver Factura')
                    ->color('info')
                    ->url("/storage/{$factura->comprobante_pdf}")
                    ->button(),
            ])
            ->duration(5000)
            ->sendToDatabase($recipient2);
           

        } else {
            
            $recipient = $factura->user;
            
            Notification::make()
            ->title("Nueva Factura Creada")
            ->body("Se ha creado una nueva factura con pago pendiente para tu departamento {$factura->departamento->numero} por un monto de {$factura->monto} y tipo {$factura->tipo}.")
            ->icon('heroicon-o-bell')
            ->color("success")
            ->actions([
                \Filament\Notifications\Actions\Action::make('ver')
                    ->label('Ver Factura')
                    ->color('info')
                    ->url("/intelliTower/facturas/{$factura->id}")
                    ->button(),
            ])
            ->duration(5000)
            ->sendToDatabase($recipient);
        }
        
        return $record;
    }

   


    protected function extractPagoData(array $data): array
    {
        $pagoData = [];
        
        if (isset($data['tipo_pago'])) {
            switch ($data['tipo_pago']) {
                case 'tigo_money':
                    // Generar referencia de transacción aleatoria de 10 caracteres alfanuméricos
                    $data['referencia_transaccion'] = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'), 0, 10);
                    $data['numero_telefono'] = substr(str_shuffle('0123456789'), 0, 8);

                    $pagoData = [
                        'numero_telefono' => $data['numero_telefono'] ?? null,
                        'referencia_transaccion' => $data['referencia_transaccion'] ?? null,
                        'qr' => $data['qr_code_upload'] ?? null,
                    ];
                    break;
                    
                case 'tarjeta':
                    // Generar token aleatorio de 12 caracteres alfanuméricos
                    $data['payment_token'] = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'), 0, 12);
                    
                    $pagoData = [
                        'payment_token' => $data['payment_token'] ?? null,
                    ];
                    break;
                    
                case 'cripto':
                    $pagoData = [
                        'wallet_origen' => $data['wallet_origen'] ?? null,
                        'wallet_destino' => $data['wallet_destino'] ?? null,
                        'moneda' => $data['moneda'] ?? null,
                        'hash_transaccion' => $data['hash_transaccion'] ?? null,
                    ];
                    break;
                    
                case 'efectivo':
                    $pagoData = [
                        'user_id' => $data['user_receptor_id'] ?? null,
                        'observacion' => $data['observacion'] ?? null,
                    ];
                    break;
            }
        }
        
        return $pagoData;
    }

    protected function createPago($factura, array $pagoData, array $allData): void
    {
        $existePago = Pago::where('factura_id', $factura->id)->first();

        $factura = Factura::find($factura->id);
        if(!$existePago){
                // Crear registro en tabla pagos
            $pago = Pago::create([
                'factura_id' => $factura->id,
                'tipo_pago' => $allData['tipo_pago'],
                'monto_pagado' => $factura['monto'],
                'fecha_pago' => now()->toDateString(),
            ]);

            // Crear registro en tabla específica según tipo de pago
            switch ($allData['tipo_pago']) {
                case 'tigo_money':
                    PagoTigoMoney::create([
                        'pago_id' => $pago->id,
                        'numero_telefono' => $pagoData['numero_telefono'],
                        'referencia_transaccion' => $pagoData['referencia_transaccion'],
                    ]);
                    break;
                    
                case 'tarjeta':
                    PagoTarjeta::create([
                        'pago_id' => $pago->id,
                        'payment_token' => $pagoData['payment_token'],
                    ]);
                    break;
                    
                case 'cripto':
                    PagoCripto::create([
                        'pago_id' => $pago->id,
                        'wallet_origen' => $pagoData['wallet_origen'],
                        'wallet_destino' => $pagoData['wallet_destino'],
                        'moneda' => $pagoData['moneda'],
                        'hash_transaccion' => $pagoData['hash_transaccion'],
                    ]);
                    break;
                    
                case 'efectivo':
                    PagoEfectivo::create([
                        'pago_id' => $pago->id,
                        'user_id' => $pagoData['user_id'],
                        'observacion' => $pagoData['observacion'],
                    ]);
                    break;
            }

            $controller = new GenerateController();
            $controller->generate(['id' => $factura->id]);

            // Recargar la factura con sus relaciones para el email
            $factura->refresh();
            $factura->load(['user', 'departamento']);

            // Enviar el correo
            try {
                Mail::to($factura->user->email)->send(new FacturaMail($factura));
                
                Notification::make()
                    ->title('Correo enviado')
                    ->success()
                    ->body('El correo de notificación ha sido enviado a ' . $factura->user->email)
                    ->send();
            } catch (\Exception $e) {
                Notification::make()
                    ->title('Error al enviar correo')
                    ->danger()
                    ->body('No se pudo enviar el correo: ' . $e->getMessage())
                    ->send();
            }

        }else{
            Notification::make()
                ->title('No se puede crear otro pago')
                ->warning()
                ->body('El pago Ya fue realizado.')
                ->send();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->visible(false), // Ocultar DeleteAction para usuarios
        ];
    }
}