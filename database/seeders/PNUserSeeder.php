<?php

namespace Database\Seeders;

use App\Models\PNUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PNUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin account
        PNUser::create([
            'user_id' => 'ADMIN002',
            'user_fname' => 'Admin',
            'user_lname' => 'User',
            'user_mInitial' => null,
            'user_suffix' => null,
            'user_email' => 'admin2@example.com',
            'user_role' => 'Admin',
            'user_password' => Hash::make('admin123'),
            'status' => 'active',
            'is_temp_password' => false
        ]);

        // Create Training account
        PNUser::create([
            'user_id' => 'TRAIN002',
            'user_fname' => 'Training',
            'user_lname' => 'User',
            'user_mInitial' => null,
            'user_suffix' => null,
            'user_email' => 'training2@example.com',
            'user_role' => 'Training',
            'user_password' => Hash::make('training123'),
            'status' => 'active',
            'is_temp_password' => false
        ]);

        // Create Educator account
        PNUser::create([
            'user_id' => 'EDUC002',
            'user_fname' => 'Educator',
            'user_lname' => 'User',
            'user_mInitial' => null,
            'user_suffix' => null,
            'user_email' => 'educator2@example.com',
            'user_role' => 'Educator',
            'user_password' => Hash::make('educator123'),
            'status' => 'active',
            'is_temp_password' => false
        ]);

        // Create Student account
        PNUser::create([
            'user_id' => 'STUD002',
            'user_fname' => 'Student',
            'user_lname' => 'User',
            'user_mInitial' => null,
            'user_suffix' => null,
            'user_email' => 'student2@example.com',
            'user_role' => 'Student',
            'user_password' => Hash::make('student123'),
            'status' => 'active',
            'is_temp_password' => false
        ]);
    }
} 