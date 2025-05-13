<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\GradeSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Get the authenticated student's details
        $student = Auth::user();
        $studentDetail = $student->studentDetail;

        // Debug student details
        \Log::info('Student Details', [
            'student_id' => $student->id,
            'student_detail' => $studentDetail ? [
                'id' => $studentDetail->id,
                'user_id' => $studentDetail->user_id,
                'student_id' => $studentDetail->student_id,
                'class_id' => $studentDetail->class_id,
                'school_id' => $studentDetail->school_id
            ] : null
        ]);

        if (!$studentDetail) {
            return view('student.dashboard', [
                'title' => 'Student Dashboard',
                'gradeSubmissions' => collect(), // Empty collection if no student details
            ]);
        }

        // Get all grade submissions for debugging
        $allGradeSubmissions = GradeSubmission::with(['school', 'classModel', 'students'])
            ->where('class_id', $studentDetail->class_id)
            ->get();

        \Log::info('All Grade Submissions for Class', [
            'class_id' => $studentDetail->class_id,
            'submissions_count' => $allGradeSubmissions->count(),
            'submissions' => $allGradeSubmissions->map(function($submission) {
                return [
                    'id' => $submission->id,
                    'class_id' => $submission->class_id,
                    'students_count' => $submission->students->count(),
                    'student_ids' => $submission->students->pluck('user_id')->toArray()
                ];
            })->toArray()
        ]);

        // Get grade submissions that are specifically assigned to this student
        $gradeSubmissions = GradeSubmission::whereHas('students', function($query) use ($student) {
            $query->where('grade_submission_student.user_id', $student->user_id);
        })
        ->with(['school', 'classModel'])
        ->orderBy('created_at', 'desc')
        ->get();

        // Debug information
        \Log::info('Student Dashboard Data', [
            'student_id' => $student->user_id,
            'class_id' => $studentDetail->class_id,
            'submissions_count' => $gradeSubmissions->count(),
            'submissions' => $gradeSubmissions->toArray()
        ]);

        return view('student.dashboard', [
            'title' => 'Student Dashboard',
            'gradeSubmissions' => $gradeSubmissions,
            'debug_info' => [
                'student_id' => $student->id,
                'class_id' => $studentDetail->class_id,
                'school_id' => $studentDetail->school_id,
                'all_submissions_count' => $allGradeSubmissions->count(),
                'assigned_submissions_count' => $gradeSubmissions->count()
            ]
        ]);
    }
}
