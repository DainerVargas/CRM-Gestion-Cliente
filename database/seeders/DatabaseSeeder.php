<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Administrador por defecto
        $admin = \App\Models\User::factory()->create([
            'name' => 'Administrador CRM',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Crear 15 clientes con llamadas
        \App\Models\Client::factory(15)->create([
            'user_id' => $admin->id
        ])->each(function ($client) use ($admin) {
            \App\Models\Call::factory(rand(2, 5))->create([
                'client_id' => $client->id,
                'user_id' => $admin->id
            ]);
        });
    }
}
