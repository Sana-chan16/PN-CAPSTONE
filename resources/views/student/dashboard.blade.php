@extends('layouts.student_layout')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; margin-bottom: 18px;">
        <h1 style="margin-bottom: 0;">Grade Submissions</h1>
        <form method="GET" action="" style="min-width: 220px; margin-top: 8px;">
            <select name="filter_key" class="form-select" style="width: 100%;" onchange="this.form.submit()">
                <option value="" disabled @if(empty($filterKey)) selected @endif>Select Semester & Year</option>
                @foreach($filterOptions as $option)
                    <option value="{{ $option }}" @if(isset($filterKey) && $filterKey == $option) selected @endif>{{ $option }}</option>
                @endforeach
            </select>
        </form>
    </div>
    <div class="row mb-4" style="display: flex; flex-wrap: nowrap; justify-content: center; gap: 20px; margin-bottom: 48px; overflow-x: auto;">
        <a href="{{ route('student.student.subjects.passed') }}" style="text-decoration: none;">
        <div class="card mb-3" style="width: 100%; min-width: 0; max-width: 120px; background: #F6DFB6; color: #222; border: none; border-radius: 5%; box-shadow: 0 4px 12px rgba(0,0,0,0.10); margin-bottom: 12px;">
            <div class="card-body text-center">
                <h3 class="card-title">Passed</h3>
                <p class="card-text" style="font-size: 1.5em !important; font-weight: bold;">{{ $passed }}</p>
            </div>
        </div>
        </a>
        <a href="{{ route('student.student.subjects.failed') }}" style="text-decoration: none;">
        <div class="card mb-3" style="width: 100%; min-width: 0; max-width: 120px; background: #F6DFB6; color: #222; border: none; border-radius: 5%; box-shadow: 0 4px 12px rgba(0,0,0,0.10); margin-bottom: 12px;">
            <div class="card-body text-center">
                <h3 class="card-title">Failed</h3>
                <p class="card-text" style="font-size: 1.5em !important; font-weight: bold;">{{ $failed }}</p>
            </div>
        </div>
        </a>
        <a href="{{ route('student.student.subjects.inc') }}" style="text-decoration: none;">
        <div class="card mb-3" style="width: 100%; min-width: 0; max-width: 120px; background: #F6DFB6; color: #222; border: none; border-radius: 5%; box-shadow: 0 4px 12px rgba(0,0,0,0.10); margin-bottom: 12px;">
            <div class="card-body text-center">
                <h3 class="card-title">INC</h3>
                <p class="card-text" style="font-size: 1.5em !important; font-weight: bold;">{{ $inc }}</p>
            </div>
        </div>
        </a>
        <a href="{{ route('student.student.subjects.nc') }}" style="text-decoration: none;">
        <div class="card mb-3" style="width: 100%; min-width: 0; max-width: 120px; background: #F6DFB6; color: #222; border: none; border-radius: 5%; box-shadow: 0 4px 12px rgba(0,0,0,0.10); margin-bottom: 12px;">
            <div class="card-body text-center">
                <h3 class="card-title">NC</h3>
                <p class="card-text" style="font-size: 1.5em !important; font-weight: bold;">{{ $nc }}</p>
            </div>
        </div>
        </a>
        <a href="{{ route('student.student.subjects.dr') }}" style="text-decoration: none;">
        <div class="card mb-3" style="width: 100%; min-width: 0; max-width: 120px; background: #F6DFB6; color: #222; border: none; border-radius: 5%; box-shadow: 0 4px 12px rgba(0,0,0,0.10); margin-bottom: 12px;">
            <div class="card-body text-center">
                <h3 class="card-title">DR</h3>
                <p class="card-text" style="font-size: 1.5em !important; font-weight: bold;">{{ $dropout }}</p>
            </div>
        </div>
        </a>
    </div>
    
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

    @if($gradeSubmissions->isEmpty())
        <div class="no-submissions" style="margin-top: 40px; text-align: center;">
            <div style="margin-bottom: 18px;">
                <!-- Colorful SVG illustration for empty state -->
                <svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <rect x="15" y="30" width="70" height="40" rx="10" fill="#e0f2fe"/>
                  <rect x="28" y="43" width="44" height="8" rx="2" fill="#38bdf8"/>
                  <rect x="28" y="57" width="28" height="6" rx="2" fill="#facc15"/>
                  <circle cx="70" cy="60" r="6" fill="#fbbf24"/>
                  <ellipse cx="50" cy="80" rx="22" ry="6" fill="#bae6fd"/>
                  <rect x="40" y="36" width="20" height="4" rx="2" fill="#0ea5e9"/>
                </svg>
            </div>
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

                        {{-- Display overall status for the student in this submission --}}
                        @php
                            $studentPivot = $submission->students->where('pivot.user_id', Auth::id())->first();
                            $overallStatus = $studentPivot ? ($studentPivot->pivot->status ?? 'pending') : 'pending';
                        @endphp
                         <div class="info-row">
                             <span class="label">Overall Status:</span> {{-- Changed label from Status to Overall Status --}}
                             <span class="status {{ $overallStatus }}">
                                 {{ ucfirst($overallStatus) }}
                             </span>
                         </div>
                        <div class="info-row">
                            <span class="label">Created:</span> {{-- Changed label from Submitted to Created --}}
                            <span class="date">{{ $submission->created_at ? $submission->created_at->format('M d, Y') : 'N/A' }}</span>
                        </div>

                        {{-- Add the button based on submission status --}}
                        <div class="card-actions">
                            @if(in_array($overallStatus, ['submitted', 'approved']))
                                <a href="{{ route('student.view-submission', $submission->id) }}" class="btn-view-submission">View Submission</a>
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
    overflow-y: auto;
    max-height: 90vh;
}

h1 {
    color: #333;
    margin-bottom: 20px;
    font-size: 24px;
}

.row.mb-4 {
    display: flex;
    flex-wrap: nowrap;
    justify-content: center;
    gap: 20px;
    margin-bottom: 48px;
    overflow-x: auto;
}

.row.mb-4 a {
    flex: 0 0 120px;
    min-width: 100px;
    max-width: 140px;
    display: flex;
    justify-content: center;
    text-decoration: none;
    margin: 0 6px;
}

.card.mb-3 {
    width: 100%;
    min-width: 0;
    max-width: 120px;
    background: #F6DFB6;
    color: #222;
    border: none;
    border-radius: 5%;
    box-shadow: 0 4px 12px rgba(0,0,0,0.10);
    margin-bottom: 12px;
}

.card-title {
    font-size: 1.1em;
    margin-bottom: 10px;
}

.card-text {
    font-size: 1.5em !important;
    font-weight: bold;
}

@media (max-width: 900px) {
    .row.mb-4 {
        gap: 10px !important;
    }
}

@media (max-width: 600px) {
    .row.mb-4 {
        flex-direction: column;
        gap: 10px !important;
        align-items: center;
        overflow-x: visible;
    }
    .row.mb-4 a {
        width: 100%;
        min-width: 0;
        max-width: 350px;
        padding: 0;
    }
    .card.mb-3 {
        width: 100%;
        min-width: 0;
        max-width: 350px;
    }
    .dashboard-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    .dashboard-header h1 {
        font-size: 1.3em;
    }
    .dashboard-header form {
        width: 100%;
    }
    .dashboard-container {
        max-width: 100vw !important;
        width: 100vw !important;
        padding: 0 8px;
        margin-top: 10px !important;
        overflow-y: auto;
        max-height: 100dvh;
    }
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
     color: #000;
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
</style>
@endsection