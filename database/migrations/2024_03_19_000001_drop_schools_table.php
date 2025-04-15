<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('schools');
    }

    public function down()
    {
        // This is intentionally left empty as we don't want to recreate the table
    }
}; 