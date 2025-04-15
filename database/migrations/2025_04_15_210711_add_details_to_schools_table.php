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
            $table->string('department')->nullable()->after('school_name');
            $table->string('course')->nullable()->after('department');
            $table->integer('semesters')->nullable()->after('course');
            $table->json('terms')->nullable()->after('semesters');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn(['department', 'course', 'semesters', 'terms']);
        });
    }
};
