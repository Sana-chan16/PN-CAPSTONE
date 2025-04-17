@extends('layouts.nav')

@section('content')
    <div class="header-container">
        <h1>Submit Grades for {{ $student->user_fname }} {{ $student->user_lname }}</h1>
        <div class="action-buttons">
            <a href="{{ route('grade.submission.students', ['class_id' => $class->id]) }}" class="back-btn">Back to Students</a>
        </div>
    </div>

    <section class="form-section">
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

        .back-btn {
            background-color: #2196F3;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background-color: #1976D2;
            transform: translateY(-2px);
        }

        .form-section {
            padding: 20px;
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