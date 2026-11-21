<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@papeleria.com'],
            [
                'name' => 'Admin',
                'apellido' => 'Sistema',
                'rol' => 'Administrador',
                'codigo_empleado' => '00001',
                'telefono' => '5500000000',
                'direccion' => 'Sucursal Principal',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
    }
}