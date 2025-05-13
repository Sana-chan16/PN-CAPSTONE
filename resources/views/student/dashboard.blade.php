@extends('layouts.student')
@section('content')

<link rel="stylesheet" href="{{ asset('css/student/dashboard.css') }}">

<div class="dashboard-container">
    <h1>Student Dashboard</h1>
    
    @if(session('error'))
        <div class="alert error">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Removed class selection dropdown as per user request --}}

    <div class="notifications-section">
        <div class="section-header">
            <h2>Grade Submissions</h2>
            <a href="{{ route('student.grades.index') }}" class="view-all">View All Submissions</a>
        </div>
        
        @if(!auth()->user()->studentDetail)
            <div class="alert warning">
                <p>Your student details are not yet set up. Please contact your administrator.</p>
            </div>
        @elseif($gradeSubmissions->isEmpty())
            <p class="no-notifications">No grade submissions yet.</p>
        @else
            <div class="notifications-list">
                @foreach($gradeSubmissions->take(3) as $submission)
                    <div class="notification-card">
                        <div class="notification-header">
                            <span class="school">{{ $submission->school->name }}</span>
                            <span class="class">{{ $submission->classModel->class_name }}</span>
                        </div>
                        <div class="notification-body">
                            <p><strong>Semester:</strong> {{ $submission->semester }}</p>
                            <p><strong>Term:</strong> {{ $submission->term }}</p>
                            <p><strong>Academic Year:</strong> {{ $submission->academic_year }}</p>
                            <p><strong>Subjects:</strong></p>
                            <ul class="subjects-list">
                                @php
                                    $allSubjects = \App\Models\Subject::whereIn('id', $submission->subject_ids)->get();
                                @endphp
                                @foreach($allSubjects as $subject)
                                    <li>{{ $subject->name }}</li>
                                @endforeach
                            </ul>
                            <p class="submission-date">Created: {{ $submission->created_at->diffForHumans() }}</p>
                            @php
                                $studentPivot = $submission->students->first()->pivot ?? null;
                            @endphp
                            @if(!$studentPivot || $studentPivot->status == 'pending')
                                <a href="{{ route('student.grade-submissions.show', $submission->id) }}" class="btn-submit-grades">Submit Grades</a>
                            @elseif($studentPivot->status == 'rejected')
                                <a href="{{ route('student.grade-submissions.show', $submission->id) }}" class="btn-submit-grades">Resubmit Grades</a>
                            @else
                                <a href="{{ route('student.grade-submissions.view', $submission->id) }}" class="btn-view-grades">View Submission</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            @if($gradeSubmissions->count() > 3)
                <div class="view-more">
                    <a href="{{ route('student.grades.index') }}">View All {{ $gradeSubmissions->count() }} Submissions</a>
                </div>
            @endif
        @endif
    </div>

    @if(isset($debug_info))
        <div class="debug-info" style="display: none;">
            <pre>{{ json_encode($debug_info, JSON_PRETTY_PRINT) }}</pre>
        </div>
    @endif
</div>

@endsection