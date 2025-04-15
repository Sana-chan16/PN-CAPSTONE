<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\PNUser;
use App\Models\School;
use Illuminate\Http\Request;

class ManageStudentController extends Controller
{
    public function index()
    {
        $schools = \App\Models\School::withCount('subjects')->get();
        return view('training.manage-students', compact('schools'));
    }

    public function edit($id)
    {
        $student = PNUser::findOrFail($id);
        return view('training.students-edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $student = PNUser::findOrFail($id);
        
        $request->validate([
            'user_lname' => 'required',
            'user_fname' => 'required',
            'user_email' => 'required|email|unique:pnph_users,user_email,' . $id . ',user_id',
        ]);

        $student->update($request->all());

        return redirect()->route('manage.students')
            ->with('success', 'Student information updated successfully.');
    }

    public function destroy($id)
    {
        $student = PNUser::findOrFail($id);
        $student->delete();

        return redirect()->route('manage.students')
            ->with('success', 'Student deleted successfully.');
    }

    public function createSchool()
    {
        return view('training.schools-create');
    }

    public function storeSchool(Request $request)
    {
        $request->validate([
            'school_id' => 'required|unique:schools,school_id',
            'school_name' => 'required|unique:schools,school_name',
            'department' => 'required',
            'course' => 'required',
            'semesters' => 'required|integer|min:1',
            'terms' => 'required|array',
            'subjects' => 'required|array',
            'subjects.*.offer_code' => 'required',
            'subjects.*.subject_name' => 'required',
            'subjects.*.instructor' => 'required',
            'subjects.*.schedule' => 'required',
        ]);

        // Create the school
        $school = School::create([
            'school_id' => $request->school_id,
            'school_name' => $request->school_name,
            'department' => $request->department,
            'course' => $request->course,
            'semesters' => $request->semesters,
            'terms' => json_encode($request->terms),
        ]);

        // Create subjects
        foreach ($request->subjects as $subjectData) {
            $school->subjects()->create([
                'offer_code' => $subjectData['offer_code'],
                'subject_name' => $subjectData['subject_name'],
                'instructor' => $subjectData['instructor'],
                'schedule' => $subjectData['schedule'],
            ]);
        }

        return redirect()->route('manage.students')
            ->with('success', 'School added successfully.');
    }

    public function listSchools()
    {
        $schools = \App\Models\School::withCount('subjects')->get();
        return view('training.manage-schools', compact('schools'));
    }

    public function viewSchool($id)
    {
        $school = \App\Models\School::findOrFail($id);
        $classes = \App\Models\ClassModel::where('school_id', $id)->get();
        $students = \App\Models\PNUser::where('user_role', 'Student')->get();
        return view('training.school-classes', compact('school', 'classes', 'students'));
    }

    public function storeClass(Request $request, $school_id)
    {
        $request->validate([
            'class_name' => 'required|string|max:255',
            'students' => 'array',
            'students.*' => 'string',
        ]);

        $class = \App\Models\ClassModel::create([
            'school_id' => $school_id,
            'class_name' => $request->class_name,
        ]);

        if ($request->has('students')) {
            $class->students()->attach($request->students);
        }

        return redirect()->route('manage.schools.view', $school_id)
            ->with('success', 'Class added successfully.');
    }

    public function createClass($school_id)
    {
        $school = \App\Models\School::findOrFail($school_id);
        $students = \App\Models\PNUser::where('user_role', 'Student')->get();
        return view('training.class-create', compact('school', 'students'));
    }

    public function selectStudents($school_id, $class_id)
    {
        $school = \App\Models\School::findOrFail($school_id);
        $class = \App\Models\ClassModel::with('students')->findOrFail($class_id);
        $students = \App\Models\PNUser::where('user_role', 'Student')->get();
        return view('training.class-select-students', compact('school', 'class', 'students'));
    }

    public function updateStudents(Request $request, $school_id, $class_id)
    {
        $class = \App\Models\ClassModel::findOrFail($class_id);
        $class->students()->sync($request->input('students', []));
        return redirect()->route('manage.schools.view', $school_id)
            ->with('success', 'Class students updated successfully.');
    }

    public function destroySchool($id)
    {
        $school = \App\Models\School::findOrFail($id);
        $school->delete();
        return redirect()->route('manage.schools')->with('success', 'School deleted successfully.');
    }
} 