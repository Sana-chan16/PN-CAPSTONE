<?php

namespace App\Http\Controllers;

use App\Models\PNUser;
use App\Models\StudentDetail;
use App\Models\School;
use App\Models\ClassModel;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function index()
    {
        $query = PNUser::where('user_role', 'Student')
            ->where('status', 'active')
            ->with('studentDetail');

        if (request('batch')) {
            $query->whereHas('studentDetail', function ($q) {
                $q->where('batch', request('batch'));
            });
        }

        $batches = StudentDetail::distinct()
            ->pluck('batch')
            ->filter()
            ->sort()
            ->values();

        $students = $query->paginate(10);

        return view('training.students-info', compact('students', 'batches'));
    }

    public function edit($user_id)
    {
        $student = PNUser::with('studentDetail')
            ->where('user_id', $user_id)
            ->firstOrFail();

        return view('training.edit-student', compact('student'));
    }

    public function view($user_id)
    {
        $student = PNUser::with('studentDetail')
            ->where('user_id', $user_id)
            ->firstOrFail();

        return view('training.view-student', compact('student'));
    }

    public function update(Request $request, $user_id)
    {
        $student = PNUser::where('user_id', $user_id)->firstOrFail();

        $request->validate([
            'user_lname' => 'required',
            'user_fname' => 'required',
            'user_mInitial' => 'nullable',
            'user_suffix' => 'nullable',
            'user_email' => 'required|email|unique:pn_users,user_email,' . $user_id . ',user_id',
            'batch' => 'required|digits:4',
            'group' => 'required|size:2',
            'student_number' => 'required|digits:4',
            'training_code' => 'required|size:2',
        ]);

        $student->update([
            'user_lname' => $request->user_lname,
            'user_fname' => $request->user_fname,
            'user_mInitial' => $request->user_mInitial,
            'user_suffix' => $request->user_suffix,
            'user_email' => $request->user_email,
        ]);

        $studentId = $request->batch . $request->group . $request->student_number . $request->training_code;

        StudentDetail::updateOrCreate(
            ['user_id' => $user_id],
            [
                'student_id' => $studentId,
                'batch' => $request->batch,
                'group' => $request->group,
                'student_number' => $request->student_number,
                'training_code' => $request->training_code,
            ]
        );

        return redirect()->route('training.students.index')
            ->with('success', 'Student information updated successfully.');
    }

    public function destroy($user_id)
    {
        $student = PNUser::where('user_id', $user_id)->firstOrFail();
        $student->update(['status' => 'inactive']);

        return redirect()->route('training.students.index')
            ->with('success', 'Student deactivated successfully.');
    }

    public function manageStudents()
    {
        $students = PNUser::where('user_role', 'Student')
            ->with('studentDetail')
            ->paginate(10);

        return view('training.manage-students', [
            'title' => 'Manage Students',
            'students' => $students
        ]);
    }

    public function dashboard()
    {
        $totalSchools = School::count();
        $totalClasses = ClassModel::count();
        $totalStudents = PNUser::where('user_role', 'Student')->count();

        $batchCounts = StudentDetail::whereHas('user', function ($q) {
                $q->where('user_role', 'Student');
            })
            ->select('batch')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('batch')
            ->orderBy('batch')
            ->pluck('count', 'batch');

        $recentStudents = PNUser::where('user_role', 'Student')
            ->with('studentDetail')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentSchools = School::with('classes')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('training.dashboard', [
            'title' => 'Training Dashboard',
            'totalSchools' => $totalSchools,
            'totalClasses' => $totalClasses,
            'totalStudents' => $totalStudents,
            'batchCounts' => $batchCounts,
            'recentStudents' => $recentStudents,
            'recentSchools' => $recentSchools
        ]);
    }
}
