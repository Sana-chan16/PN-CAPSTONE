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
            <input type="number" id="semester_count" name="semester_count" value="{{ old('semester_count', $school->semester_count) }}" required>
            @error('semester_count')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Grade Range Configuration</label>
            <div class="grade-range-selector">
                <div class="radio-group">
                    <input type="radio" id="range1" name="grade_range" value="1" 
                        {{ $school->passing_grade_min == 1.0 ? 'checked' : '' }}>
                    <label for="range1">1.0 - 3.0 (Passing)</label>
                </div>
                <div class="radio-group">
                    <input type="radio" id="range2" name="grade_range" value="2"
                        {{ $school->passing_grade_min == 3.0 ? 'checked' : '' }}>
                    <label for="range2">3.0 - 5.0 (Passing)</label>
                </div>
            </div>
            <div class="grade-info">
                <div id="passingRange">Passing Grade Range: <span>{{ number_format($school->passing_grade_min, 1) }} - {{ number_format($school->passing_grade_max, 1) }}</span></div>
                <div id="failingRange">Failing Grade Range: <span>
                    @if($school->passing_grade_min == 1.0)
                        {{ number_format($school->passing_grade_max + 0.1, 1) }} - 5.0
                    @else
                        1.0 - {{ number_format($school->passing_grade_min - 0.1, 1) }}
                    @endif
                </span></div>
            </div>
            <input type="hidden" name="passing_grade_min" id="passingGradeMin" value="{{ $school->passing_grade_min }}">
            <input type="hidden" name="passing_grade_max" id="passingGradeMax" value="{{ $school->passing_grade_max }}">
        </div>

        <div class="form-group">
            <label>Terms</label>
            <div class="checkbox-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="terms[]" value="prelim" {{ in_array('prelim', old('terms', $school->terms ?? [])) ? 'checked' : '' }}>
                    Prelim
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="terms[]" value="midterm" {{ in_array('midterm', old('terms', $school->terms ?? [])) ? 'checked' : '' }}>
                    Midterm
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="terms[]" value="semi_final" {{ in_array('semi_final', old('terms', $school->terms ?? [])) ? 'checked' : '' }}>
                    Semi Final
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="terms[]" value="final" {{ in_array('final', old('terms', $school->terms ?? [])) ? 'checked' : '' }}>
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
                @foreach(old('subjects', $school->subjects ?? []) as $index => $subject)
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
                <!-- Classes will be added dynamically -->
            </div>
            <button type="button" id="add-class" class="btn-add">Add Class</button>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">Update School</button>
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
document.addEventListener('DOMContentLoaded', function() {
    let currentClassIndex = null;
    const modal = document.getElementById('studentModal');
    const closeButtons = document.querySelectorAll('.close-modal');
    const confirmButton = document.getElementById('confirmStudentSelection');
    const batchFilter = document.getElementById('batchFilter');

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

    // Grade range configuration
    const rangeInputs = document.querySelectorAll('input[name="grade_range"]');
    const passingRangeSpan = document.querySelector('#passingRange span');
    const failingRangeSpan = document.querySelector('#failingRange span');
    const passingGradeMin = document.getElementById('passingGradeMin');
    const passingGradeMax = document.getElementById('passingGradeMax');

    function updateGradeRanges(value) {
        if (value === '1') {
            passingRangeSpan.textContent = '1.0 - 3.0';
            failingRangeSpan.textContent = '3.1 - 5.0';
            passingGradeMin.value = '1.0';
            passingGradeMax.value = '3.0';
        } else {
            passingRangeSpan.textContent = '3.0 - 5.0';
            failingRangeSpan.textContent = '1.0 - 2.9';
            passingGradeMin.value = '3.0';
            passingGradeMax.value = '5.0';
        }
    }

    rangeInputs.forEach(input => {
        input.addEventListener('change', (e) => {
            updateGradeRanges(e.target.value);
        });
    });

    // Subject management
    let subjectCount = {{ count(old('subjects', $school->subjects ?? [])) }};
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

    // Class management
    let classCount = 0;
    document.getElementById('add-class').addEventListener('click', function() {
        const container = document.getElementById('classes-container');
        const row = document.createElement('div');
        row.className = 'class-row';
        row.innerHTML = `
            <div class="class-header">
                <input type="text" name="classes[${classCount}][class_id]" placeholder="Class ID" required>
                <input type="text" name="classes[${classCount}][name]" placeholder="Class Name" required>
                <input type="hidden" name="classes[${classCount}][batch]" class="batch-input">
                <button type="button" class="btn-select-students" data-class-index="${classCount}">Select Students</button>
                <button type="button" class="btn-remove" onclick="removeClass(this)">×</button>
            </div>
            <div class="students-container" id="students-container-${classCount}">
            </div>
        `;
        container.appendChild(row);
        attachClassEventListeners(row);
        classCount++;
    });

    function attachClassEventListeners(classRow) {
        const selectStudentsBtn = classRow.querySelector('.btn-select-students');
        selectStudentsBtn.addEventListener('click', function() {
            currentClassIndex = this.dataset.classIndex;
            modal.style.display = 'block';
            loadStudents();
        });
    }

    function loadStudents() {
        const selectedBatch = batchFilter.value;
        const container = document.getElementById('modalStudentsContainer');
        const students = @json($students);
        
        container.innerHTML = students
            .filter(student => !selectedBatch || student.batch === selectedBatch)
            .map(student => `
                <div class="student-checkbox">
                    <input type="checkbox" id="modal_student_${student.user_id}" value="${student.user_id}">
                    <label for="modal_student_${student.user_id}">
                        ${student.user_id} - ${student.user_fname} ${student.user_mInitial}. ${student.user_lname}
                        <span class="batch-tag">${student.batch}</span>
                    </label>
                </div>
            `).join('');
    }

    batchFilter.addEventListener('change', loadStudents);

    confirmButton.addEventListener('click', function() {
        const selectedStudents = Array.from(document.querySelectorAll('#modalStudentsContainer input[type="checkbox"]:checked'))
            .map(checkbox => ({
                id: checkbox.value,
                name: checkbox.nextElementSibling.textContent.trim()
            }));

        const container = document.getElementById(`students-container-${currentClassIndex}`);
        container.innerHTML = selectedStudents.map(student => `
            <div class="selected-student">
                <input type="hidden" name="classes[${currentClassIndex}][student_ids][]" value="${student.id}">
                <span>${student.name}</span>
                <button type="button" class="btn-remove-student" onclick="removeStudent(this)">×</button>
            </div>
        `).join('');

        modal.style.display = 'none';
    });
});

function removeSubject(button) {
    const row = button.parentElement;
    row.remove();
    updateSubjectIndices();
}

function removeClass(button) {
    const row = button.closest('.class-row');
    row.remove();
    updateClassIndices();
}

function removeStudent(button) {
    const studentElement = button.parentElement;
    studentElement.remove();
}

function updateSubjectIndices() {
    const rows = document.querySelectorAll('.subject-row');
    rows.forEach((row, index) => {
        const inputs = row.querySelectorAll('input');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace(/\d+/, index));
            }
        });
    });
}

function updateClassIndices() {
    const rows = document.querySelectorAll('.class-row');
    rows.forEach((row, index) => {
        const inputs = row.querySelectorAll('input');
        const button = row.querySelector('.btn-select-students');
        const container = row.querySelector('.students-container');
        
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                input.setAttribute('name', name.replace(/\d+/, index));
            }
        });

        if (button) {
            button.dataset.classIndex = index;
        }

        if (container) {
            container.id = `students-container-${index}`;
        }
    });
}
</script>

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

.grade-range-selector {
    display: flex;
    gap: 20px;
    margin-bottom: 12px;
}

.radio-group {
    display: flex;
    align-items: center;
    gap: 8px;
}

.grade-info {
    margin-top: 12px;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 4px;
}

.grade-info div {
    margin-bottom: 8px;
    font-size: 14px;
}

.grade-info div:last-child {
    margin-bottom: 0;
}

.grade-info span {
    font-weight: 500;
    font-family: monospace;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
}

.subjects-section {
    margin-top: 24px;
    padding: 16px;
    background: #f8f9fa;
    border-radius: 4px;
}

.subjects-section h3 {
    margin: 0 0 16px 0;
    color: #333;
    font-size: 18px;
}

.subject-row {
    display: flex;
    gap: 12px;
    margin-bottom: 12px;
    align-items: center;
}

.subject-row input {
    flex: 1;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.btn-remove {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #dc3545;
    color: white;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.btn-add {
    background: #28a745;
    color: white;
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
}

.form-actions {
    display: flex;
    gap: 12px;
    margin-top: 24px;
}

.btn-submit {
    background: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
}

.btn-submit:hover {
    background: #388e3c;
    color: white;
    text-decoration: none;
    text-align: center;
}

.btn-cancel {
    background: #ff9933;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    text-decoration: none;
    text-align: center;
}

.btn-cancel:hover {
    background:rgb(255, 128, 0);
    color: #000;
    text-decoration: none;
    text-align: center;
}

.error-message {
    color: #dc3545;
    font-size: 12px;
    margin-top: 4px;
}

.alert {
    padding: 12px 16px;
    margin-bottom: 16px;
    border-radius: 4px;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.alert-error {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}

.classes-section {
    margin-top: 2rem;
}

.class-row {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    margin-bottom: 1rem;
    padding: 1rem;
}

.class-header {
    display: flex;
    gap: 1rem;
    align-items: center;
    margin-bottom: 1rem;
}

.class-header input[type="text"] {
    flex: 1;
    padding: 0.5rem;
    border: 1px solid #ced4da;
    border-radius: 4px;
}

.btn-select-students {
    background: #22bbea;
    color: white;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.students-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 0.5rem;
}

.selected-student {
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 0.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.btn-remove-student {
    background: none;
    border: none;
    color: #dc3545;
    cursor: pointer;
    font-size: 1.2rem;
    padding: 0 0.5rem;
}

.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
}

.modal-content {
    background: white;
    margin: 10% auto;
    padding: 1.5rem;
    border-radius: 8px;
    width: 80%;
    max-width: 800px;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.close-modal {
    background: #ff9933;
    border: none;
    font-size: 15px;
    cursor: pointer;
}

.filter-section {
    margin-bottom: 1rem;
}

.students-list {
    max-height: 400px;
    overflow-y: auto;
    margin-bottom: 1rem;
}

.student-checkbox {
    display: flex;
    align-items: center;
    padding: 0.5rem;
    border-bottom: 1px solid #dee2e6;
}

.batch-tag {
    background: #e9ecef;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    margin-left: 0.5rem;
    font-size: 0.875rem;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 1rem;
}

@media (max-width: 768px) {
    .page-container {
        padding: 16px;
    }
    
    .subject-row {
        flex-direction: column;
        gap: 8px;
    }
    
    .checkbox-group {
        flex-direction: column;
        gap: 8px;
    }
}
</style>
@endsection 