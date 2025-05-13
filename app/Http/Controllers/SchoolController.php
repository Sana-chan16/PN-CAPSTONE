<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Subject;
use App\Models\ClassModel;
use App\Models\StudentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolController extends Controller
{
    public function index()
    {
        $schools = School::with('subjects')->paginate(10);
        return view('training.manage-students', compact('schools'));
    }

    public function show(School $school)
    {
        $school->load('subjects');
        $classes = ClassModel::where('school_id', $school->school_id)
            ->with('students')
            ->get();
        return view('training.schools.show', compact('school', 'classes'));
    }

    public function create()
    {
        $batches = StudentDetail::select('batch')->distinct()->orderBy('batch')->get();
        return view('training.schools.create', compact('batches'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'school_id' => 'required|string|unique:schools,school_id',
                'name' => 'required|string|max:255',
                'department' => 'required|string|max:255',
                'course' => 'required|string|max:255',
                'semester_count' => 'required|integer|min:1',
                'terms' => 'required|array|min:1',
                'passing_grade_min' => 'required|numeric',
                'passing_grade_max' => 'required|numeric',
                'failing_grade_min' => 'required|numeric',
                'failing_grade_max' => 'required|numeric',
                'subjects' => 'required|array|min:1',
                'subjects.*.offer_code' => 'required|string',
                'subjects.*.name' => 'required|string',
                'subjects.*.instructor' => 'required|string',
                'subjects.*.schedule' => 'required|string',
                'classes' => 'array',
                'classes.*.class_id' => 'required|string|unique:classes,class_id',
                'classes.*.name' => 'required|string',
                'classes.*.batch' => 'required|string',
                'classes.*.student_ids' => 'required|array',
                'classes.*.student_ids.*' => 'exists:pnph_users,user_id',
            ]);

            DB::beginTransaction();

            // Create school
            $school = School::create([
                'school_id' => $validated['school_id'],
                'name' => $validated['name'],
                'department' => $validated['department'],
                'course' => $validated['course'],
                'semester_count' => $validated['semester_count'],
                'terms' => $validated['terms'],
                'passing_grade_min' => $validated['passing_grade_min'],
                'passing_grade_max' => $validated['passing_grade_max'],
                'failing_grade_min' => $validated['failing_grade_min'],
                'failing_grade_max' => $validated['failing_grade_max'],
            ]);

            // Create subjects
            foreach ($validated['subjects'] as $subjectData) {
                $school->subjects()->create([
                    'offer_code' => $subjectData['offer_code'],
                    'name' => $subjectData['name'],
                    'instructor' => $subjectData['instructor'],
                    'schedule' => $subjectData['schedule'],
                ]);
            }

            // Create classes if provided
            if (isset($validated['classes'])) {
                foreach ($validated['classes'] as $classData) {
                    $class = new ClassModel();
                    $class->class_id = $classData['class_id'];
                    $class->class_name = $classData['name'];
                    $class->school_id = $school->school_id;
                    $class->batch = $classData['batch'];
                    $class->save();

                    if (isset($classData['student_ids'])) {
                        // Attach students to class
                        $class->students()->attach($classData['student_ids']);
                        
                        // Update student_details with school_id and class_id
                        StudentDetail::whereIn('user_id', $classData['student_ids'])
                            ->update([
                                'school_id' => $school->school_id,
                                'class_id' => $class->class_id
                            ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('training.manage-students')
                ->with('success', 'School created successfully with subjects.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating school: ' . $e->getMessage())
                        ->withInput();
        }
    }

    public function edit(School $school)
    {
        $school->load(['subjects', 'classes.students']);
        $batches = StudentDetail::select('batch')->distinct()->orderBy('batch')->get();
        $students = DB::table('pnph_users')
            ->join('student_details', 'pnph_users.user_id', '=', 'student_details.user_id')
            ->select('pnph_users.*', 'student_details.batch', 'student_details.group', 'student_details.student_number', 'student_details.training_code')
            ->where('pnph_users.user_role', 'student')
            ->where('pnph_users.status', 'active')
            ->get();

        // Prepare classes with students info for the edit view
        $classes = $school->classes->map(function($class) {
            return [
                'class_id' => $class->class_id,
                'name' => $class->class_name,
                'batch' => $class->batch,
                'students' => $class->students->map(function($student) {
                    $detail = $student->studentDetail;
                    $student_id = $detail ? $detail->batch . $detail->group . $detail->student_number . $detail->training_code : null;
                    return [
                        'user_id' => $student->user_id,
                        'student_id' => $student_id,
                        'name' => $student->user_lname . ', ' . $student->user_fname,
                    ];
                })->toArray(),
            ];
        })->toArray();

        return view('training.schools.edit', compact('school', 'batches', 'students', 'classes'));
    }

    public function update(Request $request, School $school)
    {
        $validated = $request->validate([
            'school_id' => 'required|string|unique:schools,school_id,' . $school->school_id . ',school_id',
            'name' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'course' => 'required|string|max:255',
            'semester_count' => 'required|integer|min:1',
            'passing_grade_min' => 'required|numeric',
            'passing_grade_max' => 'required|numeric',
            'failing_grade_min' => 'required|numeric',
            'failing_grade_max' => 'required|numeric',
            'terms' => 'required|array',
            'terms.*' => 'in:prelim,midterm,semi_final,final',
            'subjects' => 'required|array',
            'subjects.*.offer_code' => 'required|string',
            'subjects.*.name' => 'required|string',
            'subjects.*.instructor' => 'required|string',
            'subjects.*.schedule' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // Update school details
            $school->update([
                'name' => $validated['name'],
                'department' => $validated['department'],
                'course' => $validated['course'],
                'semester_count' => $validated['semester_count'],
                'terms' => $validated['terms'],
                'passing_grade_min' => $validated['passing_grade_min'],
                'passing_grade_max' => $validated['passing_grade_max'],
                'failing_grade_min' => $validated['failing_grade_min'],
                'failing_grade_max' => $validated['failing_grade_max'],
            ]);

            // Update subjects
            $existingSubjectIds = $school->subjects()->pluck('id')->toArray();
            $submittedSubjectIds = [];
            foreach ($validated['subjects'] as $subject) {
                // Check if subject exists by offer_code
                $existingSubject = $school->subjects()
                    ->where('offer_code', $subject['offer_code'])
                    ->first();

                if ($existingSubject) {
                    // Update existing subject
                    $existingSubject->update([
                        'name' => $subject['name'],
                        'instructor' => $subject['instructor'],
                        'schedule' => $subject['schedule']
                    ]);
                    $submittedSubjectIds[] = $existingSubject->id;
                } else {
                    // Create new subject
                    $newSubject = $school->subjects()->create($subject);
                    $submittedSubjectIds[] = $newSubject->id;
                }
            }
            // Only delete subjects that are NOT referenced by any grade submissions
            $subjectsToDelete = array_diff($existingSubjectIds, $submittedSubjectIds);
            if (!empty($subjectsToDelete)) {
                $referencedSubjectIds = \DB::table('grade_submission_student')
                    ->whereIn('subject_id', $subjectsToDelete)
                    ->pluck('subject_id')
                    ->toArray();
                $safeToDelete = array_diff($subjectsToDelete, $referencedSubjectIds);
                if (!empty($safeToDelete)) {
                    $school->subjects()->whereIn('id', $safeToDelete)->delete();
                }
            }

            // --- Handle Classes ---
            $existingClassIds = $school->classes()->pluck('class_id')->toArray();
            $submittedClassIds = [];

            if (isset($request->classes)) {
                foreach ($request->classes as $classData) {
                    $submittedClassIds[] = $classData['class_id'];
                    $class = \App\Models\ClassModel::where('class_id', $classData['class_id'])->first();
                    
                    if ($class) {
                        // Update existing class
                        $class->update([
                            'class_name' => $classData['name'],
                            'batch' => $classData['batch'],
                            'school_id' => $school->school_id // Ensure school_id is maintained
                        ]);
                    } else {
                        // Create new class
                        $class = new \App\Models\ClassModel();
                        $class->class_id = $classData['class_id'];
                        $class->class_name = $classData['name'];
                        $class->batch = $classData['batch'];
                        $class->school_id = $school->school_id;
                        $class->save();
                    }

                    // Sync students
                    if (isset($classData['student_ids'])) {
                        $class->students()->sync($classData['student_ids']);
                        
                        // Update student_details for assigned students
                        StudentDetail::whereIn('user_id', $classData['student_ids'])
                            ->update([
                                'school_id' => $school->school_id,
                                'class_id' => $class->class_id
                            ]);
                    }
                }
                
                // Only delete classes that are NOT referenced by any grade submissions
                $classesToDelete = array_diff($existingClassIds, $submittedClassIds);
                if (!empty($classesToDelete)) {
                    $referencedClassIds = \DB::table('grade_submissions')
                        ->whereIn('class_id', $classesToDelete)
                        ->pluck('class_id')
                        ->toArray();
                    
                    $safeToDelete = array_diff($classesToDelete, $referencedClassIds);
                    if (!empty($safeToDelete)) {
                        \App\Models\ClassModel::whereIn('class_id', $safeToDelete)->delete();
                    }
                }
            }

            DB::commit();
            return redirect()->route('training.manage-students')->with('success', 'School updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('School update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update school: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(School $school)
    {
        try {
            DB::beginTransaction();
            $school->delete();
            DB::commit();

            return redirect()->route('training.manage-students')
                ->with('success', 'School deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting school: ' . $e->getMessage());
        }
    }

    public function getTerms(School $school)
    {
        return response()->json([
            'terms' => $school->terms
        ]);
    }
}