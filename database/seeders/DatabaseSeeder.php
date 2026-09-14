<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@gmail.com')->first();
        if (!$admin) {
            $admin = new User();
            $admin->email = 'admin@gmail.com';
        }

        $admin->name = 'Administrator PPOB';
        $admin->password = Hash::make('Als220426');

        if (Schema::hasColumn('users', 'username')) $admin->username = 'admin';
        if (Schema::hasColumn('users', 'role')) $admin->role = 'admin';
        if (Schema::hasColumn('users', 'level')) $admin->level = 'admin';
        if (Schema::hasColumn('users', 'is_admin')) $admin->is_admin = 1;
        if (Schema::hasColumn('users', 'status')) $admin->status = 'active';

        $admin->save();
    }
}
