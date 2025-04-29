<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\GradeSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GradeSubmissionController extends Controller
{
    public function index()
    {
        $classes = ClassModel::with('school')
            ->orderBy('class_name')
            ->paginate(12);

        return view('training.gradesubmission.index', compact('classes'));
    }

    public function show(ClassModel $class)
    {
        $class->load(['school.subjects', 'students']);
        return view('training.gradesubmission.show', compact('class'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'semester' => 'required|in:1,2',
            'term' => 'required|string',
            'academic_year' => 'required|string',
            'subjects' => 'required|array',
            'subjects.*' => 'exists:subjects,id'
        ]);

        try {
            DB::beginTransaction();

            // Create the grade submission
            $gradeSubmission = GradeSubmission::create([
                'class_id' => $validated['class_id'],
                'semester' => $validated['semester'],
                'term' => $validated['term'],
                'academic_year' => $validated['academic_year'],
                'status' => 'active'
            ]);

            // Get the class and its students
            $class = ClassModel::with('students')->findOrFail($validated['class_id']);
            
            Log::info('Creating grade submission', [
                'submission_id' => $gradeSubmission->id,
                'class_id' => $class->id,
                'student_count' => $class->students->count(),
                'subject_count' => count($validated['subjects'])
            ]);

            // Attach all subjects to the grade submission with initial state
            foreach ($validated['subjects'] as $subjectId) {
                $gradeSubmission->subjects()->attach($subjectId, [
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Attach all students from the class to this grade submission
            foreach ($class->students as $student) {
                $gradeSubmission->students()->attach($student->id, [
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            DB::commit();

            return redirect()->route('training.gradesubmission.show', $request->class_id)
                ->with('success', 'Grade submission created successfully. Students can now submit their grades.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create grade submission', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to create grade submission: ' . $e->getMessage());
        }
    }
}
