<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\PNUser;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        // Get all students without authentication check
        $students = PNUser::where('user_role', 'Student')->get();

        return view('training.students-info', compact('students'));
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

        return redirect()->route('students.info')
            ->with('success', 'Student information updated successfully.');
    }

    public function destroy($id)
    {
        $student = PNUser::findOrFail($id);
        $student->delete();

        return redirect()->route('students.info')
            ->with('success', 'Student deleted successfully.');
    }
} 