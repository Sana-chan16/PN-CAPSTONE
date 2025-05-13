<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Create new table for subject grades
        Schema::create('grade_submission_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_submission_id')->constrained()->onDelete('cascade');
            $table->string('user_id');
            $table->foreignId('subject_id');
            $table->decimal('grade', 5, 2)->nullable();
            $table->string('proof_path')->nullable();
            $table->enum('status', ['pending', 'submitted', 'approved', 'rejected'])->default('pending');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('pnph_users')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            
            // Add unique constraint to prevent duplicate submissions
            $table->unique(['grade_submission_id', 'user_id', 'subject_id'], 'unique_grade_submission');
        });

        // Migrate existing data
        DB::statement('
            INSERT INTO grade_submission_subjects (
                grade_submission_id, user_id, subject_id, grade, proof_path, 
                status, submitted_at, created_at, updated_at
            )
            SELECT 
                gs.id, gss.user_id, gss.subject_id, gss.grade, gss.proof_path,
                gss.status, gss.submitted_at, gss.created_at, gss.updated_at
            FROM grade_submissions gs
            JOIN grade_submission_student gss ON gs.id = gss.grade_submission_id
        ');

        // Drop the old table
        Schema::dropIfExists('grade_submission_student');
    }

    public function down()
    {
        // Recreate the old table
        Schema::create('grade_submission_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_submission_id')->constrained()->onDelete('cascade');
            $table->string('user_id');
            $table->foreignId('subject_id');
            $table->decimal('grade', 5, 2)->nullable();
            $table->enum('status', ['pending', 'submitted', 'approved', 'rejected'])->default('pending');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('pnph_users')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });

        // Migrate data back
        DB::statement('
            INSERT INTO grade_submission_student (
                grade_submission_id, user_id, subject_id, grade, 
                status, submitted_at, created_at, updated_at
            )
            SELECT 
                grade_submission_id, user_id, subject_id, grade,
                status, submitted_at, created_at, updated_at
            FROM grade_submission_subjects
        ');

        // Drop the new table
        Schema::dropIfExists('grade_submission_subjects');
    }
}; 