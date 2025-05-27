<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToGradeSubmissionsTable extends Migration
{
    public function up()
    {
        Schema::table('grade_submissions', function (Blueprint $table) {
            $table->string('status')->default('pending'); // Add status column with default value 'pending'
        });
    }

    public function down()
    {
        Schema::table('grade_submissions', function (Blueprint $table) {
            $table->dropColumn('status'); // Remove status column if migration is rolled back
        });
    }
} 