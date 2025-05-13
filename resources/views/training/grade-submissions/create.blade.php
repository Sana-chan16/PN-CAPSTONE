@extends('layouts.nav')

@section('content')
<link rel="stylesheet" href="{{ asset('css/training/grade-submissions/create.css') }}">

<h1>Create Grade Submission</h1>
<hr>
<div class="container">
    <!-- Error Message -->
    @if (session('error'))
        <div class="alert error">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('training.grade-submissions.store') }}" method="POST" class="form-container">
        @csrf

         <div class="form-group">
                <label for="school_id">Select School</label>
                <select name="school_id" id="school_id" required>
                    <option value="">-- Select School --</option>
                    @foreach ($schools as $school)
                        <option value="{{ $school->school_id }}" 
                            {{ request('school_id') == $school->school_id ? 'selected' : '' }}>
                            {{ $school->name }}
                        </option>
                    @endforeach
                </select>
        </div>

        <div class="form-group">
            <label for="class_id">Select Class</label>
            <select name="class_id" id="class_id" required>
                <option value="">-- Select Class --</option>
                @foreach ($classes as $class)
                    <option value="{{ $class->class_id }}" data-school-id="{{ $class->school_id }}">{{ $class->class_name }} ({{ $class->batch }})</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="semester">Semester</label>
            <select name="semester" id="semester" required>
                <option value="">-- Select Semester --</option>
                <option value="1st SEMESTER">1st SEMESTER</option>
                <option value="2nd SEMESTER">2nd SEMESTER</option>
            </select>
        </div>

        <div class="form-group">
            <label for="term">Term</label>
            <select name="term" id="term" required>
                <option value="">-- Select Term --</option>
            </select>
        </div>

        <div class="form-group">
            <label for="academic_year">Academic Year</label>
            <input type="text" name="academic_year" id="academic_year" required>
        </div>

        <div class="form-group">
            <label>Select Subjects</label>
            <div id="subjects-container" class="checkbox-group">
                <!-- Subjects will populate here as checkboxes -->
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">Create Submission</button>
            <a href="{{ route('training.grade-submissions.index') }}" class="btn-cancel">Cancel</a>
        </div>
    </form>
</div>

<!-- JavaScript to fetch subjects when school and class are selected -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const schoolSelect = document.getElementById('school_id');
    const classSelect = document.getElementById('class_id');
    const termSelect = document.getElementById('term');
    const subjectsContainer = document.getElementById('subjects-container');

    // Function to fetch school terms
    function fetchSchoolTerms(schoolId) {
        if (!schoolId) {
            termSelect.innerHTML = '<option value="">-- Select Term --</option>';
            return;
        }

        fetch(`/api/schools/${schoolId}/terms`)
            .then(response => response.json())
            .then(data => {
                termSelect.innerHTML = '<option value="">-- Select Term --</option>';
                data.terms.forEach(term => {
                    const option = document.createElement('option');
                    option.value = term;
                    option.textContent = term.charAt(0).toUpperCase() + term.slice(1).replace('_', ' ');
                    termSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching terms:', error);
                termSelect.innerHTML = '<option value="">-- Select Term --</option>';
            });
    }

    // Filter classes based on selected school
    schoolSelect.addEventListener('change', function() {
        const schoolId = this.value;
        
        // Fetch terms for selected school
        fetchSchoolTerms(schoolId);
        
        // Filter classes to show only those belonging to selected school
        Array.from(classSelect.options).forEach(option => {
            if (option.value === '') return; // Skip placeholder option
            const classSchoolId = option.dataset.schoolId;
            option.style.display = !schoolId || classSchoolId === schoolId ? '' : 'none';
        });

        // Reset class selection
        classSelect.value = '';
        
        // Clear subjects
        subjectsContainer.innerHTML = '';

        if (schoolId && classSelect.value) {
            fetchSubjects(schoolId, classSelect.value);
        }
    });

    // Fetch subjects when class is selected
    classSelect.addEventListener('change', function() {
        const schoolId = schoolSelect.value;
        const classId = this.value;

        if (schoolId && classId) {
            fetchSubjects(schoolId, classId);
        } else {
            subjectsContainer.innerHTML = '';
        }
    });

    function fetchSubjects(schoolId, classId) {
        fetch(`/training/subjects/by-school-and-class?school_id=${schoolId}&class_id=${classId}`)
            .then(response => response.json())
            .then(subjects => {
                subjectsContainer.innerHTML = '';
                if (subjects.length === 0) {
                    subjectsContainer.innerHTML = '<p>No subjects found for this school and class.</p>';
                    return;
                }
                subjects.forEach(subject => {
                    const div = document.createElement('div');
                    div.className = 'checkbox-item';
                    div.innerHTML = `
                        <label>
                            <input type="checkbox" name="subject_ids[]" value="${subject.id}" required>
                            ${subject.name} (${subject.offer_code})
                        </label>
                    `;
                    subjectsContainer.appendChild(div);
                });
            })
            .catch(error => {
                console.error('Error fetching subjects:', error);
                subjectsContainer.innerHTML = '<p class="error">Failed to load subjects. Please try again.</p>';
            });
    }

    // If school_id is pre-selected, trigger change event
    if (schoolSelect.value) {
        schoolSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection
