@extends('layouts.nav')

@section('content')
    <div class="edit-school-container">
        <h1>Edit School</h1>
        
        <form action="{{ route('manage.schools.update', $school->id) }}" method="POST" class="edit-school-form">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="school_id">School ID</label>
                <input type="text" id="school_id" name="school_id" value="{{ $school->school_id }}" required>
            </div>

            <div class="form-group">
                <label for="school_name">School Name</label>
                <input type="text" id="school_name" name="school_name" value="{{ $school->school_name }}" required>
            </div>

            <div class="form-group">
                <label for="department">Department</label>
                <input type="text" id="department" name="department" value="{{ $school->department }}" required>
            </div>

            <div class="form-group">
                <label for="course">Course</label>
                <input type="text" id="course" name="course" value="{{ $school->course }}" required>
            </div>

            <div class="form-group">
                <label for="semesters">Number of Semesters</label>
                <input type="number" id="semesters" name="semesters" value="{{ $school->semesters }}" min="1" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="update-btn">Update School</button>
                <a href="{{ route('manage.schools') }}" class="cancel-btn">Cancel</a>
            </div>
        </form>
    </div>

    <style>
        .edit-school-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }

        .edit-school-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            font-weight: 500;
            color: #333;
        }

        .form-group input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-group input:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.2);
        }

        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .update-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .update-btn:hover {
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
            text-align: center;
            transition: all 0.3s ease;
        }

        .cancel-btn:hover {
            background-color: #d32f2f;
            transform: translateY(-2px);
        }
    </style>
@endsection 