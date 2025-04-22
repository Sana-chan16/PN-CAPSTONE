<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('class_student', function (Blueprint $table) {
            $table->string('class_id');
            $table->string('user_id');
            $table->timestamps();

            $table->primary(['class_id', 'user_id']);
            $table->foreign('class_id')->references('class_id')->on('classes')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('pnph_users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('class_student');
    }
}; 