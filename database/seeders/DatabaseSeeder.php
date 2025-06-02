<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            // Uncomment the line below to clear all student data
            // ClearStudentDataSeeder::class,
            // Uncomment the line below to add dummy grades and proofs
            // DummyGradesAndProofsSeeder::class,
        ]);
    }
}
