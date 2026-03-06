<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'name' => 'Admin MERAKI',
            'email' => 'admin@meraki.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // Encargado
        $encargado = User::create([
            'name' => 'Encargado MERAKI',
            'email' => 'encargado@meraki.com',
            'password' => Hash::make('password'),
        ]);
        $encargado->assignRole('encargado');

        // Cliente de prueba
        $cliente = User::create([
            'name' => 'Cliente Demo',
            'email' => 'cliente@demo.com',
            'password' => Hash::make('password'),
        ]);
        $cliente->assignRole('cliente');
    }
}
