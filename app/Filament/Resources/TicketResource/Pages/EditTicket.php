<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

use App\Models\User;
use App\Mail\TicketCerrado;
use Illuminate\Support\Facades\Mail;

class EditTicket extends EditRecord
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
     protected function mutateFormDataBeforeSave(array $data): array
    {
        // Asegurar que el user_id no se pueda cambiar
        $data['user_id'] = auth()->id();
        
        //dd($data);
        if($data['titulo']==='Daños' ){
            $data['prioridad'] = "Alta";
        }
        else{
            if($data['titulo']=== 'Problemas comunes' ){
                $data['prioridad']= "Media";
            }
        }
        if($data['tecnico']!=null && $data['estado'] != 'Cerrado' && $data['estado'] != 'Pendiente'){
            $data['estado']= "En Proceso";
        }
       //
        
        
        return $data;
    }

     protected function afterSave(): void
    {
            $recipient = User::find($this->record->user_id);

        if($this->record->estado == 'Cerrado'){
            Notification::make()
                ->title("Ticket Cerrado")
                ->body("Su ticket '{$this->record->titulo}' ha sido cerrado. El problema ha sido resuelto satisfactoriamente.")
                ->icon('heroicon-o-check-circle')
                ->color("success")
                ->actions([
                    \Filament\Notifications\Actions\Action::make('ver')
                        ->label('Ver Ticket')
                        ->url("/intelliTower/tickets/{$this->record->id}")
                        ->button(),
                ])
                ->duration(5000)
                ->sendToDatabase($recipient);
        }
        if($this->record->estado == 'En Proceso'){
            Notification::make()
            ->title("Ticket en Proceso")
            ->body("Su ticket '{$this->record->titulo}' esta esta en proceso y esta siendo llevado por nuestro equipo técnico.")
            ->icon('heroicon-o-exclamation-circle')
            ->color("success")
                ->actions([
                    \Filament\Notifications\Actions\Action::make('ver')
                        ->label('Ver Ticket')
                        ->url("/intelliTower/tickets/{$this->record->id}")
                        ->button(),
                ])
                ->duration(5000)
                ->sendToDatabase($recipient);
        }
        
        if($this->record->tecnico_id != null){
            //notificar por email 
            $tecnico = User::find($this->record->tecnico_id);

            $ticket = $this->record;

            $ticket->load('user');

            Mail::to($tecnico)->send(new TicketCerrado($ticket));
        }
    }
}
