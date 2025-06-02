<?php

namespace Database\Seeders;

use App\Models\GradeSubmissionSubject;
use App\Models\GradeSubmissionProof;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class DummyGradesAndProofsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        
        // Get all pending grade submissions
        $pendingGrades = DB::table('grade_submission_subject')
            ->where('status', 'pending')
            ->get();

        if ($pendingGrades->isEmpty()) {
            $this->command->info('No pending grade submissions found.');
            return;
        }

        $this->command->info("Found " . $pendingGrades->count() . " pending grade submissions. Adding grades and proofs...");

        $grades = [
            // Numeric grades
            '1.00', '1.25', '1.50', '1.75', 
            '2.00', '2.25', '2.50', '2.75', 
            '3.00',
            // Special grades
            'INC', 'DR', 'NC', '5.00'
        ];

        $proofTypes = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'png' => 'image/png'
        ];

        $inserted = 0;
        $skipped = 0;

        foreach ($pendingGrades as $grade) {
            // Skip if already has a grade
            if (!is_null($grade->grade)) {
                $skipped++;
                continue;
            }

            // Randomly select a grade
            $randomGrade = $grades[array_rand($grades)];
            
            // Update the grade (keeping status as 'pending')
            DB::table('grade_submission_subject')
                ->where('id', $grade->id)
                ->update([
                    'grade' => $randomGrade,
                    'updated_at' => now()
                ]);

            // Add a proof (if one doesn't exist)
            $existingProof = DB::table('grade_submission_proofs')
                ->where('grade_submission_id', $grade->grade_submission_id)
                ->where('user_id', $grade->user_id)
                ->first();

            if (!$existingProof) {
                $ext = array_rand($proofTypes);
                $proofData = [
                    'grade_submission_id' => $grade->grade_submission_id,
                    'user_id' => $grade->user_id,
                    'file_path' => 'proofs/' . $grade->user_id . '_' . $grade->grade_submission_id . '.' . $ext,
                    'file_name' => 'Grade_Proof_' . $grade->user_id . '_' . $grade->subject_id . '.' . $ext,
                    'file_type' => $proofTypes[$ext],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                DB::table('grade_submission_proofs')->insert($proofData);
            }

            $inserted++;
        }

        $this->command->info("Successfully added grades to $inserted pending submissions. Skipped $skipped records (already had grades).");
    }
}
