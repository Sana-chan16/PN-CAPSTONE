@extends('layouts.nav')

@section('content')
    <div class="create-class-container">
        <h1>Add Class for {{ $school->school_name }}</h1>
        <form action="{{ route('manage.schools.classes.store', $school->id) }}" method="POST" class="add-class-form">
            @csrf
            <div class="form-group">
                <label for="class_name">Class Name</label>
                <input type="text" name="class_name" id="class_name" required class="class-input">
            </div>
            <div class="form-group">
                <label style="font-weight:600;">Assign Students:</label>
                <button type="button" class="select-students-btn" onclick="toggleStudentCheckboxes()">Select Students</button>
                <div class="checkbox-list" id="students-checkboxes" style="display:none; margin-top:10px;">
                    @foreach($students as $student)
                        <label class="student-checkbox">
                            <input type="checkbox" name="students[]" value="{{ $student->user_id }}">
                            {{ $student->user_lname }}, {{ $student->user_fname }} ({{ $student->user_id }})
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="add-class-btn">Add Class</button>
                <a href="{{ route('manage.schools.view', $school->id) }}" class="cancel-btn">Cancel</a>
            </div>
        </form>
    </div>

    <style>
        .create-class-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
        .add-class-form {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }
        .class-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .select-students-btn {
            background-color: #1976d2;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-top: 10px;
            display: inline-block;
            border: none;
            cursor: pointer;
        }
        .select-students-btn:hover {
            background-color: #125ea2;
        }
        .checkbox-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px 20px;
        }
        .student-checkbox {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 15px;
        }
        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }
        .add-class-btn,
        .cancel-btn {
            flex: 1 1 0;
            min-width: 120px;
            max-width: 200px;
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: 500;
            font-size: 16px;
            text-align: center;
            transition: all 0.3s ease;
        }
        .add-class-btn {
            background-color: #ff9933;
            color: white;
            border: none;
            cursor: pointer;
        }
        .add-class-btn:hover {
            background-color: #e68a00;
        }
        .cancel-btn {
            background-color: #f44336;
            color: white;
            text-decoration: none;
            border: none;
            display: inline-block;
            cursor: pointer;
        }
        .cancel-btn:hover {
            background-color: #da190b;
        }
    </style>
    <script>
        function toggleStudentCheckboxes() {
            var checkboxes = document.getElementById('students-checkboxes');
            if (checkboxes.style.display === 'none') {
                checkboxes.style.display = 'flex';
            } else {
                checkboxes.style.display = 'none';
            }
        }
    </script>
@endsection 