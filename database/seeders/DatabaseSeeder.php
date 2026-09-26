<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrNew(['email' => 'mosandy@admin.com']);
        $user->name = 'Admin Mosandy';
        $user->password = Hash::make('password123');
        $user->save();
    }
}
