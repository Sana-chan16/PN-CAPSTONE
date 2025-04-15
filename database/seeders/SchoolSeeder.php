<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;

class SchoolSeeder extends Seeder
{
    public function run()
    {
        $schools = [
            ['school_name' => 'Philippine Normal University'],
            ['school_name' => 'University of the Philippines'],
            ['school_name' => 'De La Salle University'],
            ['school_name' => 'Ateneo de Manila University'],
            ['school_name' => 'University of Santo Tomas'],
        ];

        foreach ($schools as $school) {
            School::create($school);
        }
    }
} 