<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('student_id')->unique();
            $table->date('date_of_birth');
            $table->string('gender');
            $table->text('address');
            $table->string('phone_number');
            $table->string('parent_name');
            $table->string('parent_contact');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('students');
    }
}; 