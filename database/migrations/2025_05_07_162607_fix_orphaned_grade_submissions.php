<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    // Ensure default school exists
    if (!DB::table('schools')->where('id', '001')->exists()) {
        DB::table('schools')->insert([
            'id' => '001',
            'name' => 'Default School',
        ]);
    }

    // Ensure default class exists
    if (!DB::table('classes')->where('class_id', '001')->exists()) {
        DB::table('classes')->insert([
            'class_id' => '001',
            'class_name' => 'Default Class',
        ]);
    }

    // Assign all orphaned grade submissions to default school/class
    DB::table('grade_submissions')
        ->whereNotIn('school_id', DB::table('schools')->pluck('id'))
        ->update(['school_id' => '001']);

    DB::table('grade_submissions')
        ->whereNotIn('class_id', DB::table('classes')->pluck('class_id'))
        ->update(['class_id' => '001']);
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grade_submissions', function (Blueprint $table) {
            //
        });
    }
};
