@extends('layouts.nav')

@section('content')
    <div class="edit-student-container">
        <h1>Edit Student Information</h1>
        
        <form action="{{ route('manage.students.update', $student->user_id) }}" method="POST" class="edit-form">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="user_lname">Last Name</label>
                <input type="text" name="user_lname" id="user_lname" value="{{ $student->user_lname }}" required>
            </div>

            <div class="form-group">
                <label for="user_fname">First Name</label>
                <input type="text" name="user_fname" id="user_fname" value="{{ $student->user_fname }}" required>
            </div>

            <div class="form-group">
                <label for="user_email">Email</label>
                <input type="email" name="user_email" id="user_email" value="{{ $student->user_email }}" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="save-btn">Save Changes</button>
                <a href="{{ route('manage.students') }}" class="cancel-btn">Cancel</a>
            </div>
        </form>
    </div>

    <style>
        .edit-student-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }

        .edit-form {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .form-group input:focus {
            outline: none;
            border-color: #22bbea;
            box-shadow: 0 0 0 2px rgba(34, 187, 234, 0.2);
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        .save-btn,
        .cancel-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .save-btn {
            background-color: #4CAF50;
            color: white;
        }

        .save-btn:hover {
            background-color: #45a049;
        }

        .cancel-btn {
            background-color: #f44336;
            color: white;
            text-align: center;
            line-height: 38px;
        }

        .cancel-btn:hover {
            background-color: #da190b;
        }
    </style>
@endsection 