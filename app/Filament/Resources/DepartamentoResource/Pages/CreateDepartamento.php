<?php

namespace App\Filament\Resources\DepartamentoResource\Pages;

use App\Filament\Resources\DepartamentoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDepartamento extends CreateRecord
{
    protected static string $resource = DepartamentoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function beforeCreate(): void
    {
        // Verificar si ya existe un departamento con el mismo número, piso, bloque y user_id
        $data = $this->form->getState();
        
        if (isset($data['user_id']) && $data['user_id']) {
            $existingDepartamento = \App\Models\Departamento::where('numero', $data['numero'])
                ->where('piso', $data['piso'])
                ->where('bloque', $data['bloque'])
                ->where('user_id', $data['user_id'])
                ->first();
            
            if ($existingDepartamento) {
                \Filament\Notifications\Notification::make()
                    ->title('Error')
                    ->body('Este usuario ya tiene asignado este departamento.')
                    ->danger()
                    ->send();
                
                $this->halt();
            }
        }
    }
}
