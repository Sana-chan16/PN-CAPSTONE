<?php

namespace Database\Seeders;

use App\Models\GradeSubmissionProof;
use App\Models\GradeSubmissionSubject;
use App\Models\GradeSubmission;
use App\Models\StudentDetail;
use App\Models\Student;
use App\Models\PNUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClearStudentDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Delete grade submission proofs
        GradeSubmissionProof::truncate();
        
        // Delete grade submission subjects (grades)
        GradeSubmissionSubject::truncate();
        
        // Delete grade submissions
        GradeSubmission::truncate();
        
        // Delete class-student relationships
        DB::table('class_student')->truncate();
        
        // Delete student details
        StudentDetail::truncate();
        
        // Delete students
        Student::truncate();
        
        // Delete PNUsers with user_role 'student' (assuming 'student' is the role name for students)
        PNUser::where('user_role', 'student')->delete();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        $this->command->info('All student data has been cleared successfully.');
    }
}
