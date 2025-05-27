@extends('layouts.nav')

@section('content')
<div class="create-submission-container">
    <h1>Create Intern Grade</h1>
    
    @if (session('error'))
        <div class="alert-custom alert-error-custom">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('training.intern-grades.store') }}" method="POST" class="submission-form">
        @csrf

        <div class="form-grid">
            <div class="form-group-custom">
                <label for="school_id">Select School:</label>
                <select name="school_id" id="school_id" required>
                    <option value="">-- Select School --</option>
                    @foreach ($schools as $school)
                        <option value="{{ $school->school_id }}" 
                            {{ old('school_id') == $school->school_id ? 'selected' : '' }}>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </select>
                @error('school_id')
                    <span class="error-message-custom">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group-custom">
                <label for="class_id">Select Class:</label>
                <select name="class_id" id="class_id" required disabled>
                    <option value="">-- Select Class --</option>
                </select>
                @error('class_id')
                    <span class="error-message-custom">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group-custom">
                <label for="subject_id">Select Subject:</label>
                <select name="subject_id" id="subject_id" required disabled>
                    <option value="">-- Select Subject --</option>
                </select>
                @error('subject_id')
                    <span class="error-message-custom">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group-custom">
                <label for="intern_id">Select Intern:</label>
                <select name="intern_id" id="intern_id" required disabled>
                    <option value="">-- Select Intern --</option>
                </select>
                @error('intern_id')
                    <span class="error-message-custom">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group-custom">
                <label for="grade">Grade:</label>
                <input type="text" name="grade" id="grade" required 
                       value="{{ old('grade') }}" 
                       placeholder="Enter grade (e.g., 1.0)">
                @error('grade')
                    <span class="error-message-custom">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group-custom">
                <label for="remarks">Remarks:</label>
                <textarea name="remarks" id="remarks" rows="3" 
                          placeholder="Enter any remarks about the grade">{{ old('remarks') }}</textarea>
                @error('remarks')
                    <span class="error-message-custom">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-actions-custom">
            <button type="submit" class="btn-custom btn-primary-custom">Submit Grade</button>
            <a href="{{ route('training.intern-grades.index') }}" class="btn-custom btn-secondary-custom">Cancel</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const schoolSelect = document.getElementById('school_id');
    const classSelect = document.getElementById('class_id');
    const subjectSelect = document.getElementById('subject_id');
    const internSelect = document.getElementById('intern_id');

    // Function to populate classes dropdown
    function populateClasses(classes) {
        classSelect.innerHTML = '<option value="">-- Select Class --</option>';
        if (classes && classes.length > 0) {
            classes.forEach(class_ => {
                const option = document.createElement('option');
                option.value = class_.class_id;
                option.textContent = `${class_.class_name} (${class_.batch})`;
                classSelect.appendChild(option);
            });
            classSelect.disabled = false;
        } else {
            classSelect.disabled = true;
        }
    }

    // Function to populate subjects dropdown
    function populateSubjects(subjects) {
        subjectSelect.innerHTML = '<option value="">-- Select Subject --</option>';
        if (subjects && subjects.length > 0) {
            subjects.forEach(subject => {
                const option = document.createElement('option');
                option.value = subject.id;
                option.textContent = `${subject.name} (${subject.offer_code})`;
                subjectSelect.appendChild(option);
            });
            subjectSelect.disabled = false;
        } else {
            subjectSelect.disabled = true;
        }
    }

    // Function to populate interns dropdown
    function populateInterns(interns) {
        internSelect.innerHTML = '<option value="">-- Select Intern --</option>';
        if (interns && interns.length > 0) {
            interns.forEach(intern => {
                const option = document.createElement('option');
                option.value = intern.user_id;
                option.textContent = `${intern.user_fname} ${intern.user_lname}`;
                internSelect.appendChild(option);
            });
            internSelect.disabled = false;
        } else {
            internSelect.disabled = true;
        }
    }

    // Handle school selection
    schoolSelect.addEventListener('change', function() {
        const schoolId = this.value;
        
        // Reset and disable dependent fields
        classSelect.innerHTML = '<option value="">-- Select Class --</option>';
        classSelect.disabled = true;
        
        subjectSelect.innerHTML = '<option value="">-- Select Subject --</option>';
        subjectSelect.disabled = true;
        
        internSelect.innerHTML = '<option value="">-- Select Intern --</option>';
        internSelect.disabled = true;

        if (schoolId) {
            // Fetch classes for selected school
            fetch(`/training/api/schools/${schoolId}/classes`)
                .then(response => response.json())
                .then(classes => {
                    populateClasses(classes);
                })
                .catch(error => {
                    console.error('Error fetching classes:', error);
                });

            // Fetch subjects for selected school
            fetch(`/training/subjects/by-school-and-class?school_id=${schoolId}`)
                .then(response => response.json())
                .then(subjects => {
                    populateSubjects(subjects);
                })
                .catch(error => {
                    console.error('Error fetching subjects:', error);
                });

            // Fetch interns for selected school
            fetch(`/training/api/schools/${schoolId}/interns`)
                .then(response => response.json())
                .then(interns => {
                    populateInterns(interns);
                })
                .catch(error => {
                    console.error('Error fetching interns:', error);
                });
        }
    });

    // Handle class selection
    classSelect.addEventListener('change', function() {
        const classId = this.value;
        const schoolId = schoolSelect.value;

        if (classId && schoolId) {
            // Fetch subjects for selected class
            fetch(`/training/subjects/by-school-and-class?school_id=${schoolId}&class_id=${classId}`)
                .then(response => response.json())
                .then(subjects => {
                    populateSubjects(subjects);
                })
                .catch(error => {
                    console.error('Error fetching subjects:', error);
                });
        }
    });
});
</script>

<style>
    .create-submission-container {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group-custom {
        margin-bottom: 15px;
    }

    .form-group-custom label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
    }

    .form-group-custom select,
    .form-group-custom input[type="text"],
    .form-group-custom textarea {
        width: 100%;
        padding: 8px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        font-size: 1rem;
    }

    .form-group-custom textarea {
        resize: vertical;
    }

    .error-message-custom {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 5px;
    }

    .form-actions-custom {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 20px;
    }
</style>
@endsection 