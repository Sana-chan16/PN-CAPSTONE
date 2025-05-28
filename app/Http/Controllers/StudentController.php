<?php

namespace App\Http\Controllers;

use App\Models\GradeSubmission;
use App\Models\GradeSubmissionProof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $filterKey = $request->query('filter_key');

        $gradeSubmissionsQuery = GradeSubmission::whereHas('students', function($query) use ($user) {
            $query->where('grade_submission_subject.user_id', $user->user_id);
        })
        ->with([
            'classModel',
            'subjects',
            'students' => function($query) use ($user) {
                $query->where('grade_submission_subject.user_id', $user->user_id);
            }
        ])
        ->orderBy('created_at', 'desc');

        if ($filterKey) {
            $gradeSubmissionsQuery->where(DB::raw("CONCAT(semester, ' ', term, ' ', academic_year)"), $filterKey);
        }

        $gradeSubmissions = $gradeSubmissionsQuery->get();

        // For filter dropdown
        $filterOptions = GradeSubmission::whereHas('students', function($query) use ($user) {
            $query->where('grade_submission_subject.user_id', $user->user_id);
        })
        ->select(DB::raw("CONCAT(semester, ' ', term, ' ', academic_year) AS filter_key"))
        ->distinct()
        ->pluck('filter_key')
        ->sortDesc()
        ->values()
        ->all();

        // Count subject statuses for dashboard cards
        $subjectStatuses = DB::table('grade_submission_subject as gss')
            ->where('gss.user_id', $user->user_id)
            ->where('gss.status', 'approved')
            ->select('gss.grade')
            ->get();

        $passed = 0;
        $failed = 0;
        $inc = 0;
        $not_submitted = 0;
        $nc = 0;
        $dropout = 0;
        foreach ($subjectStatuses as $row) {
            if ($row->grade === null) {
                $not_submitted++;
            } elseif (is_numeric($row->grade) && $row->grade >= 1.0 && $row->grade <= 3.0) {
                $passed++;
            } elseif (is_numeric($row->grade) && $row->grade > 3.0 && $row->grade <= 5.0) {
                $failed++;
            } elseif (strtoupper($row->grade) === 'INC') {
                $inc++;
            } elseif (strtoupper($row->grade) === 'NC') {
                $nc++;
            } elseif (strtoupper($row->grade) === 'DR' || strtoupper($row->grade) === 'DROPOUT' || strtoupper($row->grade) === 'DROP OUT') {
                $dropout++;
            }
        }

        // Transform the data to avoid the ambiguous user_id issue
        $gradeSubmissions->each(function ($submission) use ($user) {
            $submission->subjects->each(function ($subject) use ($submission, $user) {
                $studentSubmission = DB::table('grade_submission_subject')
                    ->where('grade_submission_id', $submission->id)
                    ->where('user_id', $user->user_id)
                    ->where('subject_id', $subject->id)
                    ->first();
                $subject->student_submission = $studentSubmission;
            });
        });

        return view('student.dashboard', compact('gradeSubmissions', 'filterOptions', 'filterKey', 'passed', 'failed', 'inc', 'not_submitted', 'nc', 'dropout'));
    }

    public function showSubmissionForm($submissionId)
    {
        $user = Auth::user();

        // Fetch the grade submission and eager load classModel and students
        $gradeSubmission = GradeSubmission::where('id', $submissionId)
            ->whereHas('students', function($query) use ($user) {
                $query->where('grade_submission_subject.user_id', $user->user_id);
            })
            ->with([
                'classModel',
                'subjects',
                'students' => function($query) use ($user) {
                    $query->where('grade_submission_subject.user_id', $user->user_id);
                }
            ])
            ->firstOrFail();

        // Get all subjects and their grades for this student
        $subjects = DB::table('subjects')
            ->join('grade_submission_subject as gss', 'subjects.id', '=', 'gss.subject_id')
            ->where('gss.grade_submission_id', $submissionId)
            ->where('gss.user_id', $user->user_id)
            ->select('subjects.*', 'gss.grade', 'gss.status')
            ->get();

        // If no subjects found, try getting them directly from the grade submission
        if ($subjects->isEmpty()) {
            $subjects = $gradeSubmission->subjects;
        }

        return view('student.submission_form', compact('gradeSubmission', 'subjects'));
    }

    public function submitGrades(Request $request, $submissionId)
    {
        $user = Auth::user();

        \Log::info('Starting grade submission process:', [
            'submission_id' => $submissionId,
            'user_id' => $user->user_id
        ]);

        // Find the grade submission and verify the student is associated
        $gradeSubmission = GradeSubmission::where('id', $submissionId)
            ->whereHas('students', function($query) use ($user) {
                $query->where('grade_submission_subject.user_id', $user->user_id);
            })
            ->firstOrFail();

        \Log::info('Found grade submission:', [
            'submission_id' => $gradeSubmission->id,
            'school_id' => $gradeSubmission->school_id,
            'class_id' => $gradeSubmission->class_id
        ]);

        // Validate the submitted grades and proof
        $validated = $request->validate([
            'grades' => 'required|array',
            'grades.*' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!is_numeric($value) && !in_array(strtoupper($value), ['INC', 'NC', 'DR'])) {
                        $fail('The grade must be a number between 1.0-5.0 or one of: INC, NC, DR.');
                    }
                    if (is_numeric($value) && ($value < 1.0 || $value > 5.0)) {
                        $fail('The numeric grade must be between 1.0 and 5.0.');
                    }
                },
            ],
            'proof' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240' // 10MB max
        ]);

        \Log::info('Validated grades:', [
            'grades' => $validated['grades']
        ]);

        try {
            DB::beginTransaction();

            // Update grades and status in the pivot table (this will always replace old grades, including rejected ones)
            foreach ($validated['grades'] as $subjectId => $grade) {
                $result = DB::table('grade_submission_subject')
                    ->where('grade_submission_id', $submissionId)
                    ->where('subject_id', $subjectId)
                    ->where('user_id', $user->user_id)
                    ->update([
                        'grade' => $grade,
                        'status' => 'submitted',
                        'updated_at' => now()
                    ]);

                \Log::info('Updated grade for subject:', [
                    'subject_id' => $subjectId,
                    'grade' => $grade,
                    'result' => $result
                ]);
            }

            // Handle file upload
            $file = $request->file('proof');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('proofs', $fileName, 'public');

            // Create or update the proof record
            $proof = GradeSubmissionProof::updateOrCreate(
                [
                    'grade_submission_id' => $submissionId,
                    'user_id' => $user->user_id
                ],
                [
                    'file_path' => $filePath,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientOriginalExtension(),
                    'status' => 'pending'
                ]
            );

            \Log::info('Proof uploaded:', [
                'proof_id' => $proof->id,
                'file_path' => $filePath
            ]);

            // Verify the grades were stored
            $storedGrades = DB::table('grade_submission_subject')
                ->where('grade_submission_id', $submissionId)
                ->where('user_id', $user->user_id)
                ->get();

            \Log::info('Stored grades verification:', [
                'count' => $storedGrades->count(),
                'grades' => $storedGrades->toArray()
            ]);

            DB::commit();

            return redirect()->route('student.dashboard')->with('success', 'Grades and proof submitted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error submitting grades: ' . $e->getMessage());
            return redirect()->route('student.dashboard')->with('error', 'An error occurred while submitting grades.');
        }
    }

    public function viewSubmission($submissionId)
    {
        $user = Auth::user();
        $gradeSubmission = GradeSubmission::where('id', $submissionId)
            ->whereHas('students', function($query) use ($user) {
                $query->where('grade_submission_subject.user_id', $user->user_id);
            })
            ->with([
                'classModel',
                'subjects',
                'students' => function($query) use ($user) {
                    $query->where('grade_submission_subject.user_id', $user->user_id);
                }
            ])
            ->firstOrFail();

        // Get all subjects and their grades for this student
        $studentSubjectEntries = DB::table('subjects')
            ->join('grade_submission_subject as gss', 'subjects.id', '=', 'gss.subject_id')
            ->where('gss.grade_submission_id', $submissionId)
            ->where('gss.user_id', $user->user_id)
            ->select('subjects.name as subject_name', 'gss.grade', 'gss.status')
            ->get();

        return view('student.view_submission', compact('gradeSubmission', 'studentSubjectEntries'));
    }

    public function submissionsList(Request $request)
    {
        $user = Auth::user();
        $filterKey = $request->query('filter_key');

        $gradeSubmissionsQuery = GradeSubmission::whereHas('students', function($query) use ($user) {
            $query->where('grade_submission_subject.user_id', $user->user_id);
        })
        ->with([
            'classModel',
            'subjects',
            'students' => function($query) use ($user) {
                $query->where('grade_submission_subject.user_id', $user->user_id);
            }
        ])
        ->orderBy('created_at', 'desc');

        if ($filterKey) {
            $gradeSubmissionsQuery->where(DB::raw("CONCAT(semester, ' ', term, ' ', academic_year)"), $filterKey);
        }

        $gradeSubmissions = $gradeSubmissionsQuery->get();

        // For filter dropdown
        $filterOptions = GradeSubmission::whereHas('students', function($query) use ($user) {
            $query->where('grade_submission_subject.user_id', $user->user_id);
        })
        ->select(DB::raw("CONCAT(semester, ' ', term, ' ', academic_year) AS filter_key"))
        ->distinct()
        ->pluck('filter_key')
        ->sortDesc()
        ->values()
        ->all();

        return view('student.grade_submissions_list', compact('gradeSubmissions', 'filterOptions', 'filterKey'));
    }

    public function gradeStatus(Request $request)
    {
        $studentId = auth()->user()->id;
        $selectedKey = $request->query('term_sem');

        // Build dropdown options: unique combinations of semester, term, academic year
        $semesters = DB::table('grade_submission_subject as gss')
            ->join('grade_submissions as gs', 'gss.grade_submission_id', '=', 'gs.id')
            ->where('gss.user_id', $studentId)
            ->select(DB::raw("CONCAT(gs.semester, ' | Term: ', gs.term, ' | AY: ', gs.academic_year) as label"), 'gs.semester', 'gs.term', 'gs.academic_year', 'gs.id as submission_id')
            ->distinct()
            ->orderByDesc('gs.academic_year')
            ->orderByDesc('gs.term')
            ->get();

        // Default to the latest if none selected
        if (!$selectedKey && $semesters->count()) {
            $selectedKey = $semesters->first()->label;
        }

        // Parse selectedKey into its parts
        $selectedSemester = null;
        $selectedTerm = null;
        $selectedYear = null;
        if ($selectedKey) {
            // Example: "1st Sem | Term: 1 | AY: 2023-2024"
            $parts = explode(' | ', $selectedKey);
            if (count($parts) === 3) {
                $selectedSemester = trim($parts[0]);
                $selectedTerm = trim(str_replace('Term: ', '', $parts[1]));
                $selectedYear = trim(str_replace('AY: ', '', $parts[2]));
            }
        }

        // Get the grade submission for the selected combination
        $submission = DB::table('grade_submissions')
            ->where('semester', $selectedSemester)
            ->where('term', $selectedTerm)
            ->where('academic_year', $selectedYear)
            ->first();

        $grades = collect();
        if ($submission) {
            $allSubjects = DB::table('grade_submission_subject as gss')
                ->join('subjects', 'gss.subject_id', '=', 'subjects.id')
                ->where('gss.grade_submission_id', $submission->id)
                ->where('gss.user_id', $studentId)
                ->select('subjects.id', 'subjects.name', 'gss.grade', 'gss.status')
                ->distinct()
                ->get();

            foreach ($allSubjects as $subject) {
                $grades->push((object)[
                    'subject_name' => $subject->name,
                    'grade' => $subject->grade ?? null,
                    'status' => $subject->status ?? null,
                    'semester' => $submission->semester,
                    'term' => $submission->term,
                    'academic_year' => $submission->academic_year
                ]);
            }
        }

        // Only show approved grades in the table
        $grades = $grades->filter(fn($g) => $g->status === 'approved')->values();

        // Count summary (only approved, for selected term/sem)
        $selectedSummary = [
            'passed' => $grades->filter(fn($g) => is_numeric($g->grade) && $g->grade >= 1.0 && $g->grade <= 3.0)->count(),
            'failed' => $grades->filter(fn($g) => is_numeric($g->grade) && $g->grade > 3.0 && $g->grade <= 5.0)->count(),
            'inc' => $grades->filter(fn($g) => strtoupper($g->grade) === 'INC')->count(),
            'nc' => $grades->filter(fn($g) => strtoupper($g->grade) === 'NC')->count(),
            'dr' => $grades->filter(fn($g) => in_array(strtoupper($g->grade), ['DR', 'DROPOUT', 'DROP OUT']))->count(),
        ];

        return view('student.grade-status.grade_status', [
            'grades' => $grades,
            'semesters' => $semesters,
            'selectedKey' => $selectedKey,
            'selectedSummary' => $selectedSummary,
            'selectedSemester' => $selectedSemester,
            'selectedTerm' => $selectedTerm,
            'selectedYear' => $selectedYear,
        ]);
    }

    public function editProfile()
    {
        $user = Auth::user();
        return view('student.edit_profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $filename = 'profile_'.$user->user_id.'.'.$image->getClientOriginalExtension();
            // Delete old profile images with any allowed extension
            foreach (['jpg', 'jpeg', 'png'] as $ext) {
                $oldPath = storage_path('app/public/profile_images/profile_' . $user->user_id . '.' . $ext);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $path = $image->storeAs('profile_images', $filename, 'public');
        }

        return redirect()->back()->with('success', 'Profile updated!');
    }

    // Show only passed subjects
    public function passedSubjects() {
        $user = Auth::user();
        $subjects = DB::table('grade_submission_subject as gss')
            ->join('subjects', 'gss.subject_id', '=', 'subjects.id')
            ->where('gss.user_id', $user->user_id)
            ->whereNotNull('gss.grade')
            ->whereRaw('gss.grade >= 1.0 and gss.grade <= 3.0')
            ->where('gss.status', 'approved')
            ->select('subjects.name as subject_name', 'gss.grade')
            ->get();
        return view('student.subjects_passed', compact('subjects'));
    }
    // Show only failed subjects
    public function failedSubjects() {
        $user = Auth::user();
        $subjects = DB::table('grade_submission_subject as gss')
            ->join('subjects', 'gss.subject_id', '=', 'subjects.id')
            ->where('gss.user_id', $user->user_id)
            ->whereNotNull('gss.grade')
            ->whereRaw('gss.grade > 3.0 and gss.grade <= 5.0')
            ->where('gss.status', 'approved')
            ->select('subjects.name as subject_name', 'gss.grade')
            ->get();
        return view('student.subjects_failed', compact('subjects'));
    }
    // Show only INC subjects
    public function incSubjects() {
        $user = Auth::user();
        $subjects = DB::table('grade_submission_subject as gss')
            ->join('subjects', 'gss.subject_id', '=', 'subjects.id')
            ->where('gss.user_id', $user->user_id)
            ->whereRaw('UPPER(gss.grade) = "INC"')
            ->where('gss.status', 'approved')
            ->select('subjects.name as subject_name', 'gss.grade')
            ->get();
        return view('student.subjects_inc', compact('subjects'));
    }
    // Show only NC subjects
    public function ncSubjects() {
        $user = Auth::user();
        $subjects = DB::table('grade_submission_subject as gss')
            ->join('subjects', 'gss.subject_id', '=', 'subjects.id')
            ->where('gss.user_id', $user->user_id)
            ->whereRaw('UPPER(gss.grade) = "NC"')
            ->where('gss.status', 'approved')
            ->select('subjects.name as subject_name', 'gss.grade')
            ->get();
        return view('student.subjects_nc', compact('subjects'));
    }
    // Show only DR subjects
    public function drSubjects() {
        $user = Auth::user();
        $subjects = DB::table('grade_submission_subject as gss')
            ->join('subjects', 'gss.subject_id', '=', 'subjects.id')
            ->where('gss.user_id', $user->user_id)
            ->whereRaw('UPPER(gss.grade) IN ("DR", "DROPOUT", "DROP OUT")')
            ->where('gss.status', 'approved')
            ->select('subjects.name as subject_name', 'gss.grade')
            ->get();
        return view('student.subjects_dr', compact('subjects'));
    }
} 