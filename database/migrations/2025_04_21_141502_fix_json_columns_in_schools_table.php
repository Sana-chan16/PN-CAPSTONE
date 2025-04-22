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
            // Drop existing JSON columns
            $table->dropColumn(['terms', 'subjects']);
        });

        Schema::table('schools', function (Blueprint $table) {
            // Recreate JSON columns with proper type
            $table->json('terms')->nullable();
            $table->json('subjects')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn(['terms', 'subjects']);
            $table->json('terms');
            $table->json('subjects')->nullable();
        });
    }
};
