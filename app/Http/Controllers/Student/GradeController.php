<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\GradeSubmission;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        $gradeSubmissions = GradeSubmission::with(['class', 'subjects'])
            ->whereHas('students', function($query) {
                $query->where('user_id', auth()->id());
            })
            ->latest()
            ->get();

        return view('student.grades.index', compact('gradeSubmissions'));
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'submission_id' => 'required|exists:grade_submissions,id',
            'subject_id' => 'required|exists:subjects,id',
            'grade' => 'required|numeric|min:0|max:100',
        ]);

        $submission = GradeSubmission::findOrFail($validated['submission_id']);
        
        // Verify student belongs to this submission
        if (!$submission->students->contains('user_id', auth()->id())) {
            return back()->with('error', 'You are not authorized to submit grades for this submission.');
        }

        // Update the grade
        $submission->subjects()->updateExistingPivot($validated['subject_id'], [
            'grade' => $validated['grade'],
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'Grade submitted successfully.');
    }
}
