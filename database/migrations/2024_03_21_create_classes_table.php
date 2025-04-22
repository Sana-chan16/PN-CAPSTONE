<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->string('class_id')->primary();
            $table->string('school_id');
            $table->string('name');
            $table->timestamps();

            $table->foreign('school_id')
                  ->references('school_id')
                  ->on('schools')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('classes');
    }
}; 