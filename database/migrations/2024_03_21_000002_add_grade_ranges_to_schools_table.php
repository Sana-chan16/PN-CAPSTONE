<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->decimal('passing_grade', 3, 1)->default(3.0);
            $table->decimal('failing_grade', 3, 1)->default(1.0);
        });
    }

    public function down()
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn(['passing_grade', 'failing_grade']);
        });
    }
}; 