<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@creditapp.test'],
            [
                'name' => 'Super Admin',
                'phone' => '0000000000',
                'password' => Hash::make('password123'), // change en production
                'role' => 'admin',
                'status' => true,
            ]
        );
    }
}
