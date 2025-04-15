@extends('layouts.nav')

@section('content')
    <div class="create-school-container">
        <h1>Add New School</h1>
        
        <form action="{{ route('manage.schools.store') }}" method="POST" class="create-form">
            @csrf
            
            <div class="form-section">
                <h2>Basic Information</h2>
                <div class="form-group">
                    <label for="school_id">School ID</label>
                    <input type="text" name="school_id" id="school_id" required>
                </div>

                <div class="form-group">
                    <label for="school_name">Name of School</label>
                    <input type="text" name="school_name" id="school_name" required>
                </div>

                <div class="form-group">
                    <label for="department">Department</label>
                    <input type="text" name="department" id="department" required>
                </div>

                <div class="form-group">
                    <label for="course">Course</label>
                    <input type="text" name="course" id="course" required>
                </div>

                <div class="form-group">
                    <label for="semesters">Number of Semesters</label>
                    <input type="number" name="semesters" id="semesters" min="1" required>
                </div>
            </div>

            <div class="form-section">
                <h2>Terms</h2>
                <div class="checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="terms[]" value="prelim"> Prelim
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="terms[]" value="midterm"> Midterm
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="terms[]" value="quarter_final"> Quarter Final
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="terms[]" value="semi_final"> Semi-Final
                    </label>
                    <label class="checkbox-label">
                        <input type="checkbox" name="terms[]" value="final"> Finals
                    </label>
                </div>
            </div>

            <div class="form-section">
                <h2>Subjects</h2>
                <div id="subjects-container">
                    <div class="subject-entry">
                        <div class="form-group">
                            <label for="subjects[0][offer_code]">Offer Code</label>
                            <input type="text" name="subjects[0][offer_code]" required>
                        </div>
                        <div class="form-group">
                            <label for="subjects[0][subject_name]">Subject Name</label>
                            <input type="text" name="subjects[0][subject_name]" required>
                        </div>
                        <div class="form-group">
                            <label for="subjects[0][instructor]">Instructor</label>
                            <input type="text" name="subjects[0][instructor]" required>
                        </div>
                        <div class="form-group">
                            <label for="subjects[0][schedule]">Schedule</label>
                            <input type="text" name="subjects[0][schedule]" required>
                        </div>
                    </div>
                </div>
                <button type="button" id="add-subject" class="add-subject-btn">Add Another Subject</button>
            </div>

            <div class="form-actions">
                <button type="submit" class="save-btn">Save School</button>
                <a href="{{ route('manage.students') }}" class="cancel-btn">Cancel</a>
            </div>
        </form>
    </div>

    <style>
        .create-school-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }

        .create-form {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .form-section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .form-section h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 18px;
        }

        .form-group {
            margin-bottom: 15px;
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

        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }

        .subject-entry {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 4px;
        }

        .add-subject-btn {
            background-color: #22bbea;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .add-subject-btn:hover {
            background-color: #1a9bc8;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 30px;
            justify-content: flex-end;
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
            background-color: #ff9933;
            color: white;
        }

        .save-btn:hover {
            background-color: #e68a00;
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

    <script>
        document.getElementById('add-subject').addEventListener('click', function() {
            const container = document.getElementById('subjects-container');
            const subjectCount = container.children.length;
            
            const newSubject = document.createElement('div');
            newSubject.className = 'subject-entry';
            newSubject.innerHTML = `
                <div class="form-group">
                    <label for="subjects[${subjectCount}][offer_code]">Offer Code</label>
                    <input type="text" name="subjects[${subjectCount}][offer_code]" required>
                </div>
                <div class="form-group">
                    <label for="subjects[${subjectCount}][subject_name]">Subject Name</label>
                    <input type="text" name="subjects[${subjectCount}][subject_name]" required>
                </div>
                <div class="form-group">
                    <label for="subjects[${subjectCount}][instructor]">Instructor</label>
                    <input type="text" name="subjects[${subjectCount}][instructor]" required>
                </div>
                <div class="form-group">
                    <label for="subjects[${subjectCount}][schedule]">Schedule</label>
                    <input type="text" name="subjects[${subjectCount}][schedule]" required>
                </div>
            `;
            
            container.appendChild(newSubject);
        });
    </script>
@endsection 