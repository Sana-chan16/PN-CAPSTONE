@extends('layouts.nav')

@section('content')
<div class="page-container">
    <div class="header-section">
        <h2>Create New School</h2>
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

    <form action="{{ route('training.schools.store') }}" method="POST" class="form-container">
        @csrf
        <div class="form-section">
            <h3>School Information</h3>
            <div class="form-group">
                <label for="school_id">School ID</label>
                <input type="text" id="school_id" name="school_id" class="form-control" required>
                @error('school_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="name">School Name</label>
                <input type="text" id="name" name="name" class="form-control" required>
                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="department">Department</label>
                <input type="text" id="department" name="department" class="form-control" required>
                @error('department')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="course">Course</label>
                <input type="text" id="course" name="course" class="form-control" required>
                @error('course')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="num_semesters">Number of Semesters</label>
                    <input type="number" id="num_semesters" name="num_semesters" class="form-control" required>
                    @error('num_semesters')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="passing_grade_range">Passing Grade Range</label>
                    <input type="text" id="passing_grade_range" name="passing_grade_range" 
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
                           class="form-control" required
                           placeholder="3.1 - 5.0"
                           onchange="validateGradeRange(this.value, 'failing')"
                           oninput="this.setCustomValidity('')">
                    <small class="text-muted">Enter failing grade range</small>
                    @error('failing_grade_range')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3>Terms</h3>
            <div class="terms-container">
                <div class="term-checkbox">
                    <input type="checkbox" id="term1" name="terms[]" value="Prelim">
                    <label for="term1">Prelim</label>
                </div>
                <div class="term-checkbox">
                    <input type="checkbox" id="term2" name="terms[]" value="Midterm">
                    <label for="term2">Midterm</label>
                </div>
                <div class="term-checkbox">
                    <input type="checkbox" id="term3" name="terms[]" value="Semi-Final">
                    <label for="term3">Semi-Final</label>
                </div>
                <div class="term-checkbox">
                    <input type="checkbox" id="term4" name="terms[]" value="Finals">
                    <label for="term4">Finals</label>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="section-header">
                <h3>Subjects</h3>
                <button type="button" id="add-subject" class="add-btn">Add Subject</button>
            </div>
            <div id="subjects-container">
                <!-- Default subject row -->
                <div class="subject-row">
                    <input type="text" name="subjects[0][offer_code]" class="form-control" placeholder="Offer Code" required>
                    <input type="text" name="subjects[0][name]" class="form-control" placeholder="Subject Name" required>
                    <input type="text" name="subjects[0][instructor]" class="form-control" placeholder="Instructor" required>
                    <input type="text" name="subjects[0][schedule]" class="form-control" placeholder="Schedule" required>
                    <button type="button" class="remove-btn" onclick="removeSubject(this)">×</button>
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="section-header">
                <h3>Classes</h3>
                <button type="button" id="add-class" class="add-btn">Add Class</button>
            </div>
            <div id="classes-container">
                <!-- Default class item -->
                <div class="class-item">
                    <div class="class-header">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="classes[0][class_id]">Class ID</label>
                                <input type="text" name="classes[0][class_id]" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="classes[0][name]">Class Name</label>
                                <input type="text" name="classes[0][name]" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Students</label>
                        <div class="student-filter">
                            <input type="text" class="form-control search-input" placeholder="Search students...">
                            <select class="form-control batch-select">
                                <option value="">All Batches</option>
                                @forelse($batches ?? [] as $batch)
                                    <option value="{{ $batch }}">Batch {{ $batch }}</option>
                                @empty
                                    <option value="" disabled>No batches available</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="students-list">
                            @forelse($students ?? [] as $student)
                                <div class="student-item" data-batch="{{ $student->batch }}">
                                    <input type="checkbox" name="classes[0][students][]" value="{{ $student->user_id }}" id="student-0-{{ $student->user_id }}">
                                    <label for="student-0-{{ $student->user_id }}">
                                        {{ $student->user_id }} - {{ $student->user_fname }} {{ $student->user_lname }} (Batch {{ $student->batch }})
                                    </label>
                                </div>
                            @empty
                                <div class="no-students">No students available</div>
                            @endforelse
                        </div>
                    </div>
                    <button type="button" class="remove-btn" onclick="removeClass(0)">Remove Class</button>
                </div>
            </div>
        </div>

        <div class="button-group">
            <button type="submit" class="create-btn">Create School</button>
            <button type="button" onclick="window.history.back()" class="cancel-btn">Cancel</button>
        </div>
    </form>
</div>

<style>
.page-container {
    padding: 20px;
}

.header-section {
    margin-bottom: 24px;
}

.header-section h2 {
    font-size: 24px;
    color: #333;
    margin: 0;
}

.form-container {
    background: white;
    padding: 24px;
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.form-section {
    margin-bottom: 32px;
    padding: 24px;
    border: 1px solid #eee;
    border-radius: 8px;
    background: white;
}

.form-section h3 {
    margin: 0 0 20px 0;
    color: #333;
    font-size: 18px;
    font-weight: 500;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #333;
    font-weight: 500;
}

.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.form-control:focus {
    border-color: #22bbea;
    outline: none;
    box-shadow: 0 0 0 2px rgba(34, 187, 234, 0.2);
}

.error-message {
    color: #dc3545;
    font-size: 12px;
    margin-top: 4px;
}

.add-btn {
    padding: 8px 16px;
    background: #22bbea;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s;
}

.add-btn:hover {
    background: #1da7d4;
}

.button-group {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 32px;
}

.create-btn {
    padding: 10px 24px;
    background: #22bbea;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s;
}

.create-btn:hover {
    background: #1da7d4;
}

.cancel-btn {
    padding: 10px 24px;
    background: #ff9933;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s;
}

.cancel-btn:hover {
    background:rgb(251, 136, 21);
}

.terms-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 16px;
}

.term-checkbox {
    display: flex;
    align-items: center;
    gap: 8px;
}

.term-checkbox input[type="checkbox"] {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.term-checkbox label {
    font-size: 14px;
    color: #333;
    cursor: pointer;
}

.subject-row {
    display: grid;
    grid-template-columns: 1fr 2fr 2fr 2fr auto;
    gap: 12px;
    margin-bottom: 12px;
    align-items: start;
}

.class-item {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 20px;
    margin-bottom: 20px;
    background: #f8f9fa;
    width: 100%;
    box-sizing: border-box;
}

.class-header {
    margin-bottom: 20px;
    width: 100%;
}

.student-filter {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 16px;
    margin-bottom: 16px;
    width: 100%;
}

.search-input, 
.batch-select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    box-sizing: border-box;
}

.students-list {
    max-height: 300px;
    overflow-y: auto;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 16px;
    background: white;
    margin-top: 10px;
    width: 100%;
    box-sizing: border-box;
}

.student-item {
    display: flex;
    align-items: center;
    padding: 10px;
    border-bottom: 1px solid #eee;
    transition: background-color 0.2s;
}

.student-item:last-child {
    border-bottom: none;
}

.student-item:hover {
    background-color: #f0f0f0;
}

.student-item input[type="checkbox"] {
    margin-right: 12px;
    width: 16px;
    height: 16px;
}

.student-item label {
    margin: 0;
    cursor: pointer;
    font-size: 14px;
    color: #333;
    flex: 1;
}

.no-students {
    padding: 16px;
    text-align: center;
    color: #666;
    font-size: 14px;
    background-color: #f8f9fa;
    border-radius: 4px;
}

.remove-btn {
    padding: 6px 12px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s;
}

.remove-btn:hover {
    background: #c82333;
}

.alert {
    padding: 12px 16px;
    margin-bottom: 20px;
    border-radius: 4px;
    font-size: 14px;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.no-results-message {
    padding: 16px;
    text-align: center;
    color: #666;
    font-style: italic;
    background: #f9f9f9;
    border-radius: 4px;
    margin: 10px 0;
}

.student-item {
    display: flex;
    align-items: center;
    padding: 10px;
    border-bottom: 1px solid #eee;
    transition: background-color 0.2s;
}

.student-item:last-child {
    border-bottom: none;
}

.student-item:hover {
    background-color: #f0f0f0;
}

.student-item input[type="checkbox"] {
    margin-right: 12px;
    width: 16px;
    height: 16px;
    flex-shrink: 0;
}

.student-item label {
    margin: 0;
    cursor: pointer;
    font-size: 14px;
    color: #333;
    flex: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

@media (max-width: 1024px) {
    .page-container {
        padding: 16px;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .subject-row {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
let subjectCount = 1;
let classCount = 1;

// Initialize filtering for the default class
document.addEventListener('DOMContentLoaded', function() {
    initializeFiltering(document.querySelector('.class-item'));
});

function initializeFiltering(classElement) {
    const searchInput = classElement.querySelector('.search-input');
    const batchSelect = classElement.querySelector('.batch-select');
    const studentItems = classElement.querySelectorAll('.student-item');
    const studentsList = classElement.querySelector('.students-list');

    function filterStudents() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedBatch = batchSelect.value;
        let hasVisibleStudents = false;

        studentItems.forEach(item => {
            const studentLabel = item.querySelector('label').textContent.toLowerCase();
            const studentBatch = item.dataset.batch.toString();
            const matchesSearch = studentLabel.includes(searchTerm);
            const matchesBatch = !selectedBatch || studentBatch === selectedBatch;
            
            if (matchesSearch && matchesBatch) {
                item.style.display = 'flex';
                hasVisibleStudents = true;
            } else {
                item.style.display = 'none';
            }
        });

        // Handle no results message
        let noResultsMessage = studentsList.querySelector('.no-results-message');
        if (!hasVisibleStudents) {
            if (!noResultsMessage) {
                noResultsMessage = document.createElement('div');
                noResultsMessage.className = 'no-results-message';
                studentsList.appendChild(noResultsMessage);
            }
            noResultsMessage.textContent = `No students found${selectedBatch ? ` in Batch ${selectedBatch}` : ''}${searchTerm ? ` matching "${searchTerm}"` : ''}`;
            noResultsMessage.style.display = 'block';
        } else if (noResultsMessage) {
            noResultsMessage.style.display = 'none';
        }
    }

    searchInput.addEventListener('input', filterStudents);
    batchSelect.addEventListener('change', filterStudents);

    // Initial filter
    filterStudents();
}

document.getElementById('add-subject').addEventListener('click', function() {
    const container = document.getElementById('subjects-container');
    const subjectDiv = document.createElement('div');
    subjectDiv.className = 'subject-row';
    subjectDiv.innerHTML = `
        <input type="text" name="subjects[${subjectCount}][offer_code]" class="form-control" placeholder="Offer Code" required>
        <input type="text" name="subjects[${subjectCount}][name]" class="form-control" placeholder="Subject Name" required>
        <input type="text" name="subjects[${subjectCount}][instructor]" class="form-control" placeholder="Instructor" required>
        <input type="text" name="subjects[${subjectCount}][schedule]" class="form-control" placeholder="Schedule" required>
        <button type="button" class="remove-btn" onclick="removeSubject(this)">×</button>
    `;
    container.appendChild(subjectDiv);
    subjectCount++;
});

function removeSubject(button) {
    button.closest('.subject-row').remove();
    updateSubjectIndices();
}

function updateSubjectIndices() {
    const subjects = document.querySelectorAll('.subject-row');
    subjects.forEach((subject, index) => {
        const inputs = subject.querySelectorAll('input');
        inputs.forEach(input => {
            const name = input.name;
            input.name = name.replace(/\[\d+\]/, `[${index}]`);
        });
    });
    subjectCount = subjects.length;
}

document.getElementById('add-class').addEventListener('click', function() {
    const container = document.getElementById('classes-container');
    const classHtml = `
        <div class="class-item" id="class-${classCount}">
            <div class="form-row">
                <div class="form-group">
                    <label for="classes[${classCount}][class_id]">Class ID</label>
                    <input type="text" name="classes[${classCount}][class_id]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="classes[${classCount}][name]">Class Name</label>
                    <input type="text" name="classes[${classCount}][name]" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label>Students</label>
                <div class="student-filter">
                    <input type="text" class="form-control search-input" placeholder="Search students...">
                    <select class="form-control batch-select">
                        <option value="">All Batches</option>
                        @forelse($batches ?? [] as $batch)
                            <option value="{{ $batch }}">Batch {{ $batch }}</option>
                        @empty
                            <option value="" disabled>No batches available</option>
                        @endforelse
                    </select>
                </div>
                <div class="students-list">
                    @forelse($students ?? [] as $student)
                        <div class="student-item" data-batch="{{ $student->batch }}">
                            <input type="checkbox" name="classes[${classCount}][students][]" value="{{ $student->user_id }}" id="student-${classCount}-{{ $student->user_id }}">
                            <label for="student-${classCount}-{{ $student->user_id }}">
                                {{ $student->user_id }} - {{ $student->user_fname }} {{ $student->user_lname }} (Batch {{ $student->batch }})
                            </label>
                        </div>
                    @empty
                        <div class="no-students">No students available</div>
                    @endforelse
                </div>
            </div>
            <button type="button" class="remove-btn" onclick="removeClass(${classCount})">Remove Class</button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', classHtml);
    
    // Initialize filtering for the new class
    initializeFiltering(document.getElementById(`class-${classCount}`));
    classCount++;
});

function removeClass(index) {
    document.getElementById(`class-${index}`).remove();
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
                        input.setCustomValidity('Failing grade range cannot overlap with passing grade range');
                        return;
                    }
                }
            }
        }
    }
    
    // If all validations pass
    input.setCustomValidity('');
}

// Add to form validation
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
@endsection 