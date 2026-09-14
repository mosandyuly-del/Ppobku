<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $userData = [
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('Als220426'),
        ];

        if (Schema::hasColumn('users', 'role')) {
            $userData['role'] = 'admin';
        }

        User::updateOrCreate(['username' => 'admin'], $userData);
    }
}
