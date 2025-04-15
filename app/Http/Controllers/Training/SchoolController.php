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
} 