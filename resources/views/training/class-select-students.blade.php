@extends('layouts.nav')

@section('content')
    <div class="select-students-container">
        <h1>Select Students for Class: {{ $class->class_name }} ({{ $school->school_name }})</h1>
        <form action="{{ route('manage.classes.update-students', [$school->id, $class->id]) }}" method="POST" class="select-students-form">
            @csrf
            <div class="form-group">
                <label style="font-weight:600;">Select Students:</label>
                <div class="checkbox-list">
                    @foreach($students as $student)
                        <label class="student-checkbox">
                            <input type="checkbox" name="students[]" value="{{ $student->user_id }}" {{ $class->students->contains('user_id', $student->user_id) ? 'checked' : '' }}>
                            {{ $student->user_lname }}, {{ $student->user_fname }} ({{ $student->user_id }})
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="save-btn">Save</button>
                <a href="{{ route('manage.schools.view', $school->id) }}" class="cancel-btn">Cancel</a>
            </div>
        </form>
    </div>

    <style>
        .select-students-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
        .select-students-form {
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
        .save-btn,
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
        .save-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .save-btn:hover {
            background-color: #388e3c;
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
@endsection 