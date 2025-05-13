<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First, create a temporary column
        Schema::table('grade_submission_student', function (Blueprint $table) {
            $table->string('grade_new')->nullable()->after('grade');
        });

        // Copy data from old column to new column, converting special grades
        DB::statement('
            UPDATE grade_submission_student 
            SET grade_new = CASE 
                WHEN grade = 0 THEN "INC"
                WHEN grade = 1 THEN "NC"
                WHEN grade = 2 THEN "DR"
                ELSE CAST(grade AS CHAR)
            END
        ');

        // Drop the old column
        Schema::table('grade_submission_student', function (Blueprint $table) {
            $table->dropColumn('grade');
        });

        // Rename the new column to the original name
        Schema::table('grade_submission_student', function (Blueprint $table) {
            $table->renameColumn('grade_new', 'grade');
        });
    }

    public function down()
    {
        // First, create a temporary column
        Schema::table('grade_submission_student', function (Blueprint $table) {
            $table->decimal('grade_old', 5, 2)->nullable()->after('grade');
        });

        // Copy data back, converting special grades to numbers
        DB::statement('
            UPDATE grade_submission_student 
            SET grade_old = CASE 
                WHEN grade = "INC" THEN 0
                WHEN grade = "NC" THEN 1
                WHEN grade = "DR" THEN 2
                ELSE CAST(grade AS DECIMAL(5,2))
            END
        ');

        // Drop the string column
        Schema::table('grade_submission_student', function (Blueprint $table) {
            $table->dropColumn('grade');
        });

        // Rename the decimal column back to original name
        Schema::table('grade_submission_student', function (Blueprint $table) {
            $table->renameColumn('grade_old', 'grade');
        });
    }
}; 