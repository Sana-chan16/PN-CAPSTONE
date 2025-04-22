<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            // Rename semester_count to num_semesters
            $table->renameColumn('semester_count', 'num_semesters');
            
            // Drop the old grade columns
            $table->dropColumn(['passing_grade', 'failing_grade']);
            
            // Add new grade range columns
            $table->string('passing_grade_range');
            $table->string('failing_grade_range');
            
            // Make sure subjects column exists and is JSON
            if (!Schema::hasColumn('schools', 'subjects')) {
                $table->json('subjects')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            // Reverse the changes
            $table->renameColumn('num_semesters', 'semester_count');
            
            // Drop the new columns
            $table->dropColumn(['passing_grade_range', 'failing_grade_range']);
            
            // Add back the old columns
            $table->decimal('passing_grade', 3, 1);
            $table->decimal('failing_grade', 3, 1);
        });
    }
};
