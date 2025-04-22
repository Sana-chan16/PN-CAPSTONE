<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function index()
    {

        $schools = School::all();
        return view('training.manage-students', compact('schools'));
    }

    public function edit($id)
    {
        $school = School::findOrFail($id);
        return view('training.schools-edit', compact('school'));
    }

    public function update(Request $request, $id)
    {
        $school = School::findOrFail($id);
        
        $request->validate([
            'school_name' => 'required|string|max:255',
        ]);

        $school->update($request->all());

        return redirect()->route('schools.info')
            ->with('success', 'School information updated successfully.');
    }

    public function destroy($id)
    {
        $school = School::findOrFail($id);
        $school->delete();

        return redirect()->route('schools.info')
            ->with('success', 'School deleted successfully.');
    }

        try {
            $schools = School::all() ?? collect([]);
            return view('training.manage-students', compact('schools'));
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Error in SchoolController@index: ' . $e->getMessage());
            // Return an empty collection if there's an error
            $schools = collect([]);
            return view('training.manage-students', compact('schools'));
        }
    }

    public function create()
    {
        // Get all students with their batch information
        $students = \App\Models\PNUser::where('user_role', 'student')
            ->join('student_details', 'pnph_users.user_id', '=', 'student_details.user_id')
            ->select('pnph_users.user_id', 'pnph_users.user_fname', 'pnph_users.user_lname', 'student_details.batch')
            ->get();

        // Get unique batches for the filter dropdown
        $batches = $students->pluck('batch')->unique()->sort()->values();

        return view('training.schools.create', compact('students', 'batches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|string|unique:schools',
            'name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'course' => 'required|string|max:255',
            'num_semesters' => 'required|integer|min:1',
            'passing_grade_range' => 'required|string',
            'failing_grade_range' => 'required|string',
            'terms' => 'required|array',
            'terms.*' => 'required|string',
            'subjects' => 'nullable|array',
            'subjects.*.offer_code' => 'required|string',
            'subjects.*.name' => 'required|string',
            'subjects.*.instructor' => 'required|string',
            'subjects.*.schedule' => 'required|string',
            'classes' => 'nullable|array',
            'classes.*.class_id' => 'required|string|unique:classes,class_id',
            'classes.*.name' => 'required|string',
            'classes.*.students' => 'nullable|array',
            'classes.*.students.*' => 'exists:pnph_users,user_id'
        ]);

        try {
            // Prepare the data for creation
            $schoolData = [
                'school_id' => $validated['school_id'],
                'name' => $validated['name'],
                'department' => $validated['department'],
                'course' => $validated['course'],
                'num_semesters' => $validated['num_semesters'],
                'passing_grade_range' => $validated['passing_grade_range'],
                'failing_grade_range' => $validated['failing_grade_range'],
                'terms' => $request->input('terms', []),
                'subjects' => $request->input('subjects', [])
            ];

            // Create the school
            $school = School::create($schoolData);

            // Create classes if any
            if (isset($validated['classes'])) {
                foreach ($validated['classes'] as $classData) {
                    $class = $school->classes()->create([
                        'class_id' => $classData['class_id'],
                        'name' => $classData['name']
                    ]);

                    // Attach students to the class if any
                    if (isset($classData['students'])) {
                        $class->students()->attach($classData['students']);
                    }
                }
            }

            return redirect()->route('training.manage-students')
                ->with('success', 'School and classes created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating school: ' . $e->getMessage());
        }
    }

    public function edit(School $school)
    {
        return view('training.schools.edit', compact('school'));
    }

    public function update(Request $request, School $school)
    {
        $validated = $request->validate([
            'school_id' => 'required|string|unique:schools,school_id,' . $school->school_id . ',school_id',
            'name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'course' => 'required|string|max:255',
            'semester_count' => 'required|integer|min:1',
            'passing_grade_range' => 'required|string',
            'failing_grade_range' => 'required|string',
            'terms' => 'required|array',
            'subjects' => 'nullable|array',
            'subjects.*.offer_code' => 'required|string',
            'subjects.*.name' => 'required|string',
            'subjects.*.instructor' => 'required|string',
            'subjects.*.schedule' => 'required|string',
        ]);

        try {
            // Map the form fields to database fields
            $updateData = [
                'school_id' => $validated['school_id'],
                'name' => $validated['name'],
                'department' => $validated['department'],
                'course' => $validated['course'],
                'num_semesters' => $validated['semester_count'],
                'passing_grade_range' => $validated['passing_grade_range'],
                'failing_grade_range' => $validated['failing_grade_range'],
                'terms' => $validated['terms'],
            ];

            // Handle subjects with the new structure
            if (isset($validated['subjects'])) {
                $formattedSubjects = [];
                foreach ($validated['subjects'] as $subject) {
                    $formattedSubjects[] = [
                        'offer_code' => $subject['offer_code'],
                        'name' => $subject['name'],
                        'instructor' => $subject['instructor'],
                        'schedule' => $subject['schedule']
                    ];
                }
                $updateData['subjects'] = $formattedSubjects;
            }

            $school->update($updateData);

            return redirect()->route('training.schools.show', $school)
                ->with('success', 'School updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating school: ' . $e->getMessage());
        }
    }

    public function destroy(School $school)
    {
        $school->delete();

        return redirect()->route('training.manage-students')
            ->with('success', 'School deleted successfully.');
    }

    public function show(School $school)
    {
        // Load the school with its relationships
        $school->load('classes.students');
        
        return view('training.schools.show', compact('school'));
    }
} 