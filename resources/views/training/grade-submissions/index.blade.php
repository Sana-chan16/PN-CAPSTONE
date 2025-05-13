@extends('layouts.nav')

@section('content')

<h1>Grade Submissions</h1>
<hr>
<!-- Success Message -->
@if(session('success'))
    <div class="alert-message">
        <span class="alert-icon">&#10003;</span>
        <span>{{ session('success') }}</span>
        <button class="close-alert" onclick="this.parentElement.style.display='none';">&times;</button>
    </div>
@endif
<div class="container" style="margin-top: 20px;">
    <!-- Table -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>School</th>
                    <th>Class</th>
                    <th>Semester</th>
                    <th>Term</th>
                    <th>Academic Year</th>
                    <th>Subjects</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($gradeSubmissions as $submission)
                    <tr>
                        <td>{{ $submission->school->name ?? '-' }}</td>
                        <td>{{ $submission->classModel->class_name ?? '-' }}</td>
                        <td>{{ $submission->semester }}</td>
                        <td>{{ $submission->term }}</td>
                        <td>{{ $submission->academic_year }}</td>
                        <td>
                            <ul style="padding-left: 18px; text-align:left;">
                                @foreach($submission->subject_ids as $subjectId)
                                    <li>{{ App\Models\Subject::find($subjectId)->name ?? 'Unknown' }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>{{ $submission->created_at->format('Y-m-d H:i') }}</td>
                        <td class="actions">
                            <form action="{{ route('training.grade-submissions.destroy', $submission->id) }}" method="POST" class="inline-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" onclick="return confirm('Are you sure you want to delete this grade submission?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="no-data">No grade submissions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Alert and Table Styling -->
<style>
    h1{
        margin-top:50px;
    }

    .alert-message {
        width: 100%;
        display: flex;
        align-items: center;
        background-color: #e6f4ea;
        color: #256029;
        border: 1.5px solid #b7e0c5;
        border-radius: 6px;
        padding: 12px 18px;
        margin-bottom: 22px;
        font-size: 1rem;
        max-width: 1400px;
        min-width: 200px;
        box-sizing: border-box;
        position: relative;
        box-shadow: 0 2px 6px rgba(39, 174, 96, 0.07);
        gap: 10px;
    }
    .alert-icon {
        font-size: 1.2em;
        color: #219150;
        margin-right: 8px;
    }
    .close-alert {
        position: absolute;
        right: 12px;
        top: 7px;
        background: none;
        border: none;
        color: #256029;
        font-size: 1.1em;
        cursor: pointer;
        padding: 0 4px;
        line-height: 1;
    }
    .close-alert:hover {
        color: #f44336;
        background: #f0f0f0;
        border-radius: 50%;
    }
    .container {
        margin-top: 20px;
    }
    .table-container {
        overflow-x: auto;
        width: 100%;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        background:#fff;
    }
    thead {
        /* background-color: #f5f5f5; */
        background:#4CAF50;
        color:white;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: center;
        vertical-align: middle;
    }
    th {
        font-weight: bold;
    }
    .actions {
        display: flex;
        justify-content: center;
        gap: 8px;
    }
    .inline-form {
        display: inline-block;
    }
    .btn-delete {
        border: none;
        padding: 6px 14px;
        cursor: pointer;
        border-radius: 5px;
        font-size: 0.95em;
        background-color: #F44336;
        color: white;
        transition: background 0.2s;
    }
    .btn-delete:hover {
        background: #d32f2f;
    }
    .no-data {
        text-align: center;
        color: #777;
        font-style: italic;
    }
</style>
@endsection
