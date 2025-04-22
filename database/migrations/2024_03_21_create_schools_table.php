<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->string('school_id')->primary();
            $table->string('name');
            $table->string('department');
            $table->string('course');
            $table->integer('semester_count');
            $table->json('terms');
            $table->decimal('passing_grade', 3, 1);
            $table->decimal('failing_grade', 3, 1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('schools');
    }
}; 