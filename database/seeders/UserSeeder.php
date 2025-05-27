<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PNUser;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        PNUser::create([
            'user_id' => 'admin001',
            'user_fname' => 'Admin',
            'user_lname' => 'User',
            'user_mInitial' => 'A',
            'user_suffix' => null,
            'user_email' => 'admin@example.com',
            'user_password' => Hash::make('password123'),
            'user_role' => 'Admin',
            'status' => 'active',
            'is_temp_password' => false
        ]);
    }
}
