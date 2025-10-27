<?php

namespace App\Filament\IntelliTower\Resources\TicketResource\Pages;

use App\Filament\IntelliTower\Resources\TicketResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;

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
        
    }
}