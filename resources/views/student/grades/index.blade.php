@extends('layouts.student')

@section('content')

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap">
<style>
body, .container, h1, h2, h3, h4, h5, h6, .submission-card, .submission-header, .submission-date, .submission-details, .detail-row, .status-badge, .btn-submit, .btn-view, .no-submissions, .alert {
    font-family: 'Poppins', sans-serif !important;
}
.container {
    width: 100%;
    max-width: 1500px;
    /* margin: 30px auto; */
    /* margin: 30px auto;
    padding: 20px; */
}
h1 {
    color: #222;
    font-size: 2rem;
    margin-bottom: 18px;
    font-weight: 700;
    letter-spacing: 0.5px;
}
.submissions-list {
    display: flex;
    flex-direction: column;
    gap: 24px;
}
.submission-card {
    margin-left:100px;
    background: #f8f9fa;
    border-radius: 14px;
    box-shadow: 0 2px 8px rgba(34, 187, 234, 0.07);
    padding: 24px 28px;
    border: 2px solid #ff9933;
    display: flex;
    flex-direction: column;
    gap: 10px;
    transition: box-shadow 0.2s, border 0.2s;
}
.submission-card:hover {
    box-shadow: 0 4px 16px rgba(34, 187, 234, 0.13);
    border: 1.5px solid #22bbea;
}
.submission-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}
.submission-header h2 {
    font-size: 1.1rem;
    color: #22bbea;
    margin: 0;
    font-weight: 600;
    letter-spacing: 0.2px;
}
.submission-date {
    color: #888;
    font-size: 0.95em;
}
.submission-details {
    display: flex;
    flex-wrap: wrap;
    gap: 18px 32px;
    margin-bottom: 10px;
}
.detail-row {
    display: flex;
    gap: 8px;
    min-width: 180px;
}
.detail-row .label {
    color: #666;
    font-weight: 600;
    font-size: 0.98em;
}
.detail-row .value {
    color: #222;
    font-size: 0.98em;
}
.status-badge {
    padding: 4px 14px;
    border-radius: 12px;
    font-size: 0.97em;
    font-weight: 600;
    display: inline-block;
    letter-spacing: 0.2px;
}
.status-badge.pending {
    background: #e3f3fa;
    color: #22bbea;
    border: 1px solid #22bbea;
}
.status-badge.submitted {
    background: #d4edda;
    color: #218838;
    border: 1px solid #b7e4c7;
}
.submission-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 10px;
}
.btn-submit, .btn-view {
    padding: 8px 20px;
    border-radius: 6px;
    font-size: 1em;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
    box-shadow: 0 1px 2px rgba(34, 187, 234, 0.07);
}
.btn-submit {
    background: #22bbea;
    color: #fff;
    border: 1.5px solid #22bbea;
}
.btn-submit:hover {
    background: #1aa8d4;
    color: #fff;
}
.btn-view {
    background: #fff;
    color: #22bbea;
    border: 1.5px solid #22bbea;
}
.btn-view:hover {
    background: #22bbea;
    color: #fff;
}
.no-submissions {
    text-align: center;
    padding: 40px;
    background: #f8f9fa;
    border-radius: 12px;
    color: #666;
    font-style: italic;
    margin-top: 40px;
    border: 1.5px solid #e3f3fa;
}
.pagination {
    margin-top: 30px;
    display: flex;
    justify-content: center;
}
.alert {
    padding: 15px;
    border-radius: 6px;
    margin-bottom: 20px;
    font-size: 1rem;
    border: 1.5px solid #e3f3fa;
}
.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1.5px solid #b7e4c7;
}
.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1.5px solid #f5c6cb;
}
@media (max-width: 700px) {
    .container {
        padding: 8px;
    }
    .submission-card {
        padding: 14px 8px;
    }
    .submission-details {
        flex-direction: column;
        gap: 8px;
    }
    .submission-header h2 {
        font-size: 1em;
    }
}
</style>

<h1>My Grade Submissions</h1>
<hr>
@if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
<div class="container">
    <div class="submissions-list">
        @forelse($gradeSubmissions as $submission)
            @php
                $statuses = \DB::table('grade_submission_student')
                    ->where('grade_submission_id', $submission->id)
                    ->where('user_id', auth()->user()->user_id)
                    ->pluck('status')
                    ->filter()
                    ->values();
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
            <div style="color: red;">Submission ID {{ $submission->id }} - Statuses: {{ implode(', ', $statuses->toArray()) }}</div>
            <div class="submission-card">
                <div class="submission-header">
                    <h2>{{ $submission->school->name }} - {{ $submission->classModel->class_name }}</h2>
                    <span class="submission-date">{{ $submission->created_at->format('M d, Y') }}</span>
                </div>
                <div class="submission-details">
                    <div class="detail-row">
                        <span class="label">Semester:</span>
                        <span class="value">{{ $submission->semester }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Term:</span>
                        <span class="value">{{ ucfirst(str_replace('_', ' ', $submission->term)) }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Academic Year:</span>
                        <span class="value">{{ $submission->academic_year }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Status:</span>
                        @if($aggStatus === 'approved')
                            <span class="status-badge approved">Approved</span>
                        @elseif($aggStatus === 'rejected')
                            <span class="status-badge rejected">Rejected</span>
                        @elseif($aggStatus === 'submitted')
                            <span class="status-badge submitted">Submitted</span>
                        @else
                            <span class="status-badge pending">Pending</span>
                        @endif
                    </div>
                </div>
                <div class="submission-actions">
                    @if($aggStatus === 'rejected' || $aggStatus === 'pending')
                        <a href="{{ route('student.grade-submissions.show', $submission->id) }}" class="btn-submit">
                            Resubmit Grades
                        </a>
                    @elseif($aggStatus === 'submitted')
                        <a href="{{ route('student.grade-submissions.view', $submission->id) }}" class="btn-view">
                            View Submission
                        </a>
                    @elseif($aggStatus === 'approved')
                        <a href="{{ route('student.grade-submissions.view', $submission->id) }}" class="btn-view">
                            View Submission
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="no-submissions">
                <p>No grade submissions available.</p>
            </div>
        @endforelse
    </div>
    <div class="pagination">
        {{ $gradeSubmissions->links() }}
    </div>
</div>

<pre>
@php
    // dd($gradeSubmissions->first());
@endphp
</pre>

@endsection 