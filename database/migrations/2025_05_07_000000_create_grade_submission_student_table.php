<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradeSubmissionStudentTable extends Migration
{
    public function up()
    {
        Schema::create('grade_submission_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_submission_id')->constrained()->onDelete('cascade');
            $table->string('user_id');
            $table->foreignId('subject_id');
            $table->string('grade')->nullable();
            $table->string('proof_path')->nullable();
            $table->enum('status', ['pending', 'submitted', 'approved', 'rejected'])->default('pending');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('pnph_users')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('grade_submission_student');
    }
} 