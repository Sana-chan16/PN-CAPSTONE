<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\ClassModel;
use App\Models\PNUser;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index(School $school)
    {
        $classes = $school->classes()->with('students')->get();
        return view('training.schools.classes.all', compact('school', 'classes'));
    }

    public function create(School $school)
    {
        // Get all students with their batch information
        $students = PNUser::where('user_role', 'student')
            ->join('student_details', 'pnph_users.user_id', '=', 'student_details.user_id')
            ->select('pnph_users.user_id', 'pnph_users.user_fname', 'pnph_users.user_lname', 'student_details.batch')
            ->get();

        // Get unique batches for the filter dropdown
        $batches = $students->pluck('batch')->unique()->sort()->values();

        return view('training.schools.classes.create', compact('school', 'students', 'batches'));
    }

    public function store(Request $request, School $school)
    {
        $validated = $request->validate([
            'class_id' => 'required|string|unique:classes',
            'name' => 'required|string|max:255',
            'students' => 'array',
            'students.*' => 'exists:pnph_users,user_id'
        ]);

        $class = $school->classes()->create([
            'class_id' => $validated['class_id'],
            'name' => $validated['name']
        ]);

        if (isset($validated['students'])) {
            $class->students()->attach($validated['students']);
        }

        return redirect()->route('training.schools.show', $school)
            ->with('success', 'Class created successfully.');
    }

    public function edit(School $school, ClassModel $class)
    {
        $students = PNUser::where('user_role', 'student')
            ->join('student_details', 'pnph_users.user_id', '=', 'student_details.user_id')
            ->select('pnph_users.user_id', 'pnph_users.user_fname', 'pnph_users.user_lname', 'student_details.batch')
            ->get();

        $batches = $students->pluck('batch')->unique()->sort()->values();
        $selectedStudents = $class->students()->pluck('pnph_users.user_id')->toArray();

        return view('training.schools.classes.edit', compact('school', 'class', 'students', 'batches', 'selectedStudents'));
    }

    public function update(Request $request, School $school, ClassModel $class)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'students' => 'array',
            'students.*' => 'exists:pnph_users,user_id'
        ]);

        $class->update([
            'name' => $validated['name']
        ]);

        // Sync the selected students with the class
        $class->students()->sync($validated['students'] ?? []);

        return redirect()
            ->route('training.schools.show', $school)
            ->with('success', 'Class updated successfully');
    }

    public function destroy(School $school, ClassModel $class)
    {
        $class->delete();
        return redirect()
            ->route('training.schools.show', $school)
            ->with('success', 'Class deleted successfully');
    }

    public function show(School $school, ClassModel $class)
    {
        $class->load(['students' => function($query) {
            $query->join('student_details', 'pnph_users.user_id', '=', 'student_details.user_id')
                  ->select('pnph_users.user_id', 'pnph_users.user_fname', 'pnph_users.user_lname', 'student_details.batch');
        }]);
        
        return view('training.schools.classes.show', compact('school', 'class'));
    }

    public function createGradeSubmission(School $school, ClassModel $class)
    {
        $subjects = $school->subjects;
        return view('training.schools.classes.grades.create', compact('school', 'class', 'subjects'));
    }

    public function storeGradeSubmission(Request $request, School $school, ClassModel $class)
    {
        $validated = $request->validate([
            'subject' => 'required|exists:subjects,id',
            'term' => 'required|integer|between:1,3',
            'year' => 'required|integer|min:2000|max:' . (date('Y') + 1)
        ]);

        $gradeSubmission = $class->gradeSubmissions()->create([
            'subject_id' => $validated['subject'],
            'term' => $validated['term'],
            'year' => $validated['year']
        ]);

        return redirect()
            ->route('training.schools.classes.show', [$school, $class])
            ->with('success', 'Grade submission created successfully.');
    }

    public function showAllClasses()
    {
        $classes = \App\Models\ClassModel::with(['school', 'students'])->get();
        return view('training.schools.classes.all', compact('classes'));
    }
} 