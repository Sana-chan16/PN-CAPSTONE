@extends('layouts.nav')

@section('content')
    <div class="header-container">
        <h1>Grade Submission</h1>
        @if(isset($class))
            <div class="action-buttons">
                <a href="{{ route('grade.submission.students', ['class_id' => $class->id]) }}" class="grade-submission-btn">Grade Submission</a>
                <a href="{{ route('grade.submission.monitor', ['class_id' => $class->id]) }}" class="monitor-grade-btn">Monitor Grade Submission</a>
                <a href="{{ route('grade.submission.subjects', ['class_id' => $class->id]) }}" class="view-subjects-btn">View Subjects</a>
            </div>
        @endif
    </div>

    <section class="class-details">
        <div class="table-responsive">
            @if(isset($student) && isset($terms) && isset($subjects))
                <!-- Debug Info -->
                <div style="background: #f8f9fa; padding: 10px; margin-bottom: 20px; border-radius: 4px;">
                    <p>Debug Info:</p>
                    <p>Class ID: {{ $class->id }}</p>
                    <p>Student ID: {{ $student->user_id }}</p>
                    <p>Terms: {{ json_encode($terms) }}</p>
                    <p>Subjects Count: {{ count($subjects) }}</p>
                </div>
                
                <div class="form-card">
                    <form action="{{ route('grade.submission.store', [
                        'class_id' => $class->id,
                        'student_id' => $student->user_id
                    ]) }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="semester">Semester</label>
                            <select name="semester" id="semester" class="form-control" required>
                                <option value="">Select Semester</option>
                                @foreach($terms as $term)
                                    <option value="{{ $term }}">{{ $term }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Terms</label>
                            <div class="terms-container">
                                @foreach($terms as $term)
                                    <div class="term-checkbox">
                                        <input type="checkbox" name="terms[]" id="term_{{ $loop->index }}" value="{{ $term }}">
                                        <label for="term_{{ $loop->index }}">{{ $term }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="academic_year">Academic Year</label>
                            <input type="text" name="academic_year" id="academic_year" class="form-control" placeholder="e.g., 2023-2024" required>
                        </div>

                        <div class="form-group">
                            <label>Subjects</label>
                            <div class="subjects-container">
                                @foreach($subjects as $subject)
                                    <div class="subject-input">
                                        <label for="subject_{{ $subject->id }}">{{ $subject->subject_name }}</label>
                                        <input type="number" name="grades[{{ $subject->id }}]" id="subject_{{ $subject->id }}" 
                                               class="form-control" min="0" max="100" step="0.01" required>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="submit-btn">Submit Grades</button>
                            <a href="{{ route('grade.submission.students', [
                                'class_id' => $class->id
                            ]) }}" class="cancel-btn">Cancel</a>
                        </div>
                    </form>
                </div>
            @elseif(isset($class))
                <table class="students-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>MI</th>
                            <th>Suffix</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>{{ $student->user_id }}</td>
                                <td>{{ $student->user_lname }}</td>
                                <td>{{ $student->user_fname }}</td>
                                <td>{{ $student->user_mInitial }}</td>
                                <td>{{ $student->user_suffix }}</td>
                                <td>{{ $student->user_email }}</td>
                                <td class="actions">
                                    <a href="{{ route('grade.submission.form', [
                                        'class_id' => $class->id,
                                        'student_id' => $student->user_id
                                    ]) }}" class="view-btn">Submit Grades</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align:center; color:#888;">No students found in this class.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @else
                <table class="classes-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Class Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $class)
                            <tr>
                                <td>{{ $class->id }}</td>
                                <td>{{ $class->class_name }}</td>
                                <td class="actions">
                                    <a href="{{ route('grade.submission.students', [
                                        'class_id' => $class->id
                                    ]) }}" class="view-btn">View Class</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align:center; color:#888;">No classes found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>
    </section>

    <style>
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 0 20px;
            position: relative;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .grade-submission-btn,
        .monitor-grade-btn,
        .view-subjects-btn {
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            color: white;
        }

        .grade-submission-btn {
            background-color: #4CAF50;
        }

        .grade-submission-btn:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }

        .monitor-grade-btn {
            background-color: #2196F3;
        }

        .monitor-grade-btn:hover {
            background-color: #1976D2;
            transform: translateY(-2px);
        }

        .view-subjects-btn {
            background-color: #FF9800;
        }

        .view-subjects-btn:hover {
            background-color: #F57C00;
            transform: translateY(-2px);
        }

        .class-details {
            padding: 20px;
        }

        .students-table,
        .classes-table {
            width: 70%;
            border-collapse: collapse;
            margin: 0 auto;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .students-table th,
        .students-table td,
        .classes-table th,
        .classes-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        .students-table th:last-child,
        .students-table td:last-child,
        .classes-table th:last-child,
        .classes-table td:last-child {
            text-align: center;
        }

        .students-table th,
        .classes-table th {
            background-color: #4CAF50;
            color: white;
            font-weight: 500;
        }

        .students-table tr:hover,
        .classes-table tr:hover {
            background-color: #f5f5f5;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
            flex: 1;
        }

        .actions {
            display: flex;
            gap: 5px;
            justify-content: center;
        }

        .view-btn {
            padding: 6px 12px;
            background-color: #22bbea;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .view-btn:hover {
            background-color: rgb(34, 191, 239);
            transform: translateY(-2px);
        }

        .form-card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .terms-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 10px;
        }

        .term-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .subjects-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
        }

        .subject-input {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
        }

        .submit-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }

        .cancel-btn {
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .cancel-btn:hover {
            background-color: #d32f2f;
            transform: translateY(-2px);
        }
    </style>
@endsection
