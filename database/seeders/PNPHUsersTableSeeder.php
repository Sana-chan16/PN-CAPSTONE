<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PNPHUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pnph_users')->insert([
            'user_id' => 'admin001',
            'user_fname' => 'Admin',
            'user_lname' => 'User',
            'user_mInitial' => 'A',
            'user_suffix' => '',
            'user_email' => 'admin@example.com',
            'user_role' => 'Admin',
            'user_password' => Hash::make('password123'),
            'status' => 'active',
            'is_temp_password' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
