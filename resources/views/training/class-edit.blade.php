@extends('layouts.nav')

@section('content')
    <div class="edit-class-container">
        <h1>Edit Class: {{ $class->class_name }}</h1>
        
        <form action="{{ route('manage.classes.update', [$school->id, $class->id]) }}" method="POST" class="edit-class-form">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="class_name">Class Name</label>
                <input type="text" id="class_name" name="class_name" value="{{ $class->class_name }}" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="update-btn">Update Class</button>
                <a href="{{ route('manage.schools.view', $school->id) }}" class="cancel-btn">Cancel</a>
            </div>
        </form>
    </div>

    <style>
        .edit-class-container {
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

        .edit-class-form {
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