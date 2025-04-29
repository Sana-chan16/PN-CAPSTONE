<?php

namespace Database\Seeders;

use App\Models\PNUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PNUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        PNUser::create([
            'user_id' => 'ADMIN123',
            'user_fname' => 'Admin',
            'user_lname' => 'User',
            'user_mInitial' => 'A',
            'user_suffix' => '',
            'user_email' => 'admin@admin.com',
            'user_role' => 'Admin',
            'user_password' => Hash::make('password123'),
            'status' => 'active',
            'is_temp_password' => false
        ]);
    }
}
