<?php

namespace App\Livewire;

use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Notifications\Notification;

class CustomProfileSection extends MyProfileComponent
{
    protected string $view = "livewire.custom-profile-section";
    
    public array $data = [];
    
    public function mount()
    {
        $this->data = [
            'telefono' => auth()->user()->telefono ?? '',
            'carnet_identidad' => auth()->user()->carnet_identidad ?? '',
            'rol' => auth()->user()->rol ?? '',
        ];
        
        $this->form->fill($this->data);
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('telefono')
                    ->label('Teléfono')
                    ->tel()
                    ->placeholder('+1 (555) 123-4567')
                    ->prefixIcon('heroicon-o-phone'),
               TextInput::make('carnet_identidad')
                    ->label('Carnet de Identidad(CI)')
                    ->prefixIcon('heroicon-o-identification')
                    ->maxLength(255),
                TextInput::make('rol')
                    ->label('rol Asignado')
                    ->disabled(),
            ])
            ->statePath('data');
    }
    
    public function submit()
    {
        $data = $this->form->getState();
        
        // Actualizar el usuario con los nuevos datos
        auth()->user()->update($data);
        
        Notification::make()
            ->title('Información actualizada')
            ->success()
            ->send();
    }
}
