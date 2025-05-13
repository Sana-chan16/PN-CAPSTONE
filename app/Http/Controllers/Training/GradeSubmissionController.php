<?php

namespace App\Http\Controllers\Training;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\School;
use App\Models\GradeSubmission;
use App\Models\Subject;
use App\Models\PNUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\GradeSubmissionStatusUpdated;

class GradeSubmissionController extends Controller
{
    public function index()
    {
        $gradeSubmissions = GradeSubmission::with(['school', 'classModel'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('training.grade-submissions.index', compact('gradeSubmissions'));
    }

    public function create()
    {
        $schools = School::all();
        $classes = ClassModel::all();
        return view('training.grade-submissions.create', compact('schools', 'classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,school_id',
            'class_id' => 'required|exists:classes,class_id',
            'semester' => 'required',
            'term' => 'required|string',
            'academic_year' => 'required|string',
            'subject_ids' => 'required|array',
            'subject_ids.*' => 'exists:subjects,id'
        ]);

        try {
            DB::beginTransaction();

            // Get the class and its students using where clause instead of findOrFail
            $class = ClassModel::with('students')
                ->where('class_id', $validated['class_id'])
                ->firstOrFail();

            Log::info('Class students', [
                'class_id' => $validated['class_id'],
                'students_count' => $class->students->count(),
                'students' => $class->students->pluck('user_id')->toArray()
            ]);
            
            // Create the grade submission
            $gradeSubmission = GradeSubmission::create([
                'school_id' => $validated['school_id'],
                'class_id' => $validated['class_id'],
                'semester' => $validated['semester'],
                'term' => $validated['term'],
                'academic_year' => $validated['academic_year'],
                'subject_ids' => $validated['subject_ids'],
                'status' => 'pending'
            ]);

            // Associate students with the grade submission
            foreach ($class->students as $student) {
                foreach ($validated['subject_ids'] as $subjectId) {
                    $gradeSubmission->students()->attach($student->user_id, [
                        'subject_id' => $subjectId,
                        'status' => 'pending'
                    ]);
                }
            }

            Log::info('Grade submission created', [
                'submission_id' => $gradeSubmission->id,
                'students_count' => $gradeSubmission->students->count(),
                'students' => $gradeSubmission->students->pluck('user_id')->toArray()
            ]);

            DB::commit();

            return redirect()->route('training.grade-submissions.index')
                ->with('success', 'Grade submission created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Grade submission creation failed: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Failed to create grade submission. Please try again: ' . $e->getMessage());
        }
    }

    public function show(GradeSubmission $gradeSubmission)
    {
        $gradeSubmission->load(['school', 'classModel']);
        return view('training.grade-submissions.show', compact('gradeSubmission'));
    }

    public function getSubjectsBySchoolAndClass(Request $request)
    {
        try {
            $schoolId = $request->input('school_id');
            $classId = $request->input('class_id');

            $school = School::findOrFail($schoolId);
            $subjects = $school->subjects()
                ->select('id', 'name', 'offer_code')
                ->get();

            return response()->json($subjects);
        } catch (\Exception $e) {
            Log::error('Error fetching subjects: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch subjects'], 500);
        }
    }

    public function destroy(GradeSubmission $gradeSubmission)
    {
        $gradeSubmission->delete();
        return redirect()->route('training.grade-submissions.index')->with('success', 'Grade submission deleted successfully.');
    }

    public function monitor(Request $request)
    {
        // Get all classes for the filter dropdown
        $classes = DB::table('classes')
            ->select('class_id', 'class_name')
            ->orderBy('class_name')
            ->get();

        // Get unique periods from grade submissions
        $periods = DB::table('grade_submissions')
            ->select('semester', 'term', 'academic_year')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->orderBy('semester')
            ->orderBy('term')
            ->get();

        // Base query for grade submissions
        $query = DB::table('grade_submissions')
            ->join('schools', 'grade_submissions.school_id', '=', 'schools.school_id')
            ->join('classes', 'grade_submissions.class_id', '=', 'classes.class_id')
            ->select(
                'grade_submissions.*',
                'schools.name as school_name',
                'schools.school_id as school_id',
                'classes.class_name',
                'classes.class_id'
            );

        // Apply filters
        if ($request->filled('class_id')) {
            $query->where('grade_submissions.class_id', $request->class_id);
        }
        if ($request->filled('period')) {
            $parts = explode('_', $request->period);
            if (count($parts) === 3) {
                list($semester, $term, $academic_year) = $parts;
                $query->whereRaw('LOWER(TRIM(grade_submissions.semester)) = ?', [strtolower(trim($semester))])
                      ->whereRaw('LOWER(TRIM(grade_submissions.term)) = ?', [strtolower(trim($term))])
                      ->whereRaw('TRIM(grade_submissions.academic_year) = ?', [trim($academic_year)]);
            } else {
                list($semester, $term) = $parts;
                $query->whereRaw('LOWER(TRIM(grade_submissions.semester)) = ?', [strtolower(trim($semester))])
                      ->whereRaw('LOWER(TRIM(grade_submissions.term)) = ?', [strtolower(trim($term))]);
            }
        }

        $gradeSubmissions = $query->get();

        // After fetching $gradeSubmissions
        \Log::info('Monitor: grade submissions', [
            'count' => $gradeSubmissions->count(),
            'submissions' => $gradeSubmissions->map(function($gs) {
                return [
                    'id' => $gs->id,
                    'class_id' => $gs->class_id,
                    'class_name' => $gs->class_name ?? null,
                    'school_id' => $gs->school_id,
                    'school_name' => $gs->school_name ?? null,
                ];
            })
        ]);

        // Initialize empty collections
        $classSubmissions = collect([]);
        $classSubjects = collect([]);

        // For each grade submission, get the student submissions
        foreach ($gradeSubmissions as $gradeSubmission) {
            $studentSubmissions = DB::table('grade_submission_student')
                ->join('pnph_users', 'grade_submission_student.user_id', '=', 'pnph_users.user_id')
                ->join('subjects', 'grade_submission_student.subject_id', '=', 'subjects.id')
                ->where('grade_submission_student.grade_submission_id', $gradeSubmission->id)
                ->select(
                    'grade_submission_student.*',
                    'pnph_users.user_id',
                    'pnph_users.user_fname as user_fname',
                    'pnph_users.user_lname as user_lname',
                    'subjects.name as subject_name',
                    'subjects.id as subject_id'
                )
                ->get();

            // Add school, class, and term information to each submission
            $studentSubmissions = $studentSubmissions->map(function($sub) use ($gradeSubmission) {
                $sub->school_name = $gradeSubmission->school_name;
                $sub->school_id = $gradeSubmission->school_id;
                $sub->class_name = $gradeSubmission->class_name;
                $sub->class_id = $gradeSubmission->class_id;
                $sub->term = $gradeSubmission->term;
                $sub->semester = $gradeSubmission->semester;
                $sub->academic_year = $gradeSubmission->academic_year;
                return $sub;
            });

            // Group submissions by class
            $classId = $gradeSubmission->class_id;
            if (!$classSubmissions->has($classId)) {
                $classSubmissions->put($classId, collect([]));
            }

            // Group by student and term
            $studentGroups = $studentSubmissions->groupBy(function($item) {
                return $item->user_id . '|' . $item->user_fname . ' ' . $item->user_lname;
            });

            foreach ($studentGroups as $studentKey => $records) {
                $classSubmissions->get($classId)->push($records);
            }

            // Group subjects by class
            if (!$classSubjects->has($classId)) {
                $classSubjects->put($classId, collect([]));
            }
            $classSubjects->get($classId)->push(...$studentSubmissions->pluck('subject_name', 'subject_id')
                ->map(function($name, $id) {
                    return (object)['id' => $id, 'name' => $name];
                })
                ->values()
                ->unique('id')
                ->values());
        }

        // Process each class's subjects to ensure uniqueness
        $processedClassSubjects = collect();
        foreach ($classSubjects as $classId => $subjects) {
            $processedClassSubjects->put($classId, $subjects->unique('id')->values());
        }

        // Process each class's submissions to ensure proper grouping
        $processedClassSubmissions = collect();
        foreach ($classSubmissions as $classId => $submissions) {
            // Group by student
            $studentGroups = $submissions->groupBy(function($item) {
                return $item->first()->user_id . '|' . $item->first()->user_fname . ' ' . $item->first()->user_lname;
            });

            // Add to processed submissions
            $processedClassSubmissions->put($classId, $studentGroups);
        }

        return view('training.grade-submissions.monitor', [
            'classSubmissions' => $processedClassSubmissions,
            'classes' => $classes,
            'classSubjects' => $processedClassSubjects,
            'periods' => $periods
        ]);
    }

    public function viewStudentSubmission(GradeSubmission $gradeSubmission, PNUser $student)
    {
        // Verify that the student is part of this grade submission
        if (!$gradeSubmission->students()->where('grade_submission_student.user_id', $student->user_id)->exists()) {
            return redirect()->route('training.grade-submissions.monitor')
                ->with('error', 'Student is not part of this grade submission.');
        }

        $subjects = Subject::whereIn('id', $gradeSubmission->subject_ids)->get();
        $studentGrades = $gradeSubmission->students()
            ->where('grade_submission_student.user_id', $student->user_id)
            ->withPivot(['grade', 'proof_path', 'submitted_at'])
            ->first();

        return view('training.grade-submissions.view-student', compact(
            'gradeSubmission',
            'student',
            'subjects',
            'studentGrades'
        ));
    }

    public function updateStatus(Request $request)
    {
        \Log::info('updateStatus called', $request->all());
        $request->validate([
            'submission_id' => 'required|exists:grade_submission_student,id',
            'action' => 'required|in:approve,reject'
        ]);

        try {
            DB::beginTransaction();

            // Get the submission row
            $submission = DB::table('grade_submission_student')
                ->where('id', $request->submission_id)
                ->first();

            if (!$submission) {
                throw new \Exception('Submission not found');
            }

            // Log before update
            \Log::info('About to update grade_submission_student', [
                'grade_submission_id' => $submission->grade_submission_id,
                'user_id' => $submission->user_id,
                'action' => $request->action
            ]);

            // Update ALL subjects for this student in this grade submission
            $affected = DB::table('grade_submission_student')
                ->where('grade_submission_id', $submission->grade_submission_id)
                ->where('user_id', $submission->user_id)
                ->update([
                    'status' => $request->action,
                    'updated_at' => now()
                ]);

            \Log::info('Rows affected by main update', ['affected' => $affected]);

            // Fallback: If nothing was updated, try updating by id (single row)
            if ($affected === 0) {
                $fallbackAffected = DB::table('grade_submission_student')
                    ->where('id', $request->submission_id)
                    ->update([
                        'status' => $request->action,
                        'updated_at' => now()
                    ]);
                \Log::info('Rows affected by fallback update', ['affected' => $fallbackAffected]);
                if ($fallbackAffected === 0) {
                    throw new \Exception('Failed to update submission status (no rows affected)');
                }
            }

            // Get the grade submission for notification
            $gradeSubmission = GradeSubmission::find($submission->grade_submission_id);
            
            // Get the student for notification
            $student = PNUser::where('user_id', $submission->user_id)->first();

            if ($student) {
                // Send notification to student
                $notification = new GradeSubmissionStatusUpdated(
                    $gradeSubmission,
                    $request->action
                );
                $student->notify($notification);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Grade submission has been ' . $request->action . 'd successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update grade submission status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update grade submission status: ' . $e->getMessage());
        }
    }
}
