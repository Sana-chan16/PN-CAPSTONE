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
            $table->integer('ict_learning_competency');
            $table->integer('twenty_first_century_skills');
            $table->integer('expected_outputs_deliverables');
            $table->integer('final_grade');
            $table->text('remarks')->nullable();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();

            // Add foreign key constraints
            $table->foreign('intern_id')
                  ->references('user_id')
                  ->on('pnph_users')
                  ->onDelete('cascade');
            
            $table->foreign('school_id')
                  ->references('school_id')
                  ->on('schools')
                  ->onDelete('cascade');
            
            $table->foreign('class_id')
                  ->references('class_id')
                  ->on('classes')
                  ->onDelete('cascade');
            
            $table->foreign('created_by')
                  ->references('user_id')
                  ->on('pnph_users')
                  ->onDelete('set null');
            
            $table->foreign('updated_by')
                  ->references('user_id')
                  ->on('pnph_users')
                  ->onDelete('set null');

            // Add indexes
            $table->index('intern_id');
            $table->index('school_id');
            $table->index('class_id');
            $table->index('created_by');
            $table->index('updated_by');
        });
    }

    public function down()
    {
        Schema::dropIfExists('intern_grades');
    }
}; 