@extends('layouts.student')

@section('content')
<style>
    .grade-status-container {
        max-width: 1200px;
        margin: 30px auto;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        padding: 32px 24px;
    }
    .grade-status-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 24px;
        text-align: center;
        color: #222;
    }
    .submission-details {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 18px 20px;
        margin-bottom: 28px;
    }
    .submission-details-row {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 8px;
    }
    .submission-details-label {
        font-weight: 600;
        color: #555;
        width: 160px;
    }
    .submission-details-value {
        color: #222;
    }
    .grades-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    }
    .grades-table th {
        background: #22bbea;
        color: #fff;
        padding: 15px;
        text-align: center;
        font-weight: 600;
        min-width: 120px;
    }
    .grades-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
        text-align: center;
    }
    .grades-table tr:last-child td {
        border-bottom: none;
    }
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.9em;
        font-weight: 600;
        display: inline-block;
    }
    .status-badge.pending {
        background: #fff3cd;
        color: #856404;
    }
    .status-badge.approved {
        background: #d4edda;
        color: #155724;
    }
    .status-badge.rejected {
        background: #f8d7da;
        color: #721c24;
    }
    .status-badge.submitted {
        background: #d1ecf1;
        color: #0c5460;
    }
    .proof-section {
        margin-top: 30px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    .proof-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 15px;
        color: #333;
    }
    .proof-link {
        color: #22bbea;
        text-decoration: none;
        font-weight: 500;
        padding: 8px 16px;
        background: #fff;
        border-radius: 5px;
        border: 1px solid #22bbea;
        display: inline-block;
    }
    .proof-link:hover {
        background: #e3f3fa;
    }
    .submission-info {
        margin-top: 15px;
        color: #666;
        font-size: 0.9em;
    }
    .back-button {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 20px;
        background: #ff9933;
        color: #333;
        text-decoration: none;
        border-radius: 5px;
        font-weight: 500;
    }
    .back-button:hover {
        background:rgb(251, 131, 11);
        color:white;
    }
    .grade-value {
        font-size: 1.1em;
        font-weight: 600;
        color: #333;
    }
</style>

<div class="grade-status-container">
    <div class="grade-status-title">Grade Submission Status</div>

    <!-- Submission Details -->
    <div class="submission-details">
        <div class="submission-details-row">
            <div class="submission-details-label">School:</div>
            <div class="submission-details-value">{{ $gradeSubmission->school->name }}</div>
        </div>
        <div class="submission-details-row">
            <div class="submission-details-label">Class:</div>
            <div class="submission-details-value">{{ $gradeSubmission->classModel->class_name }}</div>
        </div>
        <div class="submission-details-row">
            <div class="submission-details-label">Semester:</div>
            <div class="submission-details-value">{{ $gradeSubmission->semester }}</div>
        </div>
        <div class="submission-details-row">
            <div class="submission-details-label">Term:</div>
            <div class="submission-details-value">{{ ucfirst(str_replace('_', ' ', $gradeSubmission->term)) }}</div>
        </div>
        <div class="submission-details-row">
            <div class="submission-details-label">Academic Year:</div>
            <div class="submission-details-value">{{ $gradeSubmission->academic_year }}</div>
        </div>
    </div>

    <!-- Grades Table -->
    <table class="grades-table">
        <thead>
            <tr>
                @foreach($grades as $grade)
                    <th>{{ $grade->name }}</th>
                @endforeach
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                @foreach($grades as $grade)
                    <td>
                        <div class="grade-value">{{ $grade->grade ?? 'Not submitted' }}</div>
                    </td>
                @endforeach
                <td>
                    @php
                        $statuses = $grades->pluck('status')->filter()->values();
                        if ($statuses->contains('rejected')) {
                            $aggStatus = 'rejected';
                        } elseif ($statuses->contains('pending')) {
                            $aggStatus = 'pending';
                        } elseif ($statuses->contains('submitted')) {
                            $aggStatus = 'submitted';
                        } elseif ($statuses->every(fn($s) => $s === 'approved')) {
                            $aggStatus = 'approved';
                        } else {
                            $aggStatus = $statuses->first() ?? 'pending';
                        }
                    @endphp
                    @if($aggStatus === 'approved')
                        <span class="status-badge approved">Approved</span>
                    @elseif($aggStatus === 'rejected')
                        <span class="status-badge rejected">Rejected</span>
                    @elseif($aggStatus === 'submitted')
                        <span class="status-badge submitted">Submitted</span>
                    @else
                        <span class="status-badge pending">Pending</span>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Proof Section -->
    <!-- <div class="proof-section">
        <div class="proof-title">Submission Proof</div>
        @php
            $firstGrade = $grades->first();
            $submittedAt = $firstGrade->pivot->submitted_at ?? null;
            $proofPath = $firstGrade->pivot->proof_path ?? null;
        @endphp
        
        @if($proofPath)
            <a href="{{ asset('storage/' . $proofPath) }}" class="proof-link" target="_blank">
                View Submission Proof
            </a>
            <div class="submission-info">
                Submitted on: {{ \Carbon\Carbon::parse($submittedAt)->format('M d, Y H:i') }}
            </div>
        @else
            <div class="submission-info">No proof uploaded yet</div>
        @endif
    </div> -->

    <a href="{{ route('student.grades.index') }}" class="back-button">Back to Submissions</a>
</div>
@endsection 