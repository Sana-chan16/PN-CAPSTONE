<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('intern_grades', function (Blueprint $table) {
            $table->id();
            $table->string('intern_id');
            $table->string('school_id');
            $table->string('class_id');
            $table->unsignedBigInteger('subject_id');
            $table->string('grade', 10);
            $table->text('remarks')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->string('created_by');
            $table->string('updated_by');
            $table->timestamps();

            // Add indexes
            $table->index('intern_id');
            $table->index('school_id');
            $table->index('class_id');
            $table->index('subject_id');
            $table->index('created_by');
            $table->index('updated_by');
        });
    }

    public function down()
    {
        Schema::dropIfExists('intern_grades');
    }
}; 