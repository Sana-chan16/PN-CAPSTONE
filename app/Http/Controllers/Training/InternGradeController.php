<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\InternGrade;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InternGradeController extends Controller
{
    public function index(Request $request)
    {
        $query = InternGrade::with(['intern', 'school', 'class', 'subject'])
            ->select([
                'intern_grades.*',
                DB::raw('CONCAT(pnph_users.user_fname, " ", pnph_users.user_lname) as intern_name'),
                'schools.name as school_name',
                'classes.class_name',
                'subjects.name as subject_name'
            ])
            ->join('pnph_users', 'intern_grades.intern_id', '=', 'pnph_users.user_id')
            ->join('schools', 'intern_grades.school_id', '=', 'schools.school_id')
            ->join('classes', 'intern_grades.class_id', '=', 'classes.class_id')
            ->join('subjects', 'intern_grades.subject_id', '=', 'subjects.id');

        if ($request->filled('school_filter')) {
            $query->where('intern_grades.school_id', $request->school_filter);
        }

        $internGrades = $query->get();
        $schools = School::all();

        return view('training.intern-grades.index', compact('internGrades', 'schools'));
    }

    public function create()
    {
        $schools = School::all();
        return view('training.intern-grades.create', compact('schools'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,school_id',
            'class_id' => 'required|exists:classes,class_id',
            'subject_id' => 'required|exists:subjects,id',
            'intern_id' => 'required|exists:pnph_users,user_id',
            'grade' => 'required|string|max:10',
            'remarks' => 'nullable|string|max:500',
        ]);

        $validated['status'] = 'Pending';
        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        InternGrade::create($validated);

        return redirect()
            ->route('training.intern-grades.index')
            ->with('success', 'Intern grade has been submitted successfully.');
    }

    public function edit(InternGrade $internGrade)
    {
        $schools = School::all();
        return view('training.intern-grades.edit', compact('internGrade', 'schools'));
    }

    public function update(Request $request, InternGrade $internGrade)
    {
        $validated = $request->validate([
            'grade' => 'required|string|max:10',
            'remarks' => 'nullable|string|max:500',
            'status' => 'required|in:Pending,Approved,Rejected',
        ]);

        $validated['updated_by'] = Auth::id();

        $internGrade->update($validated);

        return redirect()
            ->route('training.intern-grades.index')
            ->with('success', 'Intern grade has been updated successfully.');
    }

    public function destroy(InternGrade $internGrade)
    {
        $internGrade->delete();

        return redirect()
            ->route('training.intern-grades.index')
            ->with('success', 'Intern grade has been deleted successfully.');
    }
} 