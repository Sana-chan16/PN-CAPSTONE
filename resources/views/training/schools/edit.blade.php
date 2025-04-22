@extends('layouts.nav')

@section('content')
<div class="page-container">
    <div class="header-section">
        <h2>Edit School</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('training.schools.update', $school) }}" method="POST" class="form-container">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="school_id">School ID</label>
            <input type="text" id="school_id" name="school_id" value="{{ old('school_id', $school->school_id) }}" required>
            @error('school_id')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="name">School Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $school->name) }}" required>
            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="department">Department</label>
            <input type="text" id="department" name="department" value="{{ old('department', $school->department) }}" required>
            @error('department')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="course">Course</label>
            <input type="text" id="course" name="course" value="{{ old('course', $school->course) }}" required>
            @error('course')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="semester_count">Number of Semesters</label>
            <input type="number" id="semester_count" name="semester_count" value="{{ old('semester_count', $school->num_semesters) }}" required>
            @error('semester_count')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="passing_grade_range">Passing Grade Range</label>
            <input type="text" id="passing_grade_range" name="passing_grade_range" 
                   value="{{ old('passing_grade_range', $school->passing_grade_range) }}" 
                   class="form-control" required
                   placeholder="1.0 - 5.0"
                   onchange="validateGradeRange(this.value, 'passing')"
                   oninput="this.setCustomValidity('')">
            <small class="text-muted">Enter passing grade range</small>
            @error('passing_grade_range')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="failing_grade_range">Failing Grade Range</label>
            <input type="text" id="failing_grade_range" name="failing_grade_range" 
                   value="{{ old('failing_grade_range', $school->failing_grade_range) }}" 
                   class="form-control" required
                   placeholder="3.1 - 5.0"
                   onchange="validateGradeRange(this.value, 'failing')"
                   oninput="this.setCustomValidity('')">
            <small class="text-muted">Enter failing grade range</small>
            @error('failing_grade_range')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Terms</label>
            <div class="checkbox-group">
                @php
                    $currentTerms = is_array($school->terms) ? $school->terms : json_decode($school->terms ?? '[]', true);
                @endphp
                <label class="checkbox-label">
                    <input type="checkbox" name="terms[]" value="prelim" {{ in_array('prelim', old('terms', $currentTerms)) ? 'checked' : '' }}>
                    Prelim
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="terms[]" value="midterm" {{ in_array('midterm', old('terms', $currentTerms)) ? 'checked' : '' }}>
                    Midterm
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="terms[]" value="semi_final" {{ in_array('semi_final', old('terms', $currentTerms)) ? 'checked' : '' }}>
                    Semi Final
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="terms[]" value="final" {{ in_array('final', old('terms', $currentTerms)) ? 'checked' : '' }}>
                    Final
                </label>
            </div>
            @error('terms')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="subjects-section">
            <h3>Subjects</h3>
            <div id="subjects-container">
                @php
                    $currentSubjects = is_array($school->subjects) ? $school->subjects : json_decode($school->subjects ?? '[]', true);
                @endphp
                @foreach(old('subjects', $currentSubjects) as $index => $subject)
                    <div class="subject-row">
                        <input type="text" name="subjects[{{ $index }}][offer_code]" placeholder="Offer Code" value="{{ $subject['offer_code'] ?? '' }}" required>
                        <input type="text" name="subjects[{{ $index }}][name]" placeholder="Subject Name" value="{{ $subject['name'] ?? '' }}" required>
                        <input type="text" name="subjects[{{ $index }}][instructor]" placeholder="Instructor" value="{{ $subject['instructor'] ?? '' }}" required>
                        <input type="text" name="subjects[{{ $index }}][schedule]" placeholder="Schedule" value="{{ $subject['schedule'] ?? '' }}" required>
                        <button type="button" class="btn-remove" onclick="removeSubject(this)">×</button>
                    </div>
                @endforeach
            </div>
            <button type="button" id="add-subject" class="btn-add">Add Subject</button>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">Update School</button>
            <button type="button" onclick="window.history.back()" class="btn-cancel">Cancel</button>
        </div>
    </form>
</div>

<script>
let subjectCount = {{ count(old('subjects', is_array($school->subjects) ? $school->subjects : json_decode($school->subjects ?? '[]', true))) }};

document.getElementById('add-subject').addEventListener('click', function() {
    const container = document.getElementById('subjects-container');
    const row = document.createElement('div');
    row.className = 'subject-row';
    row.innerHTML = `
        <input type="text" name="subjects[${subjectCount}][offer_code]" placeholder="Offer Code" required>
        <input type="text" name="subjects[${subjectCount}][name]" placeholder="Subject Name" required>
        <input type="text" name="subjects[${subjectCount}][instructor]" placeholder="Instructor" required>
        <input type="text" name="subjects[${subjectCount}][schedule]" placeholder="Schedule" required>
        <button type="button" class="btn-remove" onclick="removeSubject(this)">×</button>
    `;
    container.appendChild(row);
    subjectCount++;
});

function removeSubject(button) {
    const row = button.parentElement;
    row.remove();
    updateSubjectIndices();
}

function updateSubjectIndices() {
    const rows = document.querySelectorAll('.subject-row');
    rows.forEach((row, index) => {
        const inputs = row.querySelectorAll('input');
        inputs.forEach(input => {
            const name = input.name;
            input.name = name.replace(/\[\d+\]/, `[${index}]`);
        });
    });
    subjectCount = rows.length;
}

function validateGradeRange(value, type) {
    const input = document.getElementById(type === 'passing' ? 'passing_grade_range' : 'failing_grade_range');
    
    // First check if empty
    if (!value.trim()) {
        input.setCustomValidity('This field is required');
        return;
    }
    
    // Check format
    const regex = /^\s*([0-9]+(?:\.[0-9]+)?)\s*-\s*([0-9]+(?:\.[0-9]+)?)\s*$/;
    const match = value.match(regex);
    
    if (!match) {
        input.setCustomValidity('Please enter a valid grade range (e.g., 1.0 - 5.0)');
        return;
    }
    
    const first = parseFloat(match[1]);
    const second = parseFloat(match[2]);
    
    // Validate numeric values
    if (isNaN(first) || isNaN(second)) {
        input.setCustomValidity('Please enter valid numeric values');
        return;
    }
    
    // Validate that grades are between 1.0 and 5.0
    if (first < 1.0 || first > 5.0 || second < 1.0 || second > 5.0) {
        input.setCustomValidity('Grades must be between 1.0 and 5.0');
        return;
    }
    
    // Get the other input's value
    const otherInput = document.getElementById(type === 'passing' ? 'failing_grade_range' : 'passing_grade_range');
    const otherValue = otherInput.value.trim();
    
    if (otherValue) {
        const otherMatch = otherValue.match(regex);
        if (otherMatch) {
            const otherFirst = parseFloat(otherMatch[1]);
            const otherSecond = parseFloat(otherMatch[2]);
            
            // Determine if we're using ascending (1.0 best) or descending (5.0 best) grading
            const isAscending = first < second;
            const otherIsAscending = otherFirst < otherSecond;
            
            // Both ranges should use the same grading system
            if (isAscending !== otherIsAscending) {
                input.setCustomValidity('Both ranges must use the same grading system (either 1.0 or 5.0 as best grade)');
                return;
            }
            
            // Check for overlap based on grading system
            if (isAscending) { // 1.0 is best grade
                if (type === 'passing') {
                    if (second >= otherFirst) {
                        input.setCustomValidity('Passing grade range cannot overlap with failing grade range');
                        return;
                    }
                } else {
                    if (first <= otherSecond) {
                        input.setCustomValidity('Failing grade range cannot overlap with passing grade range');
                        return;
                    }
                }
            } else { // 5.0 is best grade
                if (type === 'passing') {
                    if (first <= otherSecond) {
                        input.setCustomValidity('Passing grade range cannot overlap with failing grade range');
                        return;
                    }
                } else {
                    if (second >= otherFirst) {
                        input.setCustomValidity('Failing grade range cannot overlap with failing grade range');
                        return;
                    }
                }
            }
        }
    }
    
    // If all validations pass
    input.setCustomValidity('');
}

// Add form validation on submit
document.querySelector('form').addEventListener('submit', function(e) {
    if (!validateForm()) {
        e.preventDefault();
    }
});

function validateForm() {
    const passingRange = document.getElementById('passing_grade_range').value;
    const failingRange = document.getElementById('failing_grade_range').value;
    
    if (!passingRange || !failingRange) {
        alert('Please enter both passing and failing grade ranges');
        return false;
    }
    
    validateGradeRange(passingRange, 'passing');
    validateGradeRange(failingRange, 'failing');
    
    if (!document.getElementById('passing_grade_range').checkValidity() || 
        !document.getElementById('failing_grade_range').checkValidity()) {
        return false;
    }
    
    return true;
}
</script>

<style>
.page-container {
    padding: 20px;
    max-width: 1400px;
    margin: 0 auto;
    width: 100%;
    box-sizing: border-box;
}

.header-section {
    margin-bottom: 24px;
    width: 100%;
}

.form-container {
    background: white;
    padding: 24px;
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    width: 100%;
    box-sizing: border-box;
    max-width: 100%;
}

.form-group {
    margin-bottom: 20px;
    width: 100%;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #333;
    font-weight: 500;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    box-sizing: border-box;
}

.checkbox-group {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.subjects-section {
    margin-top: 24px;
    width: 100%;
}

.subject-row {
    display: grid;
    grid-template-columns: 1fr 2fr 2fr 2fr auto;
    gap: 12px;
    margin-bottom: 12px;
    align-items: start;
    width: 100%;
}

.subject-row input {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    width: 100%;
}

.btn-remove {
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 4px;
    width: 32px;
    height: 32px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.btn-add {
    background: #28a745;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 8px 16px;
    cursor: pointer;
    margin-top: 12px;
}

.form-actions {
    margin-top: 24px;
    display: flex;
    gap: 12px;
}

.btn-submit {
    background: #22bbea;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 10px 20px;
    cursor: pointer;
}

.btn-submit:hover{
    background:rgb(5, 167, 216);
}

.btn-cancel {
    background: #ff9933;
    color: white;
    border: none;
    border-radius: 4px;
    padding: 10px 20px;
    text-decoration: none;
    text-align: center;
}

.btn-cancel:hover{
    background:rgb(254, 138, 22);
}

.error-message {
    color: #dc3545;
    font-size: 14px;
    margin-top: 4px;
}

.alert {
    padding: 12px;
    border-radius: 4px;
    margin-bottom: 20px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.table-container {
    width: 100%;
    overflow-x: auto;
}

.data-table {
    width: 100%;
    min-width: 800px;
}

@media (max-width: 1024px) {
    .page-container {
        padding: 16px;
    }
    
    .subject-row {
        grid-template-columns: 1fr 1fr 1fr 1fr auto;
    }
}

@media (max-width: 768px) {
    .subject-row {
        grid-template-columns: 1fr;
        gap: 8px;
    }
    
    .subject-row button {
        margin-top: 8px;
    }
}
</style>
@endsection 