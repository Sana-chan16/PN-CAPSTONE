<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\GradeSubmission;
use App\Models\PNUser;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    // Show the Subject Progress Analytics page
    public function showSubjectProgress()
    {
        // Get the first school's passing grade range as default
        $school = School::select('passing_grade_min', 'passing_grade_max')->first();
        
        return view('training.analytics.subject-progress', [
            'defaultSchool' => $school
        ]);
    }
    // Show the Subject Intervention Analytics page
    public function showSubjectIntervention()
    {
        // Get the first school's passing grade range as default
        $school = School::select('passing_grade_min', 'passing_grade_max')->first();
        
        return view('training.analytics.subject-intervention', [
            'defaultSchool' => $school
        ]);
    }

    // Get all schools
    public function getSchools()
    {
        $schools = School::select('school_id as id', 'name')->get();
        return response()->json($schools);
    }

    // Get classes for a school
    public function getClassesBySchool($schoolId)
    {
        $classes = ClassModel::where('school_id', $schoolId)
            ->select('class_id as id', 'class_name as name')
            ->get();
        return response()->json($classes);
    }

    // Get terms/semesters for a school
    public function getTermsBySchool($schoolId)
    {
        $school = School::where('school_id', $schoolId)->first();
        $terms = $school ? ($school->terms ?? []) : [];
        return response()->json($terms);
    }

    // Get submissions (semester/term/year) for a school/class
    public function getClassSubmissions($schoolId, $classId)
    {
        $submissions = GradeSubmission::where('school_id', $schoolId)
            ->where('class_id', $classId)
            ->orderByDesc('created_at')
            ->get();
            
        \Log::info('Submissions query:', [
            'school_id' => $schoolId,
            'class_id' => $classId,
            'count' => $submissions->count()
        ]);
        
        $result = $submissions->map(function($sub) {
            $label = [];
            if (!empty($sub->semester)) $label[] = 'Semester: ' . $sub->semester;
            if (!empty($sub->term)) $label[] = 'Term: ' . $sub->term;
            if (!empty($sub->academic_year)) $label[] = 'Year: ' . $sub->academic_year;
            
            // Check for incomplete grades only if submission is not approved
            $incompleteGrades = false;
            if ($sub->status !== 'approved') {
                $incompleteGrades = DB::table('grade_submission_subject')
                    ->where('grade_submission_id', $sub->id)
                    ->whereNull('grade')
                    ->exists();
            }
            
            // If no specific fields, use created_at as identifier
            if (empty($label)) {
                $label[] = 'Submission: ' . $sub->created_at->format('Y-m-d H:i:s');
            }
            
            return [
                'id' => $sub->id,
                'label' => implode(' | ', $label),
                'status' => $sub->status,
                'has_incomplete_grades' => $incompleteGrades
            ];
        });
        
        \Log::info('Formatted submissions:', $result->toArray());
        
        return response()->json($result);
    }

    // Fetch class grades for the selected school, class, and submission
    public function fetchSubjectInterventionData(\Illuminate\Http\Request $request)
    {
        $schoolId = $request->query('school_id');
        $classId = $request->query('class_id');
        $submissionId = $request->query('submission_id');
        
        if (!$schoolId || !$classId || !$submissionId) {
            return response()->json([]);
        }

        $school = School::where('school_id', $schoolId)->first();
        if (!$school) return response()->json([]);

        // Get the GradeSubmission by id
        $gradeSubmission = GradeSubmission::where('id', $submissionId)
            ->where('school_id', $schoolId)
            ->where('class_id', $classId)
            ->with(['subjects' => function($query) {
                $query->select('subjects.id', 'subjects.name');
            }])
            ->first();
            
        if (!$gradeSubmission) {
            return response()->json([
                'error' => 'Submission not found',
                'submission_status' => 'not_found'
            ]);
        }

        // Get unique subjects from the submission
        $subjects = $gradeSubmission->subjects->unique('id');
        
        // Get all grades for this submission, grouped by subject and student
        $grades = DB::table('grade_submission_subject')
            ->join('subjects', 'subjects.id', '=', 'grade_submission_subject.subject_id')
            ->where('grade_submission_subject.grade_submission_id', $gradeSubmission->id)
            ->where('grade_submission_subject.status', 'approved')
            ->select(
                'subjects.id as subject_id',
                'subjects.name as subject_name',
                'grade_submission_subject.user_id',
                'grade_submission_subject.grade',
                'grade_submission_subject.student_status'
            )
            ->orderBy('subjects.id')
            ->get();
            
        // Group grades by subject
        $groupedGrades = $grades->groupBy('subject_name');
        
        // Initialize results array
        $subjectResults = [];
        $processedSubjects = [];
        
        foreach ($subjects as $subject) {
            // Skip if we've already processed this subject
            if (in_array($subject->id, $processedSubjects)) {
                continue;
            }
            
            $processedSubjects[] = $subject->id;
            
            // Initialize counters for this subject
            $passed = 0;
            $failed = 0;
            $inc = 0;
            $dr = 0;
            $nc = 0;
            $pending = false;
            $needIntervention = false;
            $processedStudents = [];
            
            // Get grades for this specific subject
            $subjectGrades = $groupedGrades->get($subject->name, collect());
            
            // Process each student's grade for this subject
            foreach ($subjectGrades as $grade) {
                $studentId = $grade->user_id;
                
                // Skip if we've already processed this student for this subject
                if (in_array($studentId, $processedStudents)) {
                    continue;
                }
                
                $processedStudents[] = $studentId;
                $gradeValue = $grade->grade;
                
                // Check student status first
                if ($grade->student_status !== 'approved') {
                    continue; // Skip unapproved student grades
                }
                
                // Check grade status
                if ($gradeValue === 'INC') {
                    $inc++;
                    $pending = true;
                } elseif ($gradeValue === 'DR') {
                    $dr++;
                    $pending = true;
                } elseif ($gradeValue === 'NC') {
                    $nc++;
                    $needIntervention = true;
                } elseif (is_numeric($gradeValue)) {
                    $gradeValue = (float)$gradeValue;
                    if ($gradeValue >= $school->passing_grade_min && $gradeValue <= $school->passing_grade_max) {
                        $passed++;
                    } else {
                        $failed++;
                        $needIntervention = true;
                    }
                }
            }
            
            // Determine remarks
            $remarks = '';
            $totalGrades = $passed + $failed + $inc + $dr + $nc;
            $totalStudents = $totalGrades;
            
            if ($totalGrades === 0) {
                $remarks = 'No Submission Recorded';
            } elseif ($inc > 0 || $dr > 0 || $nc > 0 || $failed > 0) {
                // Mark as Need Intervention if there are any special grades or failing grades
                $remarks = 'Need Intervention';
            } else {
                $remarks = 'No Intervention Needed';
            }
            
            $subjectResults[] = [
                'subject' => $subject->name,
                'passed' => $passed,
                'failed' => $failed,
                'inc' => $inc,
                'dr' => $dr,
                'nc' => $nc,
                'remarks' => $remarks
            ];
        }
        
        return response()->json([
            'subjects' => $subjectResults,
            'submission' => [
                'term' => $gradeSubmission->term,
                'semester' => $gradeSubmission->semester,
                'academic_year' => $gradeSubmission->academic_year,
            ],
            'school' => [
                'name' => $school->name,
                'passing_grade_min' => $school->passing_grade_min,
                'passing_grade_max' => $school->passing_grade_max
            ],
            'class_name' => $gradeSubmission->classModel->class_name ?? 'Unknown Class'
        ]);
    }
    
    public function fetchSubjectProgressData(\Illuminate\Http\Request $request)
    {
        $schoolId = $request->query('school_id');
        $classId = $request->query('class_id');
        $submissionId = $request->query('submission_id');
        
        if (!$schoolId || !$classId || !$submissionId) {
            return response()->json([]);
        }

        $school = School::where('school_id', $schoolId)->first();
        if (!$school) return response()->json([]);

        // Get the GradeSubmission by id
        $gradeSubmission = GradeSubmission::where('id', $submissionId)
            ->where('school_id', $schoolId)
            ->where('class_id', $classId)
            ->first();
            
        if (!$gradeSubmission) {
            return response()->json([
                'error' => 'Submission not found',
                'submission_status' => 'not_found'
            ]);
        }

        // Get all grades for this submission
        $grades = DB::table('grade_submission_subject')
            ->join('subjects', 'subjects.id', '=', 'grade_submission_subject.subject_id')
            ->where('grade_submission_subject.grade_submission_id', $gradeSubmission->id)
            ->where('grade_submission_subject.student_status', 'approved')
            ->select(
                'subjects.id as subject_id',
                'subjects.name as subject_name',
                'grade_submission_subject.grade'
            )
            ->get();
            
        // Group grades by subject
        $groupedGrades = $grades->groupBy('subject_name');
        
        $subjectResults = [];
        
        foreach ($groupedGrades as $subjectName => $grades) {
            $passed = 0;
            $failed = 0;
            $inc = 0;
            $dr = 0;
            $nc = 0;
            
            // Count grades for this subject
            foreach ($grades as $grade) {
                if ($grade->grade === 'INC') {
                    $inc++;
                } elseif ($grade->grade === 'DR') {
                    $dr++;
                } elseif ($grade->grade === 'NC') {
                    $nc++;
                } elseif (is_numeric($grade->grade)) {
                    $gradeValue = (float)$grade->grade;
                    if ($gradeValue >= $school->passing_grade_min && $gradeValue <= $school->passing_grade_max) {
                        $passed++;
                    } else {
                        $failed++;
                    }
                }
            }
            
            $totalStudents = $passed + $failed + $inc + $dr + $nc;
            
            $subjectResults[] = [
                'subject' => $subjectName,
                'passed' => $passed,
                'failed' => $failed,
                'inc' => $inc,
                'dr' => $dr,
                'nc' => $nc,
                'total_students' => $totalStudents
            ];
        }
        
        return response()->json([
            'subjects' => $subjectResults,
            'submission' => [
                'term' => $gradeSubmission->term,
                'semester' => $gradeSubmission->semester,
                'academic_year' => $gradeSubmission->academic_year,
            ],
            'school' => [
                'name' => $school->name,
                'passing_grade_min' => $school->passing_grade_min,
                'passing_grade_max' => $school->passing_grade_max
            ],
            'class_name' => $gradeSubmission->classModel->class_name ?? 'Unknown Class'
        ]);
    }
}
