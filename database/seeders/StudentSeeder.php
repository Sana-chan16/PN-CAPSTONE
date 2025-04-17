<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\SchoolClass;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run()
    {
        $student = Student::create([
            'first_name' => 'Test',
            'last_name' => 'Student',
            'email' => 'test.student@example.com',
            'school_id' => 1, // Make sure this school_id exists
            'student_id' => 'STU001',
            'date_of_birth' => '2000-01-01',
            'gender' => 'Male',
            'address' => 'Test Address',
            'phone_number' => '1234567890',
            'parent_name' => 'Test Parent',
            'parent_contact' => '0987654321'
        ]);

        // Attach the student to class with ID 1 (make sure this class exists)
        $class = SchoolClass::find(1);
        if ($class) {
            $student->classes()->attach($class->id);
        }
    }
} 