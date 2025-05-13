<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\GradeSubmission;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
{
    public function index()
    {
        $student = auth()->user();
        $studentDetail = $student->studentDetail;

        if (!$studentDetail) {
            return view('student.grades.index', [
                'gradeSubmissions' => collect(),
                'error' => 'Your student details are not yet set up. Please contact your administrator.'
            ]);
        }

        // Get grade submissions that are specifically assigned to this student
        $gradeSubmissions = GradeSubmission::whereHas('students', function($query) use ($student) {
            $query->where('grade_submission_student.user_id', $student->user_id);
        })
        ->with(['school', 'classModel', 'subjects'])
        ->with(['students' => function($query) use ($student) {
            $query->where('grade_submission_student.user_id', $student->user_id)
                  ->withPivot('status', 'submitted_at');
        }])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        // Aggregate status for each submission (paginated)
        foreach ($gradeSubmissions->items() as $submission) {
            $statuses = DB::table('grade_submission_student')
                ->where('grade_submission_id', $submission->id)
                ->where('user_id', $student->user_id)
                ->pluck('status');
            if ($statuses->contains('rejected')) {
                $submission->aggregated_status = 'rejected';
            } elseif ($statuses->contains('pending')) {
                $submission->aggregated_status = 'pending';
            } elseif ($statuses->contains('submitted')) {
                $submission->aggregated_status = 'submitted';
            } elseif ($statuses->every(fn($s) => $s === 'approved')) {
                $submission->aggregated_status = 'approved';
            } else {
                $submission->aggregated_status = $statuses->first() ?? 'pending';
            }
        }

        Log::info('GradeController@index', [
            'student_id' => $student->user_id,
            'class_id' => $studentDetail->class_id,
            'submissions_count' => $gradeSubmissions->count(),
            'submissions' => $gradeSubmissions->toArray()
        ]);

        return view('student.grades.index', compact('gradeSubmissions'));
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'submission_id' => 'required|exists:grade_submissions,id',
            'grades' => 'required|array',
            'grades.*' => 'required|numeric|min:0|max:100',
        ]);

        $student = auth()->user();
        $submission = GradeSubmission::findOrFail($validated['submission_id']);
        
        // Verify student belongs to this submission
        if (!$submission->students()->where('grade_submission_student.user_id', $student->user_id)->exists()) {
            Log::warning('Unauthorized grade submission attempt', [
                'student_id' => $student->user_id,
                'submission_id' => $submission->id
            ]);
            return back()->with('error', 'You are not authorized to submit grades for this submission.');
        }

        // Prevent resubmission if already submitted
        $alreadySubmitted = $submission->students()->where('grade_submission_student.user_id', $student->user_id)
            ->where('grade_submission_student.status', 'submitted')->exists();
        if ($alreadySubmitted) {
            return back()->with('error', 'You have already submitted your grades for this submission.');
        }

        try {
            foreach ($validated['grades'] as $subjectId => $grade) {
                $submission->students()->updateExistingPivot($student->user_id, [
                    'grade' => $grade,
                    'status' => 'submitted',
                    'submitted_at' => now(),
                ], false, ['subject_id' => $subjectId]);
            }

            Log::info('Grades submitted successfully', [
                'student_id' => $student->user_id,
                'submission_id' => $submission->id,
                'grades' => $validated['grades']
            ]);

            return back()->with('success', 'Grades submitted successfully.');
        } catch (\Exception $e) {
            Log::error('Grade submission failed', [
                'student_id' => $student->user_id,
                'submission_id' => $submission->id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Failed to submit grades. Please try again.');
        }
    }

    public function showSubmissionForm(GradeSubmission $gradeSubmission)
    {
        return redirect()->route('student.grade-submissions.show', $gradeSubmission->id);
    }
}
