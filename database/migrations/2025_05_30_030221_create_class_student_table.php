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
        Schema::create('class_student', function (Blueprint $table) {
    $table->id();
    $table->string('class_id');
    $table->string('user_id');
    $table->timestamps();

    $table->foreign('class_id')->references('class_id')->on('classes')->onDelete('cascade');
    $table->foreign('user_id')->references('user_id')->on('pnph_users')->onDelete('cascade');
    $table->unique(['class_id', 'user_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_student');
    }
};
