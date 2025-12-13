#!/bin/bash

echo "Exécution des migrations Laravel..."
php artisan migrate --force

echo "Création ou mise à jour de l'administrateur..."
php artisan tinker --execute="
use App\Models\User;
User::updateOrCreate(
    ['email' => 'admin@creditapp.com'],
    [
        'name' => 'MOUHAMEDDIA',
        'email' => 'admin@creditapp.com',
        'phone' => '771167023',
        'password' => bcrypt('Admin@2025Fort!'),  // Change ce mot de passe en un très fort !
        'role' => 'admin',
        'status' => true,
    ]
);
echo 'Administrateur créé/mis à jour avec succès !';
"    



