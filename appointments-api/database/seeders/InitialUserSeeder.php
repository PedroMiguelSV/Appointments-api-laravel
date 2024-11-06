<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialUserSeeder extends Seeder
{
    public function run()
    {
        $user = User::where('email', 'admin@example.com')->first();

        if (!$user) {
            User::create([
                'name' => 'Administrador',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
            ]);
        }
    }
}
