<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Récupère l'email et le mot de passe depuis le fichier .env
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        // Vérifie que l'email et le mot de passe sont définis
        if (!$email || !$password) {
            // Ne pas créer l'admin si ces valeurs ne sont pas définies
            $this->command->error('ADMIN_EMAIL et ADMIN_PASSWORD doivent être définis dans le fichier .env.');
            return;
        }

        User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'MOUHAMEDDIA',
                'phone' => '771167023', // modifier si nécessaire
                'password' => Hash::make($password),
                'role' => 'admin',
                'status' => true,
            ]
        );

        $this->command->info('Compte administrateur créé ou mis à jour avec succès.');
    }
}
