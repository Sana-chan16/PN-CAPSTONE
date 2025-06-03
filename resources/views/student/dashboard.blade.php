@extends('layouts.student_layout')

@section('content')
<div class="dashboard-container">
    <h1>My Grade Submissions</h1>
    
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Grade Statistics Cards -->
    <div class="grade-stats-container">
        <div class="grade-stat-wrapper">
            <div class="grade-card pass" onclick="openGradeModal('pass-modal')">
                <div class="grade-card-content">
                    <h3>PASS</h3>
                    <p class="grade-count">{{ $gradeStats['pass'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="grade-stat-wrapper">
            <div class="grade-card fail" onclick="openGradeModal('fail-modal')">
                <div class="grade-card-content">
                    <h3>FAIL</h3>
                    <p class="grade-count">{{ $gradeStats['fail'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="grade-stat-wrapper">
            <div class="grade-card inc" onclick="openGradeModal('inc-modal')">
                <div class="grade-card-content">
                    <h3>INC</h3>
                    <p class="grade-count">{{ $gradeStats['inc'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="grade-stat-wrapper">
            <div class="grade-card nc" onclick="openGradeModal('nc-modal')">
                <div class="grade-card-content">
                    <h3>NC</h3>
                    <p class="grade-count">{{ $gradeStats['nc'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="grade-stat-wrapper">
            <div class="grade-card dr" onclick="openGradeModal('dr-modal')">
                <div class="grade-card-content">
                    <h3>DR</h3>
                    <p class="grade-count">{{ $gradeStats['dr'] }}</p>
                </div>
            </div>
        </div>
    </div>

    @if($gradeSubmissions->isEmpty())
        <div class="no-submissions">
            <p>No grade submissions found.</p>
        </div>
    @else
        <div class="submissions-grid">
            @foreach($gradeSubmissions as $submission)
                <div class="submission-card">
                    <div class="card-header">
                        <h3>{{ $submission->term ?? 'N/A' }}</h3>
                    </div>
                    <div class="card-content">
                        <div class="info-row">
                            <span class="label">Semester:</span>
                            <span class="value">{{ $submission->semester ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Academic Year:</span>
                            <span class="value">{{ $submission->academic_year ?? 'N/A' }}</span>
                        </div>

                        @php
                            // Get the student's pivot data
                            $studentPivot = $submission->students->where('pivot.user_id', Auth::id())->first();
                            $pivotStatus = $studentPivot ? ($studentPivot->pivot->status ?? 'pending') : 'pending';
                            
                            // Get the latest proof status
                            $proof = $submission->proofs->where('user_id', Auth::id())->sortByDesc('created_at')->first();
                            $proofStatus = $proof ? $proof->status : null;
                            
                            // Determine the overall status to display
                            $overallStatus = $pivotStatus; // Default to pivot status
                            
                            // If there's a proof with a more specific status, use that
                            if ($proofStatus && in_array($proofStatus, ['approved', 'rejected'])) {
                                $overallStatus = $proofStatus;
                            } elseif ($proofStatus === 'pending' && $pivotStatus === 'submitted') {
                                $overallStatus = 'pending_review';
                            }
                        @endphp
                        
                        <div class="info-row">
                            <span class="label">Status:</span>
                            <span class="status {{ $overallStatus }}">
                                @if($overallStatus === 'pending_review')
                                    Pending Review
                                @else
                                    {{ ucfirst($overallStatus) }}
                                @endif
                            </span>
                        </div>
                        
                        <div class="info-row">
                            <span class="label">Submitted:</span>
                            <span class="date">{{ $submission->created_at ? $submission->created_at->format('M d, Y') : 'N/A' }}</span>
                        </div>

                        {{-- Add the button based on submission status --}}
                        <div class="card-actions">
                            @php
                                // Check if the student has any grades submitted for this submission
                                $hasGrades = $submission->students->contains('pivot.user_id', Auth::id()) && 
                                          $submission->students->where('pivot.user_id', Auth::id())->first()->pivot->grade !== null;
                                
                                // Check if there's a proof submitted
                                $hasProof = $proof !== null;
                            @endphp
                            
                            @if(!$hasGrades && !$hasProof)
                                {{-- New submission - no grades or proof yet --}}
                                <a href="{{ route('student.submit-grades.show', $submission->id) }}" class="btn-submit-grades">Submit Grades</a>
                            @elseif($overallStatus === 'rejected' || $pivotStatus === 'rejected' || $proofStatus === 'rejected')
                                <a href="{{ route('student.submit-grades.show', $submission->id) }}" class="btn-submit-grades">Resubmit Grades</a>
                            @elseif(in_array($overallStatus, ['submitted', 'pending_review', 'pending']))
                                <a href="{{ route('student.view-submission', $submission->id) }}" class="btn-view-submission">View Submission</a>
                            @elseif($overallStatus === 'approved')
                                <a href="{{ route('student.view-submission', $submission->id) }}" class="btn-view-submission">View Approved Submission</a>
                            @else
                                <a href="{{ route('student.submit-grades.show', $submission->id) }}" class="btn-submit-grades">Submit Grades</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
.dashboard-container {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

/* Grade Statistics Cards Styles */
.grade-stats-container {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 25px;
    margin-bottom: 30px;
    padding: 10px;
}

.grade-card {
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    padding: 15px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    color: white;
    text-align: center;
    width: 100%;
    height: 120px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.grade-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.15);
}

.grade-card.pass {
    background-color: #28a745;
}

.grade-card.fail {
    background-color: #dc3545;
}

.grade-card.inc {
    background-color: #ffc107;
    color: #212529; /* Darker text for better contrast on yellow */
}

.grade-card.nc {
    background-color: #6c757d;
}

.grade-card.dr {
    background-color: #17a2b8;
}

.grade-card-content {
    flex-grow: 1;
}

.grade-card-content h3 {
    margin: 0 0 5px 0;
    font-size: 1.2rem;
    font-weight: 700;
}

.grade-count {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0 0 5px 0;
}

.grade-desc {
    font-size: 0.8rem;
    color: #6c757d;
    margin: 0;
}

.grade-desc-below {
    font-size: 0.9rem;
    color: #333;
    margin: 5px 0 0 0;
    text-align: center;
    font-weight: 500;
}

.grade-stat-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: 0 5px;
}

@media (max-width: 992px) {
    .grade-stats-container {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .grade-stats-container {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .grade-stats-container {
        grid-template-columns: 1fr;
    }
}

h1 {
    color: #333;
    margin-bottom: 20px;
    font-size: 24px;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: .25rem;
}

.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}

.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}

.submissions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.submission-card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.card-header {
    background: #f8f9fa;
    padding: 15px;
    border-bottom: 1px solid #eee;
}

.card-header h3 {
    margin: 0;
    color: #333;
    font-size: 18px;
}

.card-content {
    padding: 15px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.info-row:last-child {
    margin-bottom: 0;
}

.label {
    color: #666;
    font-weight: 500;
}

.value,
.date {
    color: #333;
}

.status {
    padding: 4px 8px;
    border-radius: 3px;
    font-size: 14px;
    font-weight: 500;
}

.status.pending {
    background: #fff3cd;
    color: #856404;
}

.status.approved {
    background: #d4edda;
    color: #155724;
}

.status.rejected {
    background: #f8d7da;
    color: #721c24;
}

.status.submitted {
     background-color: #cce5ff;
     color: #004085;
}

.no-submissions {
    text-align: center;
    padding: 40px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.no-submissions p {
    color: #6c757d;
    font-size: 1.1em;
    margin: 0;
}

@media (max-width: 768px) {
    .submissions-grid {
        grid-template-columns: 1fr;
    }
}

/* Styles for the subjects list (removed as subjects are not listed directly on the card anymore) */
/*
.card-content h4 {
    margin-top: 15px;
    margin-bottom: 5px;
    color: #555;
    font-size: 16px;
}

.card-content ul {
    list-style: disc inside;
    padding-left: 0;
    margin-bottom: 10px;
}

.card-content ul li {
    margin-bottom: 3px;
    color: #666;
}
*/

.card-actions {
    margin-top: auto; /* Push actions to the bottom */
    padding-top: 15px; /* Add some space above the button */
    border-top: 1px solid #eee; /* Optional: Add a separator */
    text-align: right; /* Align button to the right */
}

.btn-submit-grades,
.btn-view-submission {
    display: inline-block;
    color: white;
    padding: 8px 16px;
    border-radius: 5px;
    text-decoration: none; /* Remove underline */
    font-size: 14px;
    transition: background-color 0.3s ease;
}

.btn-submit-grades {
    background-color: #007bff; /* Primary blue color */
}

.btn-view-submission {
    background-color: #6c757d; /* Secondary gray color */
}

.btn-submit-grades:hover {
    background-color: #0056b3;
}

.btn-view-submission:hover {
    background-color: #5a6268;
}

/* Modal Styles */
.grade-modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.4);
    animation: fadeIn 0.3s;
}

@keyframes fadeIn {
    from {opacity: 0}
    to {opacity: 1}
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 0;
    border: 1px solid #888;
    width: 80%;
    max-width: 900px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    animation: slideDown 0.3s;
}

@keyframes slideDown {
    from {transform: translateY(-50px); opacity: 0;}
    to {transform: translateY(0); opacity: 1;}
}

.modal-header {
    padding: 15px 20px;
    border-bottom: 1px solid #ddd;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    color: white;
}

.modal-header.pass {
    background-color: #28a745;
}

.modal-header.fail {
    background-color: #dc3545;
}

.modal-header.inc {
    background-color: #ffc107;
    color: #333;
}

.modal-header.nc {
    background-color: #6c757d;
}

.modal-header.dr {
    background-color: #17a2b8;
}

.modal-header h2 {
    margin: 0;
    font-size: 1.5rem;
}

.close {
    color: inherit;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover {
    opacity: 0.7;
}

.modal-body {
    padding: 20px;
    max-height: 70vh;
    overflow-y: auto;
}

.grade-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.grade-table th, .grade-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.grade-table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.grade-table tr:hover {
    background-color: #f1f5f9;
}

.grade-value {
    font-weight: bold;
    padding: 5px 10px;
    border-radius: 4px;
    display: inline-block;
    text-align: center;
    min-width: 40px;
}

.grade-value.pass {
    background-color: rgba(40, 167, 69, 0.2);
    color: #28a745;
}

.grade-value.fail {
    background-color: rgba(220, 53, 69, 0.2);
    color: #dc3545;
}

.grade-value.inc {
    background-color: rgba(255, 193, 7, 0.2);
    color: #856404;
}

.grade-value.nc {
    background-color: rgba(108, 117, 125, 0.2);
    color: #6c757d;
}

.grade-value.dr {
    background-color: rgba(23, 162, 184, 0.2);
    color: #17a2b8;
}

.no-data {
    text-align: center;
    padding: 30px;
    color: #6c757d;
    font-style: italic;
}

/* Make grade cards look clickable with cursor and hover effect */
.grade-card {
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
}

.grade-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 15px rgba(0,0,0,0.1);
}
</style>
<!-- Grade Modals -->
<!-- PASS Modal -->
<div id="pass-modal" class="grade-modal">
    <div class="modal-content">
        <div class="modal-header pass">
            <h2>Passing Grades</h2>
            <span class="close" onclick="closeGradeModal('pass-modal')">&times;</span>
        </div>
        <div class="modal-body">
            @if(count($gradeDetails['pass']) > 0)
                <table class="grade-table">
                    <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Grade</th>
                            <th>Term</th>
                            <th>Academic Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gradeDetails['pass'] as $grade)
                            <tr>
                                <td>{{ $grade['subject_code'] }}</td>
                                <td>{{ $grade['subject_name'] }}</td>
                                <td class="grade-value pass">{{ $grade['grade'] }}</td>
                                <td>{{ $grade['semester'] }} {{ $grade['term'] }}</td>
                                <td>{{ $grade['academic_year'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="no-data">No passing grades found.</p>
            @endif
        </div>
    </div>
</div>

<!-- FAIL Modal -->
<div id="fail-modal" class="grade-modal">
    <div class="modal-content">
        <div class="modal-header fail">
            <h2>Failing Grades</h2>
            <span class="close" onclick="closeGradeModal('fail-modal')">&times;</span>
        </div>
        <div class="modal-body">
            @if(count($gradeDetails['fail']) > 0)
                <table class="grade-table">
                    <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Grade</th>
                            <th>Term</th>
                            <th>Academic Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gradeDetails['fail'] as $grade)
                            <tr>
                                <td>{{ $grade['subject_code'] }}</td>
                                <td>{{ $grade['subject_name'] }}</td>
                                <td class="grade-value fail">{{ $grade['grade'] }}</td>
                                <td>{{ $grade['semester'] }} {{ $grade['term'] }}</td>
                                <td>{{ $grade['academic_year'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="no-data">No failing grades found.</p>
            @endif
        </div>
    </div>
</div>

<!-- INC Modal -->
<div id="inc-modal" class="grade-modal">
    <div class="modal-content">
        <div class="modal-header inc">
            <h2>Incomplete Grades</h2>
            <span class="close" onclick="closeGradeModal('inc-modal')">&times;</span>
        </div>
        <div class="modal-body">
            @if(count($gradeDetails['inc']) > 0)
                <table class="grade-table">
                    <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Grade</th>
                            <th>Term</th>
                            <th>Academic Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gradeDetails['inc'] as $grade)
                            <tr>
                                <td>{{ $grade['subject_code'] }}</td>
                                <td>{{ $grade['subject_name'] }}</td>
                                <td class="grade-value inc">{{ $grade['grade'] }}</td>
                                <td>{{ $grade['semester'] }} {{ $grade['term'] }}</td>
                                <td>{{ $grade['academic_year'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="no-data">No incomplete grades found.</p>
            @endif
        </div>
    </div>
</div>

<!-- NC Modal -->
<div id="nc-modal" class="grade-modal">
    <div class="modal-content">
        <div class="modal-header nc">
            <h2>No Credit Grades</h2>
            <span class="close" onclick="closeGradeModal('nc-modal')">&times;</span>
        </div>
        <div class="modal-body">
            @if(count($gradeDetails['nc']) > 0)
                <table class="grade-table">
                    <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Grade</th>
                            <th>Term</th>
                            <th>Academic Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gradeDetails['nc'] as $grade)
                            <tr>
                                <td>{{ $grade['subject_code'] }}</td>
                                <td>{{ $grade['subject_name'] }}</td>
                                <td class="grade-value nc">{{ $grade['grade'] }}</td>
                                <td>{{ $grade['semester'] }} {{ $grade['term'] }}</td>
                                <td>{{ $grade['academic_year'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="no-data">No NC grades found.</p>
            @endif
        </div>
    </div>
</div>

<!-- DR Modal -->
<div id="dr-modal" class="grade-modal">
    <div class="modal-content">
        <div class="modal-header dr">
            <h2>Dropped Subjects</h2>
            <span class="close" onclick="closeGradeModal('dr-modal')">&times;</span>
        </div>
        <div class="modal-body">
            @if(count($gradeDetails['dr']) > 0)
                <table class="grade-table">
                    <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Grade</th>
                            <th>Term</th>
                            <th>Academic Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gradeDetails['dr'] as $grade)
                            <tr>
                                <td>{{ $grade['subject_code'] }}</td>
                                <td>{{ $grade['subject_name'] }}</td>
                                <td class="grade-value dr">{{ $grade['grade'] }}</td>
                                <td>{{ $grade['semester'] }} {{ $grade['term'] }}</td>
                                <td>{{ $grade['academic_year'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="no-data">No dropped subjects found.</p>
            @endif
        </div>
    </div>
</div>

<script>
function openGradeModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}

function closeGradeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    if (event.target.classList.contains('grade-modal')) {
        event.target.style.display = 'none';
    }
}
</script>
@endsection