<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixOrphanedGradeSubmissions extends Command
{
    protected $signature = 'fix:orphaned-grade-submissions';
    protected $description = 'Fix orphaned grade submissions by assigning them to school_id 001 and class_id 001';

    public function handle()
    {
        $this->info('Fixing orphaned grade submissions...');

        $schoolId = '001';
        $classId = '001';

        $orphanedSchools = DB::table('grade_submissions')
            ->whereNotIn('school_id', DB::table('schools')->pluck('id'))
            ->update(['school_id' => $schoolId]);

        $orphanedClasses = DB::table('grade_submissions')
            ->whereNotIn('class_id', DB::table('classes')->pluck('class_id'))
            ->update(['class_id' => $classId]);

        $this->info('All orphaned grade submissions have been assigned to school_id 001 and class_id 001.');
    }
}