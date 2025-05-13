<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\GradeSubmission;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class GradeSubmissionController extends Controller
{
    public function show(GradeSubmission $gradeSubmission)
    {
        // Verify that the student is part of this grade submission
        if (!$gradeSubmission->students()->where('grade_submission_student.user_id', Auth::id())->exists()) {
            return redirect()->route('student.grades.index')
                ->with('error', 'You are not authorized to view this submission.');
        }

        $subjects = Subject::whereIn('id', $gradeSubmission->subject_ids)->get();
        $student = Auth::user();
        $studentGrades = $gradeSubmission->students()->where('grade_submission_student.user_id', $student->id)->get();
        $statuses = $studentGrades->pluck('pivot.status')->filter()->values();
        if ($statuses->contains('rejected')) {
            // If any subject is rejected, always show the submission form
            return view('student.grade-submissions.submit', compact('gradeSubmission', 'subjects'));
        }
        $submitted = $studentGrades->first(function($g) {
            return in_array($g->pivot->status, ['submitted', 'approved']);
        });
        if ($submitted) {
            // If submitted or approved, show the view page
            $grades = $gradeSubmission->students()->where('grade_submission_student.user_id', $student->id)->get();
            return view('student.grade-submissions.view', compact('gradeSubmission', 'subjects', 'grades'));
        }
        // If no submission exists yet, show the submission form
        return view('student.grade-submissions.submit', compact('gradeSubmission', 'subjects'));
    }

    public function store(Request $request, GradeSubmission $gradeSubmission)
    {
        // Verify that the student is part of this grade submission
        if (!$gradeSubmission->students()->where('grade_submission_student.user_id', Auth::id())->exists()) {
            return redirect()->route('student.grades.index')
                ->with('error', 'You are not authorized to submit grades for this submission.');
        }

        // Get the school's grade range configuration
        $school = $gradeSubmission->school;
        $passingMin = $school->passing_grade_min;
        $passingMax = $school->passing_grade_max;
        $failingMin = $school->failing_grade_min;
        $failingMax = $school->failing_grade_max;

        $request->validate([
            'grades' => 'required|array',
            'grades.*' => [
                'required',
                function ($attribute, $value, $fail) use ($passingMin, $passingMax, $failingMin, $failingMax) {
                    // Allow special grades
                    if (in_array($value, ['INC', 'NC', 'DR'])) {
                        return;
                    }
                    
                    // Validate numerical grades
                    if (!is_numeric($value)) {
                        $fail('The grade must be a number or a special grade (INC, NC, DR).');
                        return;
                    }

                    $grade = floatval($value);
                    
                    // Check if grade is within valid range (1.0 to 5.0)
                    if ($grade < 1.0 || $grade > 5.0) {
                        $fail('The grade must be between 1.0 and 5.0.');
                        return;
                    }

                    // Check if grade is within passing range (1.0-3.0 or 3.1-5.0)
                    $isPassing = ($grade >= 1.0 && $grade <= 3.0) || ($grade >= 3.1 && $grade <= 5.0);
                    
                    if (!$isPassing) {
                        $fail('The grade must be either between 1.0-3.0 or 3.1-5.0.');
                    }
                }
            ],
            'proof_file' => 'required|file|mimes:jpeg,png,pdf|max:5120', // 5MB max
        ]);

        try {
            // Store the proof file
            $proofPath = $request->file('proof_file')->store('grade-proofs', 'public');

            // Update the grade submission student pivot table using direct DB update
            foreach ($request->grades as $subjectId => $grade) {
                $affected = DB::table('grade_submission_student')
                    ->where('grade_submission_id', $gradeSubmission->id)
                    ->where('user_id', Auth::user()->user_id)
                    ->where('subject_id', $subjectId)
                    ->update([
                        'grade' => $grade,
                        'proof_path' => $proofPath,
                        'status' => 'submitted',
                        'submitted_at' => now(),
                        'updated_at' => now(),
                    ]);
                if ($affected === 0) {
                    // If no row was updated, insert a new one
                    DB::table('grade_submission_student')->insert([
                        'grade_submission_id' => $gradeSubmission->id,
                        'user_id' => Auth::user()->user_id,
                        'subject_id' => $subjectId,
                        'grade' => $grade,
                        'proof_path' => $proofPath,
                        'status' => 'submitted',
                        'submitted_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                // Debug log removed. If status is still pending after approval/rejection, check backend update logic for approval/rejection.
            }

            return redirect()->route('student.grades.index')
                ->with('success', 'Grades submitted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to submit grades: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function view(GradeSubmission $gradeSubmission)
    {
        // Verify that the student is part of this grade submission
        if (!$gradeSubmission->students()->where('grade_submission_student.user_id', Auth::id())->exists()) {
            return redirect()->route('student.grades.index')
                ->with('error', 'You are not authorized to view this submission.');
        }

        // Get all subjects for this submission
        $subjects = Subject::whereIn('id', $gradeSubmission->subject_ids)->get();
        
        // Get the student's grades with their status
        $studentGrades = DB::table('grade_submission_student')
            ->where('grade_submission_id', $gradeSubmission->id)
            ->where('user_id', Auth::id())
            ->get()
            ->keyBy('subject_id');

        // Combine subjects with their grades
        $grades = $subjects->map(function($subject) use ($studentGrades) {
            $grade = $studentGrades->get($subject->id);
            $subject->grade = $grade ? $grade->grade : null;
            $subject->status = $grade ? $grade->status : 'pending';
            $subject->submitted_at = $grade ? $grade->submitted_at : null;
            $subject->proof_path = $grade ? $grade->proof_path : null;
            return $subject;
        });

        return view('student.grade-submissions.view', compact('gradeSubmission', 'grades'));
    }
} 