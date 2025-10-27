<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        $user = $this->record;
        $rol = $this->data['rol'] ?? null;
    
            // Asignar rol automáticamente basándose en el campo 'rol'
            if ($rol && in_array($rol, ['super_admin', 'residente', 'inquilino','admin'])) {
                // Mapear los roles del formulario a los roles del sistema

                $roleMap = [
                            'super_admin' => 'Super Admin',
                            'residente' => 'Residente',
                            'inquilino' => 'Inquilino',
                            'admin' => 'Admin',
                        ];

                $systemRole = $roleMap[$rol];
                
                // Crear el rol si no existe
                $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => $systemRole]);
                
                // Asignar el rol al usuario
                $user->assignRole($systemRole);

                // Notificación adicional sobre la asignación de rol
                // Notification::make()
                //     ->success()
                //     ->title('¡Rol asignado automáticamente!')
                //     ->body("El usuario ha sido asignado al rol: {$systemRole}")
                //     ->duration(3000)
                //     ->send();
            }
        
        
    }

}
