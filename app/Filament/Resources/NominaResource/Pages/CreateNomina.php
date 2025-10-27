<?php

namespace App\Filament\Resources\NominaResource\Pages;

use App\Http\Controllers\Generate\GenerateController;
use App\Filament\Resources\NominaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Mail\NominaMail;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

class CreateNomina extends CreateRecord
{
    protected static string $resource = NominaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $nomina = $this->record;
        $user = User::find($this->record->user_id);
        
        // Verificar si la nómina está pagada (asumiendo que tienes un campo estado)
        if($nomina->estado == 'Pagado'){
            
            // Generar el PDF
            $controller = new GenerateController();
            $controller->nomina_generate([
                'id' => $nomina->id,
                'user_id' => $nomina->user_id
            ]);

            // Recargar la nómina para obtener el path del PDF generado
            $nomina->refresh();
            $nomina->load('user');

            // Enviar email con el PDF adjunto
            Mail::to($user->email)->send(new NominaMail($nomina));

            $recipient = User::find($nomina->user_id);

            Notification::make()
                ->title("Nómina Pagada")
                ->body("Se ha cancelado tu nómina del mes de {$nomina->mes} por el monto de {$nomina->monto}.")
                ->icon('heroicon-o-bell')
                ->color("primary")
                ->actions([
                    \Filament\Notifications\Actions\Action::make('ver')
                        ->label('Ver Factura')
                        ->url("/storage/{$nomina->comprobante_pdf}")
                        ->color('info')
                        ->button(),
                ])
                ->duration(5000)
                ->sendToDatabase($recipient);
            
            $recipient2 = User::where('rol' , 'super_admin')->first();

             Notification::make()
                ->title("Nómina Pagada")
                ->body("Se ha pagado una nómina para {$nomina->user->name} por {$nomina->monto}.")
                ->icon('heroicon-o-bell')
                ->color("primary")
                ->actions([
                    \Filament\Notifications\Actions\Action::make('ver')
                        ->label('Ver Nómina')
                        ->url("/admin/nominas/{$nomina->id}")
                        ->button(),
                ])
                ->duration(5000)
                ->sendToDatabase($recipient2);        


        }else{

            $recipient = User::find($nomina->user_id);
             Notification::make()
                ->title("Nueva Nómina del mes de {$nomina->mes} ")
                ->body("Se a creado un nueva nomina pronto se notificara el pago correspondiente")
                ->icon('heroicon-o-bell')
                ->color("primary")
                ->actions([
                    \Filament\Notifications\Actions\Action::make('ver')
                        ->label('Ver Nómina')
                        ->url("/intelliTower/nominas/{$nomina->id}")
                        ->button(),
                ])
                ->duration(5000)
                ->sendToDatabase($recipient);
        }
        
    }

    
}
