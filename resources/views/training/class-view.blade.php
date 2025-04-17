@extends('layouts.nav')

@section('content')
    <div class="header-container">
        <h1>Class: {{ $class->class_name }}</h1>
        <div class="action-buttons">
            <a href="#" class="grade-submission-btn">Grade Submission</a>
            <a href="#" class="monitor-grade-btn">Monitor Grade Submission</a>
            <a href="#" class="view-subjects-btn">View Subjects</a>
        </div>
    </div>

    <section class="class-details">
        <div class="table-responsive">
            <table class="students-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>MI</th>
                        <th>Suffix</th>
                        <th>Email</th>
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; color:#888;">No students found in this class.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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

        .students-table {
            width: 90%;
            border-collapse: collapse;
            margin: 0 auto;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .students-table th,
        .students-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        .students-table th {
            background-color: #4CAF50;
            color: white;
            font-weight: 500;
        }

        .students-table tr:hover {
            background-color: #f5f5f5;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
            flex: 1;
        }
    </style>
@endsection 