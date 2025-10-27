<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

use App\Models\User;
use App\Mail\TicketCerrado;
use Illuminate\Support\Facades\Mail;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Asegurar que el user_id se establezca al usuario autenticado
        $data['user_id'] = auth()->id();

        if($data['titulo']==='Daños' ){
            $data['prioridad']= "Alta";
        }
        else{
            if($data['titulo']=== 'Problemas comunes' ){
                $data['prioridad']= "Media";
            }
        }

        if($data['tecnico_id']!=null && $data['estado'] === 'Cerrado' && $data['estado'] === 'Pendiente'){
            $data['estado']= "En Proceso";
        }
        //dd($data);
        
        return $data;
    }

    protected function afterCreate(): void
    {
        $ticket = $this->record;

        $recipient = User::where('rol','admin')->first();

        Notification::make()
            ->title("Nuevo Ticket Creado")
            ->body("Se ha creado un nuevo ticket: {$ticket->titulo}.")
            ->icon('heroicon-o-bell')
            ->color("primary")
            ->actions([
                \Filament\Notifications\Actions\Action::make('ver')
                    ->label('Ver Ticket')
                    ->url("/admin/tickets/{$ticket->id}")
                    ->button(),
            ])
            ->duration(5000)
            ->sendToDatabase($recipient);
        if($this->record->tecnico_id != null){

            //notificar por email 

            $tecnico = User::find($this->record->tecnico_id);

            $ticket = $this->record;

            $ticket->load('user');

            Mail::to($tecnico)->send(new TicketCerrado($ticket));

            Notification::make()
            ->title('Notificacion Tecnico')
            ->body('Se ha notificado por Email al tecnico asignado')
            ->icon('heroicon-o-bell')
            ->color("primary")
            ->duration(5000);
            
        }
    }
}
