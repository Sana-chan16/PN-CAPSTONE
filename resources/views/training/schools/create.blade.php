@extends('layouts.nav')

@section('content')

<style>
.page-container {
    padding: 20px;
    max-width: 100%;
}
.header-section {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
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
.form-group {
    margin-bottom: 20px;
}
.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #333;
    font-weight: 500;
}
.form-group input[type="text"],
.form-group input[type="number"] {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}
.form-group input[type="text"]:focus,
.form-group input[type="number"]:focus {
    border-color: #4CAF50;
    outline: none;
    box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.2);
}
.checkbox-group {
    display: flex;
    gap: 16px;
}
.subjects-section, .classes-section {
    margin-top: 32px;
}
.subject-row {
    display: flex;
    gap: 8px;
    align-items: center;
    margin-bottom: 8px;
}
.btn-remove {
    background: #c62828;
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    font-size: 18px;
    cursor: pointer;
    transition: background 0.2s;
}
.btn-remove:hover {
    background: #b71c1c;
}
.card {
    background: #f9f9f9;
    border-radius: 6px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.07);
    padding: 16px;
    margin-bottom: 18px;
}
.badge, .selected-student-tag {
    display: inline-block;
    background: #e8f5e9;
    color: #388e3c;
    border-radius: 16px;
    padding: 4px 12px;
    margin: 2px 6px 2px 0;
    font-size: 13px;
    font-weight: 500;
    position: relative;
}
.badge .remove-student, .selected-student-tag .remove-student {
    background: transparent;
    border: none;
    color: #c62828;
    font-size: 15px;
    margin-left: 7px;
    cursor: pointer;
    position: absolute;
    right: 3px;
    top: 2px;
}
.btn-add {
    background: #4CAF50;
    color: #fff;
    border: none;
    border-radius: 4px;
    padding: 7px 16px;
    font-size: 15px;
    cursor: pointer;
    margin-top: 8px;
    transition: background 0.2s;
}
.btn-add:hover {
    background: #388e3c;
}
.btn-select-students {
    background: #fff;
    color: #4CAF50;
    border: 1px solid #4CAF50;
    border-radius: 4px;
    padding: 5px 10px;
    font-size: 14px;
    cursor: pointer;
    margin-left: 7px;
    transition: background 0.2s, color 0.2s;
}
.btn-select-students:hover {
    background: #4CAF50;
    color: #fff;
}
.form-actions {
    margin-top: 24px;
    display: flex;
    gap: 16px;
}
.btn-submit {
    background: #4CAF50;
    color: #fff;
    border: none;
    border-radius: 4px;
    padding: 10px 24px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.2s;
}
.btn-submit:hover {
    background: #388e3c;
}
.btn-cancel {
    background: #eee;
    color: #333;
    border: none;
    border-radius: 4px;
    padding: 10px 24px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.2s;
    text-decoration: none;
}
.btn-cancel:hover {
    background: #ddd;
}
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0,0,0,0.4);
    justify-content: center;
    align-items: center;
    display: flex;
}
.modal-content {
    background: #fff;
    margin: auto;
    padding: 24px 32px;
    border-radius: 8px;
    max-width: 480px;
    width: 100%;
    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: stretch;
}
.close-modal {
    background: transparent;
    border: none;
    font-size: 24px;
    color: #888;
    cursor: pointer;
    position: absolute;
    right: 16px;
    top: 16px;
}
@media (max-width: 768px) {
    .page-container {
        padding: 16px;
    }
    .form-container {
        padding: 12px;
    }
    .modal-content {
        padding: 12px 8px;
    }
}
</style>

<link rel="stylesheet" href="{{ asset('css/training/school/create.css') }}">
<link rel="stylesheet" href="{{ asset('css/training/student-selection.css') }}">
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
            <input type="number" step="0.1" id="passingGradeMin" name="passing_grade_min" value="{{ old('passing_grade_min') }}" required>
        </div>
        <div class="input-group">
            <label for="passingGradeMax">Passing Grade Max</label>
            <input type="number" step="0.1" id="passingGradeMax" name="passing_grade_max" value="{{ old('passing_grade_max') }}" required>
        </div>
        <div class="input-group">
            <label for="failingGradeMin">Failing Grade Min</label>
            <input type="number" step="0.1" id="failingGradeMin" name="failing_grade_min" value="{{ old('failing_grade_min') }}" required>
        </div>
        <div class="input-group">
            <label for="failingGradeMax">Failing Grade Max</label>
            <input type="number" step="0.1" id="failingGradeMax" name="failing_grade_max" value="{{ old('failing_grade_max') }}" required>
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
                    <div class="class-row card">
                        <div class="class-header">
                            <input type="text" name="classes[{{ $index }}][class_id]" placeholder="Class ID" value="{{ $class['class_id'] ?? '' }}" required>
                            <input type="text" name="classes[{{ $index }}][name]" placeholder="Class Name" value="{{ $class['name'] ?? '' }}" required>
                            <input type="hidden" name="classes[{{ $index }}][batch]" class="batch-input">
                            <button type="button" class="btn-select-students" data-class-index="{{ $index }}">Select Students</button>
                            <button type="button" class="btn-remove" onclick="removeClass(this)">×</button>
                        </div>
                        <div class="students-container" id="students-container-{{ $index }}">
                            <!-- Students will be loaded here via AJAX when batch is selected -->
                        </div>
                    </div>
                @endforeach
            </div>
            <button type="button" id="add-class" class="btn-add">Add Class</button>
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
            <div class="filter-section">
                <select id="batchFilter">
                    <option value="">All Batches</option>
                    @foreach($batches as $batch)
                        <option value="{{ $batch->batch }}">{{ $batch->batch }}</option>
                    @endforeach
                </select>
                <input type="text" id="studentSearch" class="search-input" placeholder="Search students...">
            </div>
            <div id="modalStudentsContainer" class="students-list">
                <!-- Students will be loaded here -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-submit" id="confirmStudentSelection">Confirm Selection</button>
            <button type="button" class="btn-cancel close-modal">Cancel</button>
        </div>
    </div>
</div>

<script>
console.log('Add school JS loaded');
document.addEventListener('DOMContentLoaded', function() {
    // --- Add Subject Logic ---
    const addSubjectButton = document.getElementById('add-subject');
    const subjectsContainer = document.getElementById('subjects-container');
    let subjectIndex = subjectsContainer ? subjectsContainer.children.length : 0;
    if(addSubjectButton && subjectsContainer) {
        addSubjectButton.addEventListener('click', function() {
            const subjectRow = document.createElement('div');
            subjectRow.className = 'subject-row';
            const fields = [
                { name: 'offer_code', placeholder: 'Offer Code' },
                { name: 'name', placeholder: 'Subject Name' },
                { name: 'instructor', placeholder: 'Instructor' },
                { name: 'schedule', placeholder: 'Schedule' }
            ];
            fields.forEach(field => {
                const input = document.createElement('input');
                input.type = 'text';
                input.name = `subjects[${subjectIndex}][${field.name}]`;
                input.placeholder = field.placeholder;
                input.required = true;
                subjectRow.appendChild(input);
            });
            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'btn-remove';
            removeButton.textContent = '×';
            removeButton.onclick = function() { removeSubject(this); };
            subjectRow.appendChild(removeButton);
            subjectsContainer.appendChild(subjectRow);
            subjectIndex++;
            console.log('[DEBUG] Subject added. New subjectIndex:', subjectIndex);
        });
    }
    window.removeSubject = function(button) {
        button.closest('.subject-row').remove();
        console.log('[DEBUG] Subject removed');
    };
    // --- Add Class Logic ---
    const addClassButton = document.getElementById('add-class');
    const classesContainer = document.getElementById('classes-container');
    let classIndex = classesContainer ? classesContainer.children.length : 0;
    if(addClassButton && classesContainer) {
        addClassButton.addEventListener('click', function() {
            const classRow = document.createElement('div');
            classRow.className = 'class-row card';
            classRow.innerHTML = `
                <div class=\"class-header\">
                    <input type=\"text\" name=\"classes[${classIndex}][class_id]\" placeholder=\"Class ID\" required>
                    <input type=\"text\" name=\"classes[${classIndex}][name]\" placeholder=\"Class Name\" required>
                    <input type=\"hidden\" name=\"classes[${classIndex}][batch]\" class=\"batch-input\">
                    <button type=\"button\" class=\"btn-select-students\" data-class-index=\"${classIndex}\">Select Students</button>
                    <button type=\"button\" class=\"btn-remove\" onclick=\"removeClass(this)\">×</button>
                </div>
                <div class=\"students-container\" id=\"students-container-${classIndex}\"></div>
            `;
            classesContainer.appendChild(classRow);
            classIndex++;
            console.log('[DEBUG] Class added. New classIndex:', classIndex);
        });
    }
    window.removeClass = function(button) {
        button.closest('.class-row').remove();
        console.log('[DEBUG] Class removed');
    };
    // --- Select Students Modal Logic ---
    const modal = document.getElementById('studentModal');
    const closeButtons = document.querySelectorAll('.close-modal');
    const confirmButton = document.getElementById('confirmStudentSelection');
    const batchFilter = document.getElementById('batchFilter');
    let currentClassIndex = null;
    if(classesContainer) {
        classesContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-select-students')) {
                const classIndex = e.target.getAttribute('data-class-index');
                console.log('[DEBUG] Select Students clicked. Class index:', classIndex);
                openStudentModal(classIndex);
            }
        });
    }
    function openStudentModal(classIndex) {
        currentClassIndex = classIndex;
        if (!modal) {
            alert('Modal not found!');
            return;
        }
        modal.style.display = 'block';
        loadStudentsByBatch(batchFilter.value);
    }
    if(closeButtons) {
        closeButtons.forEach(button => {
            button.addEventListener('click', () => {
                modal.style.display = 'none';
            });
        });
    }
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
    if(batchFilter) {
        batchFilter.addEventListener('change', function() {
            loadStudentsByBatch(this.value);
        });
    }
    if(confirmButton) {
        confirmButton.addEventListener('click', function() {
            const selectedStudents = Array.from(document.querySelectorAll('#modalStudentsContainer input[type="checkbox"]:checked'))
                .map(checkbox => ({
                    id: checkbox.value,
                    name: checkbox.getAttribute('data-name'),
                    student_id: checkbox.getAttribute('data-student-id')
                }));
            const selectedBatch = batchFilter.value;
            const batchInput = document.querySelector(`input[name=\"classes[${currentClassIndex}][batch]\"]`);
            if (batchInput) {
                batchInput.value = selectedBatch;
            }
            updateSelectedStudentsList(currentClassIndex, selectedStudents);
            modal.style.display = 'none';
            console.log('[DEBUG] Students selected for class', currentClassIndex, selectedStudents);
        });
    }
    function loadStudentsByBatch(batchId, searchTerm = '') {
        const url = `/training/students/by-batch${batchId ? '?batch_id=' + batchId : ''}`;
        fetch(url)
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.json();
            })
            .then(students => {
                const container = document.getElementById('modalStudentsContainer');
                let filteredStudents = students;
                
                // Filter by search term if provided
                if (searchTerm) {
                    const searchLower = searchTerm.toLowerCase();
                    filteredStudents = students.filter(student => {
                        const studentId = `${student.batch}${student.group}${student.student_number}${student.training_code}`;
                        const fullName = `${student.user_lname}, ${student.user_fname}`;
                        return studentId.toLowerCase().includes(searchLower) || 
                               fullName.toLowerCase().includes(searchLower);
                    });
                }

                container.innerHTML = filteredStudents.map(student => {
                    const studentId = `${student.batch}${student.group}${student.student_number}${student.training_code}`;
                    const fullName = `${student.user_lname}, ${student.user_fname}`;
                    return `
                        <div class="student-item">
                            <input type="checkbox" id="modal_student_${student.user_id}" value="${student.user_id}"
                                data-name="${fullName}" data-student-id="${studentId}">
                            <label for="modal_student_${student.user_id}">
                                <span class="student-id">${studentId}</span>
                                <span class="student-name">${fullName}</span>
                                <span class="batch-tag">Batch ${student.batch}</span>
                            </label>
                        </div>
                    `;
                }).join('');

                // Add click handlers for student items
                container.querySelectorAll('.student-item').forEach(item => {
                    item.addEventListener('click', function(e) {
                        if (e.target !== this.querySelector('input[type="checkbox"]')) {
                            const checkbox = this.querySelector('input[type="checkbox"]');
                            checkbox.checked = !checkbox.checked;
                        }
                    });
                });
            })
            .catch(error => {
                document.getElementById('modalStudentsContainer').innerHTML = `
                    <div class="error-message">
                        Error loading students: ${error.message}
                    </div>
                `;
                console.error('[DEBUG] Error loading students:', error);
            });
    }

    // Add search functionality
    const studentSearch = document.getElementById('studentSearch');
    if (studentSearch) {
        studentSearch.addEventListener('input', function() {
            loadStudentsByBatch(batchFilter.value, this.value);
        });
    }

    // Update the updateSelectedStudentsList function
    window.updateSelectedStudentsList = function(classIndex, students) {
        const container = document.getElementById(`students-container-${classIndex}`);
        if (!container) return;
        
        container.innerHTML = students.map(student => `
            <span class="selected-student-tag" data-student-id="${student.id}">
                ${student.student_id} - ${student.name}
                <input type="hidden" name="classes[${classIndex}][student_ids][]" value="${student.id}">
                <button type="button" class="remove-student">×</button>
            </span>
        `).join('');

        // Add click handlers for remove buttons
        container.querySelectorAll('.remove-student').forEach(btn => {
            btn.addEventListener('click', function() {
                this.parentElement.remove();
            });
        });
    };
});
</script>

@endsection 