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
        Schema::dropIfExists('class_student');
        Schema::dropIfExists('classes');

        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('class_id')->unique();
            $table->string('class_name');
            $table->string('school_id');
            $table->string('batch');
            $table->timestamps();

            $table->foreign('school_id')
                ->references('school_id')
                ->on('schools')
                ->onDelete('cascade');
        });

        Schema::create('class_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->string('user_id'); // foreign key added later
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First check if class_subject table exists and drop its constraints
        if (Schema::hasTable('class_subject')) {
            Schema::table('class_subject', function (Blueprint $table) {
                // Get the actual foreign key constraint names
                $foreignKeys = Schema::getConnection()
                    ->getDoctrineSchemaManager()
                    ->listTableForeignKeys('class_subject');
                
                foreach ($foreignKeys as $foreignKey) {
                    $table->dropForeign($foreignKey->getName());
                }
            });
            Schema::dropIfExists('class_subject');
        }

        // Drop other foreign key constraints
        if (Schema::hasTable('grade_submissions')) {
            Schema::table('grade_submissions', function (Blueprint $table) {
                if (Schema::hasColumn('grade_submissions', 'class_id')) {
                    $table->dropForeign(['class_id']);
                }
            });
        }

        if (Schema::hasTable('class_student')) {
            Schema::table('class_student', function (Blueprint $table) {
                if (Schema::hasColumn('class_student', 'class_id')) {
                    $table->dropForeign(['class_id']);
                }
            });
        }

        if (Schema::hasTable('intern_grades')) {
            Schema::table('intern_grades', function (Blueprint $table) {
                if (Schema::hasColumn('intern_grades', 'class_id')) {
                    $table->dropForeign(['class_id']);
                }
            });
        }

        // Drop tables in the correct order
        Schema::dropIfExists('grade_submission_subject');
        Schema::dropIfExists('grade_submissions');
        Schema::dropIfExists('class_student');
        Schema::dropIfExists('intern_grades');
        Schema::dropIfExists('classes');
    }
};
