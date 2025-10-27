<?php

namespace App\Filament\Imports;

use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Hash;

class UserImporter extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label('Nombre')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('email')
                ->label('Email')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255'])
                ->unique(),
            ImportColumn::make('telefono')
                ->label('Teléfono')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('rol')
                ->label('Rol')
                ->requiredMapping()
                ->rules(['required', 'in:super_admin,residente,inquilino,admin']),
            ImportColumn::make('password')
                ->label('Contraseña')
                ->rules(['nullable', 'min:8']),
        ];
    }

    public function resolveRecord(): ?User
    {
        // Buscar usuario existente por email o crear uno nuevo
        $user = User::firstOrNew([
            'email' => $this->data['email'],
        ]);

        // Si es un usuario nuevo, asignar valores
        if (!$user->exists) {
            $user->name = $this->data['name'];
            $user->telefono = $this->data['telefono'] ?? null;
            $user->rol = $this->data['rol'];
            
            // Si no se proporciona contraseña, usar una por defecto
            $password = $this->data['password'] ?? 'password123';
            $user->password = Hash::make($password);
        }

        return $user;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'La importación de usuarios se ha completado y ' . number_format($import->successful_rows) . ' ' . str('fila')->plural($import->successful_rows) . ' importadas.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('fila')->plural($failedRowsCount) . ' fallaron al importar.';
        }

        return $body;
    }
}
