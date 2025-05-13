@extends('layouts.student')

@section('content')
<style>
    .grade-form-container {
        max-width: 700px;
        margin: 30px auto;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        padding: 32px 24px;
    }
    .grade-form-title {
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
    .section-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin: 24px 0 12px 0;
        color: #222;
    }
    .form-group {
        margin-bottom: 18px;
    }
    .form-label {
        display: block;
        margin-bottom: 6px;
        color: #444;
        font-weight: 500;
    }
    .form-input, .form-file {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
    }
    .form-file {
        padding: 6px 0;
    }
    .form-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 28px;
    }
    .btn-submit {
        background: #22bbea;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 10px 22px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-submit:hover {
        background: #1aa8d4;
    }
    .btn-cancel {
        background: #f1f1f1;
        color: #333;
        border: none;
        border-radius: 4px;
        padding: 10px 22px;
        font-size: 1rem;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.2s;
    }
    .btn-cancel:hover {
        background: #e2e2e2;
    }
    .alert {
        padding: 12px 18px;
        border-radius: 4px;
        margin-bottom: 18px;
        font-size: 1rem;
    }
    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    @media (max-width: 600px) {
        .grade-form-container {
            padding: 16px 4vw;
        }
        .submission-details-row {
            flex-direction: column;
        }
        .submission-details-label {
            width: 100%;
            margin-bottom: 2px;
        }
    }
</style>

<div class="grade-form-container">
    <div class="grade-form-title">Submit Grades</div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Submission Details at the Top -->
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

        <div style="font-size:0.95em;color:black;margin-top:4px;">
                    Valid grades:
                    <span style="color:#4CAF50;">1.0-3.0</span> or 
                    <span style="color:#4CAF50;">3.1-5.0</span> (passing)
                    <br>
                    Special grades: <span style="color:#2196F3;">INC, NC, DR</span>
                </div>
    </div>

    <form action="{{ route('student.grade-submissions.store', $gradeSubmission->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="section-title">Subject Grades</div>
        @foreach($subjects as $subject)
            <div class="form-group">
                <label for="grade_{{ $subject->id }}" class="form-label">{{ $subject->name }} <span style="color:#888;">({{ $subject->offer_code }})</span></label>
                <input type="text" name="grades[{{ $subject->id }}]" id="grade_{{ $subject->id }}" class="form-input" required 
                     pattern="^(1\.0|1\.25|1\.5|1\.75|2\.0|2\.25|2\.5|2\.75|3\.0|3\.1|3\.25|3\.5|3\.75|4\.0|4\.25|4\.5|4\.75|5\.0|INC|NC|DR)$"
                    title="Enter a grade between 1.0-3.0 or 3.1-5.0, or INC, NC, or DR" 
                    placeholder="Enter grade (e.g, or INC, NC, DR)">
            </div>
        @endforeach

        <div class="section-title">Proof of Grades</div>
        <div class="form-group">
            <label for="proof_file" class="form-label">Upload Proof (Image/PDF)</label>
            <input type="file" name="proof_file" id="proof_file" class="form-file" accept="image/*,.pdf" required>
            <div style="font-size:0.95em;color:#888;margin-top:4px;">Accepted formats: JPG, PNG, PDF. Max size: 5MB</div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">Submit Grades</button>
            <a href="{{ route('student.grades.index') }}" class="btn-cancel">Cancel</a>
        </div>
    </form>
</div>
@endsection 