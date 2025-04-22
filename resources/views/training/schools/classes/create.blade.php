@extends('layouts.nav')

@section('content')
<div class="page-container">
    <h2 class="page-title">Create New Class</h2>
    
    <form action="{{ route('training.schools.classes.store', ['school' => $school]) }}" method="POST" class="full-width-form">
        @csrf
        <div class="input-container">
            <label for="class_id">Class ID</label>
            <input type="text" id="class_id" name="class_id" required>
            @error('class_id')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="input-container">
            <label for="name">Class Name</label>
            <input type="text" id="name" name="name" required>
            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div class="input-container">
            <label>Select Students</label>
            <div class="filter-container">
                <input type="text" class="search-input" placeholder="Search students...">
                <select class="batch-select">
                    <option value="">All Batches</option>
                    @foreach($batches as $batch)
                        <option value="{{ $batch }}">Batch {{ $batch }}</option>
                    @endforeach
                </select>
            </div>
            <div class="students-container">
                @forelse($students as $student)
                    <div class="student-row" data-batch="{{ $student->batch }}">
                        <div class="student-info">
                            <input type="checkbox" name="students[]" value="{{ $student->user_id }}" id="student-{{ $student->user_id }}">
                            <label for="student-{{ $student->user_id }}">{{ $student->user_fname }} {{ $student->user_lname }}</label>
                        </div>
                        <span class="batch-label">Batch {{ $student->batch }}</span>
                    </div>
                @empty
                    <div class="no-data">No students available</div>
                @endforelse
            </div>
        </div>

        <div class="button-container">
            <button type="submit" class="submit-btn">Create Class</button>
            <button type="button" onclick="window.history.back()" class="cancel-btn">Cancel</button>
        </div>
    </form>
</div>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.page-container {
    width: 80vw;
    min-height: 100vh;
    background: #fff;
    padding: 0;
    margin: 0;
    overflow-x: hidden;
}

.page-title {
    font-size: 24px;
    padding: 20px;
    border-bottom: 1px solid #eee;
    width: 100%;
    background: #fff;
    margin: 0;
}

.full-width-form {
    width: 100%;
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.input-container {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.input-container label {
    font-weight: 500;
    color: #333;
}

.input-container input,
.input-container select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.filter-container {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 16px;
    width: 100%;
}

.students-container {
    width: 100%;
    border: 1px solid #eee;
    border-radius: 4px;
    max-height: 400px;
    overflow-y: auto;
}

.student-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 16px;
    border-bottom: 1px solid #eee;
    width: 100%;
    gap: 20px;
}

.student-info {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
    min-width: 0;
}

.student-info input[type="checkbox"] {
    width: 16px;
    height: 16px;
    margin: 0;
    flex-shrink: 0;
}

.student-info label {
    font-size: 14px;
    color: #333;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin: 0;
    cursor: pointer;
}

.batch-label {
    background: #f5f5f5;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 13px;
    color: #666;
    white-space: nowrap;
    flex-shrink: 0;
}

.button-container {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    width: 100%;
    margin-top: 20px;
}

.submit-btn, .cancel-btn {
    padding: 10px 24px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.2s;
}

.submit-btn {
    background: #22bbea;
    color: white;
}

.submit-btn:hover {
    background: #1da7d4;
}

.cancel-btn {
    background: #ff9933;
    color: white;
}

.cancel-btn:hover {
    background:rgb(253, 135, 17);
}

.error-message {
    color: #dc3545;
    font-size: 13px;
}

.no-data {
    padding: 16px;
    text-align: center;
    color: #666;
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

@media (max-width: 768px) {
    .filter-container {
        grid-template-columns: 1fr;
    }
    
    .full-width-form {
        padding: 16px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('.search-input');
    const batchSelect = document.querySelector('.batch-select');
    const studentRows = document.querySelectorAll('.student-row');

    function filterStudents() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedBatch = batchSelect.value;

        studentRows.forEach(row => {
            const studentLabel = row.querySelector('label').textContent.toLowerCase();
            const studentBatch = row.dataset.batch.toString();
            const matchesSearch = studentLabel.includes(searchTerm);
            const matchesBatch = !selectedBatch || studentBatch === selectedBatch;
            
            row.style.display = matchesSearch && matchesBatch ? 'flex' : 'none';
        });

        // Add visual feedback for no results
        const hasVisibleRows = Array.from(studentRows).some(row => row.style.display === 'flex');
        let noResultsMessage = document.querySelector('.no-results-message');
        
        if (!hasVisibleRows) {
            if (!noResultsMessage) {
                noResultsMessage = document.createElement('div');
                noResultsMessage.className = 'no-results-message';
                noResultsMessage.style.padding = '16px';
                noResultsMessage.style.textAlign = 'center';
                noResultsMessage.style.color = '#666';
                const studentsContainer = document.querySelector('.students-container');
                studentsContainer.appendChild(noResultsMessage);
            }
            noResultsMessage.textContent = `No students found${selectedBatch ? ` in Batch ${selectedBatch}` : ''} ${searchTerm ? ` matching "${searchTerm}"` : ''}`;
            noResultsMessage.style.display = 'block';
        } else if (noResultsMessage) {
            noResultsMessage.style.display = 'none';
        }
    }

    // Add debug logging
    batchSelect.addEventListener('change', function(e) {
        console.log('Selected batch:', e.target.value);
        filterStudents();
    });

    searchInput.addEventListener('input', filterStudents);

    // Initial filter to handle any preselected values
    filterStudents();
});
</script>
@endsection 