<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Departamento;
use App\Models\AreaComun;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // // Create super_admin role if it doesn’t exist
        // if (!\Spatie\Permission\Models\Role::where('name', 'super_admin')->where('guard_name', 'web')->exists()) {
        //     \Spatie\Permission\Models\Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        // }
        // User::factory(10)->create();
        // Create super_admin user if it doesn’t exist
        if (!User::where('email', 'admin@admin.com')->exists()) {
            $superAdmin = User::factory()->create([
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'telefono' => '12345678',
                'rol' => 'super_admin',
                'password' => bcrypt('12345678'),
            ]);
            
            // // Asignar rol de super_admin con Filament Shield
            // $superAdmin->assignRole('super_admin');
        }
        // Create admin user if it doesn't exist
        if (!User::where('email', 'admin2@admin.com')->exists()) {
            $admin = User::factory()->create([
                'name' => 'admin2',
                'email' => 'admin2@admin.com',
                'telefono' => '87654321',
                'rol' => 'admin',
                'password' => bcrypt('12345678'),
            ]);
            
            // // Asignar rol de admin con Filament Shield (si existe)
            // if (\Spatie\Permission\Models\Role::where('name', 'admin')->exists()) {
            //     $admin->assignRole('admin');
            // }
        }

        // Create residente user if it doesn't exist
        if (!User::where('email', 'residente@admin.com')->exists()) {
            $residente = User::factory()->create([
                'name' => 'residente',
                'email' => 'residente@admin.com',
                'telefono' => '11223344',
                'rol' => 'residente',
                'password' => bcrypt('12345678'),
            ]);
            
            // // Asignar rol de residente con Filament Shield (si existe)
            // if (\Spatie\Permission\Models\Role::where('name', 'residente')->exists()) {
            //     $residente->assignRole('residente');
            // }
        }

        // Create invitado user if it doesn't exist
        if (!User::where('email', 'inquilino@admin.com')->exists()) {
            $inquilino = User::factory()->create([
                'name' => 'inquilino',
                'email' => 'inquilino@admin.com',
                'telefono' => '44332211',
                'rol' => 'inquilino',
                'password' => bcrypt('12345678'),
            ]);
            
            // // Asignar rol de inquilino con Filament Shield (si existe)
            // if (\Spatie\Permission\Models\Role::where('name', 'inquilino')->exists()) {
            //     $inquilino->assignRole('inquilino');
            // }
        }
        
        // Create departamento if it doesn't exist
        if (!Departamento::where('numero', '101')->exists()) {
            Departamento::factory()->create([
                'numero' => 'A101',
                'piso' => '1',
                'bloque' => 'A',
                'user_id' => User::inRandomOrder()->first()->id,
                'estado' => true,
            ]);
        }

        if (!Departamento::where('numero', '102')->exists()) {
            Departamento::factory()->create([
                'numero' => 'A102', 
                'piso' => '1',
                'bloque' => 'A',
                'user_id' => User::inRandomOrder()->first()->id,
                'estado' => true,
            ]);
        }

        if (!Departamento::where('numero', '201')->exists()) {
            Departamento::factory()->create([
                'numero' => 'B201',
                'piso' => '2',
                'bloque' => 'B',
                'user_id' => User::inRandomOrder()->first()->id,
                'estado' => true,
            ]);
        }

        if (!Departamento::where('numero', '202')->exists()) {
            Departamento::factory()->create([
                'numero' => 'B202',
                'piso' => '2',
                'bloque' => 'B',
                'user_id' => User::inRandomOrder()->first()->id,
                'estado' => true,
            ]);
        }

        
    }
}
