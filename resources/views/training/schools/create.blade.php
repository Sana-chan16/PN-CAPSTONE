@extends('layouts.nav')

@section('content')


<link rel="stylesheet" href="{{ asset('css/training/school/create.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
    body {
        font-family: Poppins;
    }
</style>
<div class="page-container">
    <div class="header-section">
        <h2>Create New School</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error') || $errors->any())
        <div class="alert alert-danger" style="color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
            <h4 style="margin-top: 0; margin-bottom: 10px; font-size: 16px; font-weight: 600;">Creation unsuccessful</h4>
            <div style="margin: 0; padding: 0;">
                @if(session('error'))
                    <div style="padding: 5px 0; border-bottom: 1px solid #f5c6cb; margin-bottom: 5px;">
                        {{ session('error') }}
                    </div>
                @endif
                @foreach($errors->all() as $error)
                    <div style="padding: 5px 0; border-bottom: 1px solid #f5c6cb; margin-bottom: 5px;">
                        {{ $error }}
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <form action="{{ route('training.schools.store') }}" method="POST" class="form-container" id="createSchoolForm">
        @csrf
        <div id="form-errors" class="alert alert-danger" style="display: none; color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
            <h4 style="margin-top: 0; margin-bottom: 10px; font-size: 16px; font-weight: 600;">Creation unsuccessful</h4>
            <div id="error-list" style="margin: 0; padding: 0;"></div>
        </div>
        
        <div class="form-group">
            <label for="school_id">School ID</label>
            <input type="text" id="school_id" name="school_id" value="{{ old('school_id') }}" required>
            @error('school_id')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="name">School Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="department">Department</label>
            <input type="text" id="department" name="department" value="{{ old('department') }}" required>
            @error('department')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="course">Course</label>
            <input type="text" id="course" name="course" value="{{ old('course') }}" required>
            @error('course')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="semester_count">Number of Semesters</label>
            <input type="number" id="semester_count" name="semester_count" value="{{ old('semester_count') }}" required>
            @error('semester_count')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Grade Range Configuration</label>
            <div class="grade-range-selector">
                <div class="input-group">
                    <label for="passingGradeMin">Passing Grade Min</label>
                    <input type="number" step="0.1" id="passingGradeMin" name="passing_grade_min" 
                        value="{{ old('passing_grade_min') }}" required>
                </div>
                <div class="input-group">
                    <label for="passingGradeMax">Passing Grade Max</label>
                    <input type="number" step="0.1" id="passingGradeMax" name="passing_grade_max" 
                        value="{{ old('passing_grade_max') }}" required>
                </div>
                <div class="input-group">
                    <label for="failingGradeMin">Failing Grade Min</label>
                    <input type="number" step="0.1" id="failingGradeMin" name="failing_grade_min" 
                        value="{{ old('failing_grade_min') }}" required>
                </div>
                <div class="input-group">
                    <label for="failingGradeMax">Failing Grade Max</label>
                    <input type="number" step="0.1" id="failingGradeMax" name="failing_grade_max" 
                        value="{{ old('failing_grade_max') }}" required>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Terms</label>
            <div class="checkbox-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="terms[]" value="prelim" {{ in_array('prelim', old('terms', [])) ? 'checked' : '' }}>
                    Prelim
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="terms[]" value="midterm" {{ in_array('midterm', old('terms', [])) ? 'checked' : '' }}>
                    Midterm
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="terms[]" value="semi_final" {{ in_array('semi_final', old('terms', [])) ? 'checked' : '' }}>
                    Semi Final
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="terms[]" value="final" {{ in_array('final', old('terms', [])) ? 'checked' : '' }}>
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
                @foreach(old('subjects', []) as $index => $subject)
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

        <div class="classes-section">
            <h3>Classes</h3>
            <div id="classes-container">
                @foreach(old('classes', []) as $index => $class)
                    <div class="class-row">
                        <div class="class-header">
                            <div class="class-display">
                                <strong>ID:</strong>
                                <input type="text" name="classes[{{ $index }}][class_id]" placeholder="Class ID" value="{{ $class['class_id'] ?? '' }}" required>
                                <strong>Name:</strong>
                                <input type="text" name="classes[{{ $index }}][name]" placeholder="Class Name" value="{{ $class['name'] ?? '' }}" required>
                            </div>
                            <button type="button" class="btn-select-students" data-class-index="{{ $index }}">Select Students</button>
                            <button type="button" class="btn-remove" onclick="removeClass(this)">×</button>
                        </div>
                        <div id="students-container-{{ $index }}" class="students-container"></div>
                    </div>
                @endforeach
            </div>
            <button type="button" id="add-class" class="btn-add">Add New Class</button>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">Create School</button>
            <a href="{{ route('training.manage-students') }}" class="btn-cancel">Cancel</a>
        </div>
    </form>
</div>

<!-- Student Selection Modal -->
<div id="studentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Select Students</h3>
            <button type="button" class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="batch-filter">
                <label for="batchFilter">Filter by Batch:</label>
                <select id="batchFilter">
                    <option value="">All Batches</option>
                </select>
            </div>
            <div id="modalStudentsContainer" class="students-list">
                <!-- Students will be loaded here -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-save" id="confirmStudentSelection">Save Selection</button>
            <button type="button" class="btn-cancel close-modal">Cancel</button>
        </div>
    </div>
</div>

<script>
function showError(field, message) {
    // Remove any existing error for this field
    const existingError = document.getElementById(`${field}-error`);
    if (existingError) {
        existingError.remove();
    }
    
    if (message) {
        const input = document.querySelector(`[name="${field}"]`) || document.getElementById(field);
        if (input) {
            input.style.borderColor = '#dc3545';
            const errorDiv = document.createElement('div');
            errorDiv.id = `${field}-error`;
            errorDiv.className = 'error-message';
            errorDiv.style.color = '#dc3545';
            errorDiv.style.fontSize = '0.875rem';
            errorDiv.style.marginTop = '0.25rem';
            errorDiv.textContent = message;
            
            // Insert after the input
            input.parentNode.insertBefore(errorDiv, input.nextSibling);
        }
    }
}

// Handle form submission
// Global variables
let subjectCount = {{ count(old('subjects', [])) }};
let classCount = {{ count(old('classes', [])) }};
let currentClassIndex = null;
const modal = document.getElementById('studentModal');

// Helper function to show error messages
function showError(field, message) {
    // Remove any existing error for this field
    const existingError = document.getElementById(`${field}-error`);
    if (existingError) {
        existingError.remove();
    }
    
    if (message) {
        const input = document.querySelector(`[name="${field}"]`) || document.getElementById(field);
        if (input) {
            input.style.borderColor = '#dc3545';
            const errorDiv = document.createElement('div');
            errorDiv.id = `${field}-error`;
            errorDiv.className = 'error-message';
            errorDiv.style.color = '#dc3545';
            errorDiv.style.fontSize = '0.875rem';
            errorDiv.style.marginTop = '0.25rem';
            errorDiv.textContent = message;
            
            // Insert after the input
            input.parentNode.insertBefore(errorDiv, input.nextSibling);
        }
    }
}

// Initialize the application when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createSchoolForm');
    const closeButtons = document.querySelectorAll('.close-modal');
    const confirmButton = document.getElementById('confirmStudentSelection');
    
    // Form submission handler
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Clear previous error messages
        document.querySelectorAll('.error-message').forEach(el => el.remove());
        document.querySelectorAll('input, select, textarea').forEach(input => {
            input.style.borderColor = '';
        });
        
        const errorList = document.getElementById('error-list');
        const formErrors = document.getElementById('form-errors');
        errorList.innerHTML = '';
        formErrors.style.display = 'none';
        
        let isValid = true;
        const errors = [];
        
        // Check required fields
        const requiredInputs = document.querySelectorAll('[required]');
        requiredInputs.forEach(input => {
            const name = input.name || input.id;
            if (!input.value.trim()) {
                const label = document.querySelector(`label[for="${name}"]`);
                const fieldName = label ? label.textContent.trim().replace('*', '') : 'This field';
                showError(name, `${fieldName} is required`);
                errors.push(`${fieldName} is required`);
                isValid = false;
            }
        });
        
        // Check if at least one term is selected
        const terms = document.querySelectorAll('input[name="terms[]"]:checked');
        if (terms.length === 0) {
            showError('terms', 'Please select at least one term');
            errors.push('Please select at least one term');
            isValid = false;
        }
        
        // Check if at least one subject is added
        const subjectRows = document.querySelectorAll('.subject-row');
        if (subjectRows.length === 0) {
            showError('subjects', 'Please add at least one subject');
            errors.push('Please add at least one subject');
            isValid = false;
        } else {
            // Validate each subject row
            subjectRows.forEach((row, index) => {
                const inputs = row.querySelectorAll('input[required]');
                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        const name = input.name || input.id;
                        showError(name, 'This field is required');
                        errors.push(`Subject ${index + 1} is missing required information`);
                        isValid = false;
                    }
                });
            });
        }
        
        // Check if at least one class is added
        const classRows = document.querySelectorAll('.class-row');
        if (classRows.length === 0) {
            showError('classes', 'Please add at least one class');
            errors.push('Please add at least one class');
            isValid = false;
        } else {
            // Validate each class row
            classRows.forEach((row, index) => {
                const inputs = row.querySelectorAll('input[required]');
                let classValid = true;
                
                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        const name = input.name || input.id;
                        showError(name, 'This field is required');
                        errors.push(`Class ${index + 1} is missing required information`);
                        isValid = false;
                        classValid = false;
                    }
                });
                
                // Check if class has students
                if (classValid) {
                    const studentCount = row.querySelectorAll('.student-tag').length;
                    if (studentCount === 0) {
                        errors.push(`Class ${index + 1} must have at least one student`);
                        isValid = false;
                    }
                }
            });
        }
        
        if (!isValid) {
            // Show errors at the top of the form
            errors.forEach(error => {
                const errorDiv = document.createElement('div');
                errorDiv.textContent = error;
                errorDiv.style.padding = '5px 0';
                errorDiv.style.borderBottom = '1px solid #f5c6cb';
                errorDiv.style.marginBottom = '5px';
                errorList.appendChild(errorDiv);
            });
            formErrors.style.display = 'block';
            
            // Scroll to the first error
            const firstError = document.querySelector('.error-message');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            
            return false;
        }
        
        // If all validations pass, submit the form
        this.submit();
    });

    // Add Subject Button
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

    // Add Class Button
    document.getElementById('add-class').addEventListener('click', function() {
        const container = document.getElementById('classes-container');
        const row = document.createElement('div');
        row.className = 'class-row';
        row.innerHTML = `
            <div class="class-header">
                <div class="class-display">
                    <strong>ID:</strong>
                    <input type="text" name="classes[${classCount}][class_id]" placeholder="Class ID" required>
                    <strong>Name:</strong>
                    <input type="text" name="classes[${classCount}][name]" placeholder="Class Name" required>
                </div>
                <button type="button" class="btn-select-students" data-class-index="${classCount}">Select Students</button>
                <button type="button" class="btn-remove" onclick="removeClass(this)">×</button>
            </div>
            <div id="students-container-${classCount}" class="students-container"></div>
        `;
        container.appendChild(row);
        
        // Add event listener for the new select students button
        row.querySelector('.btn-select-students').addEventListener('click', function() {
            currentClassIndex = this.getAttribute('data-class-index');
            openStudentModal();
        });
        
        classCount++;
        document.activeElement.blur(); // Prevent auto-focus triggering modal
    });
    
    // Initialize any existing select students buttons
    document.querySelectorAll('.btn-select-students').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            currentClassIndex = this.getAttribute('data-class-index');
            openStudentModal();
        });
    });

    // Modal event listeners
    closeButtons.forEach(button => {
        button.addEventListener('click', closeStudentModal);
    });
    
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeStudentModal();
        }
    });
    
    confirmButton.addEventListener('click', confirmStudentSelection);
    
    // Initialize any existing select students buttons
    document.querySelectorAll('.btn-select-students').forEach(button => {
        button.addEventListener('click', function() {
            currentClassIndex = this.getAttribute('data-class-index');
            openStudentModal();
        });
    });

    let subjectCount = {{ count(old('subjects', [])) }};
    let classCount = {{ count(old('classes', [])) }};
    let currentClassIndex = null;
    const modal = document.getElementById('studentModal');
    const closeButtons = document.querySelectorAll('.close-modal');
    const confirmButton = document.getElementById('confirmStudentSelection');

    // Add Subject Button
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

    // Add Class Button
    document.getElementById('add-class').addEventListener('click', function() {
        const container = document.getElementById('classes-container');
        const row = document.createElement('div');
        row.className = 'class-row';
        row.innerHTML = `
            <div class="class-header">
                <div class="class-display">
                    <strong>ID:</strong>
                    <input type="text" name="classes[${classCount}][class_id]" placeholder="Class ID" required>
                    <strong>Name:</strong>
                    <input type="text" name="classes[${classCount}][name]" placeholder="Class Name" required>
                </div>
                <button type="button" class="btn-select-students" data-class-index="${classCount}">Select Students</button>
                <button type="button" class="btn-remove" onclick="removeClass(this)">×</button>
            </div>
            <div id="students-container-${classCount}" class="students-container"></div>
        `;
        container.appendChild(row);
        classCount++;
        document.activeElement.blur(); // Prevent auto-focus triggering modal
    });

    // Close modal when clicking close button or outside the modal
    closeButtons.forEach(button => {
        button.addEventListener('click', () => {
            modal.style.display = 'none';
        });
    });

    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Handle confirm button click
    confirmButton.addEventListener('click', function() {
        const selectedStudents = Array.from(document.querySelectorAll('#modalStudentsContainer input[type="checkbox"]:checked'))
            .map(checkbox => ({
                id: checkbox.value,
                name: checkbox.getAttribute('data-name'),
                student_id: checkbox.getAttribute('data-student-id')
            }));

        updateSelectedStudentsList(currentClassIndex, selectedStudents);
        modal.style.display = 'none';
    });
});

// Make these functions globally available
window.removeSubject = function(button) {
    const row = button.parentElement;
    row.remove();
    updateSubjectIndices();
};

window.removeClass = function(button) {
    const row = button.closest('.class-row');
    row.remove();
    updateClassIndices();
};

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

function removeClass(button) {
    const row = button.closest('.class-row');
    row.remove();
    updateClassIndices();
}

function updateClassIndices() {
    const rows = document.querySelectorAll('.class-row');
    rows.forEach((row, index) => {
        const inputs = row.querySelectorAll('input');
        inputs.forEach(input => {
            const name = input.name;
            input.name = name.replace(/\[\d+\]/, `[${index}]`);
        });
        const button = row.querySelector('.btn-select-students');
        if (button) {
            button.dataset.classIndex = index;
        }
        const container = row.querySelector('.students-container');
        if (container) {
            container.id = `students-container-${index}`;
        }
    });
    classCount = rows.length;
}

function loadStudents() {
    fetch('/training/api/students')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(students => {
            const container = document.getElementById('modalStudentsContainer');
            const batchFilter = document.getElementById('batchFilter');
            const batches = new Set();

            // Collect unique batches
            students.forEach(student => {
                if (student.batch) {
                    batches.add(student.batch);
                }
            });

            // Populate batch filter
            batchFilter.innerHTML = '<option value="">All Batches</option>';
            Array.from(batches).sort().forEach(batch => {
                const option = document.createElement('option');
                option.value = batch;
                option.textContent = `Batch ${batch}`;
                batchFilter.appendChild(option);
            });

            // Function to render students
            const renderStudents = (filteredStudents) => {
                container.innerHTML = filteredStudents.map(student => {
                    const studentId = `${student.batch}${student.group}${student.student_number}${student.training_code}`;
                    const fullName = `${student.user_lname}, ${student.user_fname}`;
                    return `
                        <div class="student-item" data-batch="${student.batch || ''}">
                            <label class="student-checkbox">
                                <input type="checkbox" 
                                       value="${student.user_id}"
                                       data-name="${fullName}"
                                       data-student-id="${studentId}">
                                <span>${studentId} - ${fullName}</span>
                            </label>
                        </div>
                    `;
                }).join('');
            };

            // Initial render
            renderStudents(students);

            // Add batch filter event listener
            batchFilter.addEventListener('change', function() {
                const selectedBatch = this.value;
                const filteredStudents = selectedBatch 
                    ? students.filter(student => student.batch === selectedBatch)
                    : students;
                renderStudents(filteredStudents);
            });
        })
        .catch(error => {
            console.error('Error loading students:', error);
            document.getElementById('modalStudentsContainer').innerHTML = 
                `<p class="error-message">Error loading students: ${error.message}</p>`;
        });
}

function updateSelectedStudentsList(classIndex, students) {
    const container = document.getElementById(`students-container-${classIndex}`);
    
    // Create selected students display
    const selectedStudentsHtml = `
        <div class="selected-students">
            <h4>Selected Students:</h4>
            <div class="selected-students-list">
                ${students.map(student => `
                    <div class="selected-student-tag" data-student-id="${student.id}">
                        ${student.student_id} - ${student.name}
                        <span class="remove-student" onclick="removeSelectedStudent(this, ${classIndex}, ${student.id})">&times;</span>
                    </div>
                `).join('')}
            </div>
        </div>
    `;

    // Add hidden inputs for student IDs
    const hiddenInputsHtml = students.map(student => 
        `<input type="hidden" name="classes[${classIndex}][student_ids][]" value="${student.id}">`
    ).join('');

    container.innerHTML = selectedStudentsHtml + hiddenInputsHtml;
}

function removeSelectedStudent(button, classIndex, studentId) {
    const tag = button.parentElement;
    tag.remove();
    
    // Remove the corresponding hidden input
    const hiddenInput = document.querySelector(`input[name="classes[${classIndex}][student_ids][]"][value="${studentId}"]`);
    if (hiddenInput) {
        hiddenInput.remove();
    }
}
</script>

@endsection 