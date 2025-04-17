<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Grade;
use Illuminate\Http\Request;

class GradeSubmissionController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::all();
        return view('training.grade-sub', compact('classes'));
    }

    public function showStudents($class_id)
    {
        try {
            $class = SchoolClass::findOrFail($class_id);
            $students = $class->students;
            
            return view('training.grade-sub', compact('class', 'students'));
        } catch (\Exception $e) {
            \Log::error('Error in showStudents: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading students.');
        }
    }

    public function showForm($class_id, $student_id)
    {
        try {
            $class = SchoolClass::with('school')->findOrFail($class_id);
            $student = Student::findOrFail($student_id);
            
            // Get terms from school
            $terms = json_decode($class->school->terms, true) ?? [];
            
            // Get subjects from school
            $subjects = $class->school->subjects;

            return view('training.grade-submission-form', compact('class', 'student', 'terms', 'subjects'));
        } catch (\Exception $e) {
            \Log::error('Error in showForm: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading grade submission form.');
        }
    }

    public function store(Request $request, $class_id, $student_id)
    {
        $request->validate([
            'semester' => 'required',
            'terms' => 'required|array',
            'academic_year' => 'required',
            'grades' => 'required|array',
            'grades.*' => 'required|numeric|min:0|max:100',
        ]);

        try {
            $class = SchoolClass::findOrFail($class_id);
            
            foreach ($request->grades as $subject_id => $grade) {
                Grade::create([
                    'student_id' => $student_id,
                    'class_id' => $class_id,
                    'subject_id' => $subject_id,
                    'semester' => $request->semester,
                    'terms' => json_encode($request->terms),
                    'academic_year' => $request->academic_year,
                    'grade' => $grade,
                ]);
            }

            return redirect()->route('grade.submission.students', [
                'class_id' => $class_id
            ])->with('success', 'Grades submitted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error in store: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error submitting grades.');
        }
    }

    public function monitorGrades($class_id)
    {
        try {
            $class = SchoolClass::findOrFail($class_id);
            $grades = Grade::where('class_id', $class_id)
                ->with(['student', 'subject'])
                ->get()
                ->groupBy('student_id');

            return view('training.grade-sub', compact('class', 'grades'));
        } catch (\Exception $e) {
            \Log::error('Error in monitorGrades: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading grade monitoring.');
        }
    }

    public function viewSubjects($class_id)
    {
        try {
            $class = SchoolClass::with('school.subjects')->findOrFail($class_id);
            $subjects = $class->school->subjects;

            return view('training.grade-sub', compact('class', 'subjects'));
        } catch (\Exception $e) {
            \Log::error('Error in viewSubjects: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading subjects.');
        }
    }
} 