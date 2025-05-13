@extends('layouts.nav')

@section('content')
<style>
body, .container, h1, h2, h3, h4, h5, h6, .table, .alert {
    font-family: 'Poppins', sans-serif !important;
}
.container {
    width: 100%;
    max-width: 1500px;
}
h1 {
    margin-top: 50px;
    color:rgb(0, 0, 0);
    font-size: 2rem;
    margin-bottom: 18px;
    font-weight: 700;
    letter-spacing: 0.5px;
}
.filter-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    width: 100%;
    max-width: 1400px;
    margin-left: auto;
    margin-right: auto;
}
.filter-form {
    display: flex;
    gap: 15px;
    align-items: center;
    flex-wrap: wrap;
}
.filter-form select {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    min-width: 180px;
    background: white;
}
.filter-form button {
    padding: 8px 20px;
    background: #22bbea;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
.filter-form button:hover {
    background: #1aa8d4;
}
.class-section {
    margin: 32px auto 40px auto;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow: hidden;
    width: 78vw;
    max-width: 1500px;
}
.class-header {
    background: #22bbea;
    color: white;
    padding: 15px 20px;
    border-radius: 8px 8px 0 0;
    margin-bottom: 0;
}
.class-header h2 {
    margin: 0;
    font-size: 1.2rem;
    font-weight: 600;
}
.table-header, .table-row {
    display: grid;
    align-items: center;
    width: 100%;
}
.header-cell {
    padding: 15px;
    font-size: 13px;
    font-weight: 500;
    text-transform: uppercase;
}
.table-row {
    display: grid;
    grid-template-columns: 100px 180px repeat(auto-fit, minmax(120px, 1fr)) 140px;
    border-bottom: 1px solid #eee;
    align-items: center;
    background: white;
    padding: 5px 0;
}
.table-row:last-child {
    border-bottom: none;
    border-radius: 0 0 8px 8px;
}
.cell {
    padding: 10px 15px;
    font-size: 13px;
}
.grade-display {
    font-weight: 600;
    color: #333;
    font-size: 1.1em;
}
.status-badge {
    padding: 4px 14px;
    border-radius: 12px;
    font-size: 0.85em;
    font-weight: 600;
    display: inline-block;
    letter-spacing: 0.2px;
    text-align: center;
    width: fit-content;
}
.status-badge.pending {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffe082;
}
.status-badge.submitted {
    background: #d4edda;
    color: #218838;
    border: 1px solid #b7e4c7;
}
.status-badge.approved {
    background: #e3f3fa;
    color: #22bbea;
    border: 1px solid #22bbea;
}
.status-badge.rejected {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
.btn-sm {
    padding: 4px 10px;
    font-size: 0.95em;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    font-weight: 600;
}
.btn-info {
    background: #22bbea;
    color: #fff;
}
.btn-info:hover {
    background: #1aa8d4;
}
.alert {
    padding: 12px;
    border-radius: 4px;
    margin-bottom: 16px;
}
.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1.5px solid #b7e4c7;
}
.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1.5px solid #f5c6cb;
}
.modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0,0,0,0.5);
    align-items: center;
    justify-content: center;
}
.modal-content {
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    max-width: 600px;
    width: 90%;
    position: relative;
}
.modal-close {
    position: absolute;
    top: 10px;
    right: 20px;
    font-size: 2em;
    cursor: pointer;
}
.proof-viewer {
    text-align: center;
    margin-bottom: 20px;
}
.proof-viewer img {
    max-width: 100%;
    max-height: 400px;
}
.proof-viewer embed {
    width: 100%;
    height: 400px;
}
.modal-actions {
    display: flex;
    gap: 10px;
    justify-content: center;
    margin-top: 20px;
}
.btn-approve {
    background: #28a745;
    color: #fff;
}
.btn-approve:hover {
    background: #218838;
}
.btn-reject {
    background: #dc3545;
    color: #fff;
}
.btn-reject:hover {
    background: #b52a37;
}
.term-semester-info {
    margin-top: 8px;
    display: flex;
    gap: 10px;
}
.badge {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.85em;
    font-weight: 500;
}
.badge-info {
    background: #e3f3fa;
    color: #22bbea;
    border: 1px solid #22bbea;
}
.term-header {
    background: #f8f9fa;
    padding: 15px 20px;
    border-bottom: 1px solid #eee;
}
.term-header h3 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
}
.table-wrapper {
    margin-bottom: 20px;
}
.table-wrapper:last-child {
    margin-bottom: 0;
}
@media (max-width: 900px) {
    .container {
        padding: 8px;
    }
    .table-header, .table-row {
        grid-template-columns: 1fr 1.5fr repeat(auto-fit, 1fr) 1.5fr;
    }  
    .cell {
        padding: 8px;
        font-size: 12px;
    }
}
</style>

<h1>Monitor Grade Submissions</h1>
<hr>
@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

<!-- Filter Section -->
<div class="filter-section">
    <form action="{{ route('training.grade-submissions.monitor') }}" method="GET" class="filter-form">
        <select name="class_id" id="class_id">
            <option value="">All Classes</option>
            @foreach($classes as $class)
                <option value="{{ $class->class_id }}" {{ request('class_id') == $class->class_id ? 'selected' : '' }}>
                    {{ $class->class_name }}
                </option>
            @endforeach
        </select>
        <select name="period" id="period">
            <option value="">All Periods</option>
            @foreach($periods as $period)
                @php
                    $value = $period->semester . '_' . $period->term;
                    if (isset($period->academic_year)) {
                        $value .= '_' . $period->academic_year;
                    }
                    $label = $period->semester . ' - ' . ucfirst($period->term);
                    if (isset($period->academic_year)) {
                        $label .= ' (' . $period->academic_year . ')';
                    }
                @endphp
                <option value="{{ $value }}" {{ request('period') == $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        <button type="submit">Filter</button>
    </form>
</div>

@forelse($classSubmissions as $classId => $students)
    @php
        // Get the first actual record with all info
        $firstRecord = $students->flatten(2)->first(function($item) {
            return is_object($item) && isset($item->class_name) && isset($item->school_name);
        });
        $className = $firstRecord->class_name ?? 'Unknown Class';
        $schoolName = $firstRecord->school_name ?? 'Unknown School';
        $subjects = $classSubjects->get($classId, collect());
        $columnCount = $subjects->count();
        $gridTemplate = "1fr 2fr " . str_repeat("1fr ", $columnCount) . "1.2fr";
        // Group students by term if no specific term is selected
        $termGroups = collect();
        if (!request('term')) {
            $termGroups = $students->groupBy(function($studentGroup) {
                $first = $studentGroup->flatten(1)->first();
                return $first->term ?? 'Unknown';
            });
        } else {
            $termGroups = collect(['selected' => $students]);
        }
    @endphp
    <div class="class-section">
        <div class="class-header">
            <h2>{{ $className }} - {{ $schoolName }}</h2>
            <div class="term-semester-info">
                @if(request('semester'))
                    <span class="badge badge-info">{{ request('semester') }}st Semester</span>
                @endif
            </div>
        </div>

        @foreach($termGroups as $term => $termStudents)
            @if(!request('term'))
                <div class="term-header">
                    <h3>Term: {{ ucfirst($term) }}</h3>
                </div>
            @endif
            <div class="table-wrapper">
                <div class="table-header" style="grid-template-columns: {{ $gridTemplate }};">
                    <div class="header-cell">Student ID</div>
                    <div class="header-cell">Name</div>
                    @foreach($subjects as $subject)
                        <div class="header-cell">{{ $subject->name }}</div>
                    @endforeach
                    <div class="header-cell">Actions</div>
                </div>
                @forelse($termStudents as $studentKey => $records)
                    @php
                        $flatRecords = $records->flatten(1);
                        $firstStudent = $flatRecords->first();
                        $studentId = $firstStudent->user_id ?? '';
                        $studentName = ($firstStudent->user_fname ?? '') . ' ' . ($firstStudent->user_lname ?? '');
                        $hasProof = $flatRecords->first(function($item) {
                            return isset($item->proof_path) && $item->proof_path !== null && $item->status !== 'rejected';
                        });
                        $statuses = $flatRecords->pluck('status')->filter()->values();
                        if ($statuses->contains('rejected')) {
                            $aggStatus = 'rejected';
                        } elseif ($statuses->contains('pending')) {
                            $aggStatus = 'pending';
                        } elseif ($statuses->contains('submitted')) {
                            $aggStatus = 'submitted';
                        } elseif ($statuses->every(fn($s) => $s === 'approved')) {
                            $aggStatus = 'approved';
                        } else {
                            $aggStatus = $statuses->first() ?? 'pending';
                        }
                    @endphp
                    <div class="table-row" style="grid-template-columns: {{ $gridTemplate }};">
                        <div class="cell">{{ $studentId }}</div>
                        <div class="cell">{{ $studentName }}</div>
                        @foreach($subjects as $subject)
                            @php
                                $rec = $flatRecords->first(function($item) use ($subject) {
                                    return isset($item->subject_id) && $item->subject_id == $subject->id;
                                });
                                $grade = '';
                                if ($rec) {
                                    if ($rec->grade === null || $rec->grade === '') {
                                        $grade = '';
                                    } else {
                                        if (in_array(strtoupper($rec->grade), ['INC', 'NC', 'DR'])) {
                                            $grade = strtoupper($rec->grade);
                                        } else {
                                            $grade = number_format((float)$rec->grade, 2);
                                        }
                                    }
                                }
                                $status = $rec ? $rec->status : 'pending';
                            @endphp
                            <div class="cell">
                                <span class="grade-display">{{ $grade }}</span>
                                @if($status === 'pending')
                                    <span class="status-badge pending">Pending</span>
                                @elseif($status === 'approved')
                                    <span class="status-badge approved">Approved</span>
                                @elseif($status === 'rejected')
                                    <span class="status-badge rejected">Rejected</span>
                                @endif
                            </div>
                        @endforeach
                        <div class="cell">
                            @if($aggStatus === 'approved')
                                <span class="status-badge approved">Approved</span>
                            @elseif($aggStatus === 'rejected')
                                <span class="status-badge rejected">Rejected</span>
                            @elseif(($aggStatus === 'pending' || $aggStatus === 'submitted') && $hasProof)
                                <button type="button" class="btn-sm btn-info view-proof-btn" 
                                    data-proof="{{ asset('storage/' . $hasProof->proof_path) }}" 
                                    data-submission-id="{{ $hasProof->id }}"
                                    data-subject="All Grades">
                                    View Proof
                                </button>
                            @elseif($aggStatus === 'submitted')
                                <span class="status-badge submitted">Submitted</span>
                            @else
                                <span class="status-badge pending">Pending</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="table-row">
                        <div class="cell" style="grid-column: 1/-1; text-align: center; color: #888;">No submissions found for this term.</div>
                    </div>
                @endforelse
            </div>
        @endforeach
    </div>
@empty
    <div class="alert alert-info">No grade submissions found.</div>
@endforelse

<!-- Modal for Proof and Approve/Reject -->
<div id="proofModal" class="modal">
    <div class="modal-content">
        <span class="modal-close">&times;</span>
        <h3 id="modalSubject" style="margin-bottom: 20px;"></h3>
        <div id="proofViewer" class="proof-viewer"></div>
        <form id="approveRejectForm" method="POST" action="{{ route('training.grade-submissions.update-status') }}">
            @csrf
            <input type="hidden" name="submission_id" id="modalSubmissionId">
            <input type="hidden" name="action" id="modalAction">
            <div class="modal-actions">
                <button type="button" class="btn-sm btn-approve" onclick="submitAction('approve')">Approve</button>
                <button type="button" class="btn-sm btn-reject" onclick="submitAction('reject')">Reject</button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
document.querySelectorAll('.view-proof-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const proofUrl = this.getAttribute('data-proof');
        const submissionId = this.getAttribute('data-submission-id');
        const subject = this.getAttribute('data-subject');
        
        document.getElementById('modalSubmissionId').value = submissionId;
        document.getElementById('modalSubject').textContent = subject;
        
        let viewer = document.getElementById('proofViewer');
        if (proofUrl.match(/\.(jpg|jpeg|png|gif)$/i)) {
            viewer.innerHTML = `<img src="${proofUrl}" alt="Proof" style="max-width: 100%; height: auto;">`;
        } else if (proofUrl.match(/\.pdf$/i)) {
            viewer.innerHTML = `<embed src="${proofUrl}" type="application/pdf" width="100%" height="500px">`;
        } else {
            viewer.innerHTML = `<a href="${proofUrl}" target="_blank" class="btn btn-info">View Proof</a>`;
        }
        
        document.getElementById('proofModal').style.display = 'flex';
    });
});

document.querySelector('.modal-close').onclick = function() {
    document.getElementById('proofModal').style.display = 'none';
};

function submitAction(action) {
    if (confirm(`Are you sure you want to ${action} this submission?`)) {
        document.getElementById('modalAction').value = action;
        document.getElementById('approveRejectForm').submit();
    }
}

window.onclick = function(event) {
    if (event.target == document.getElementById('proofModal')) {
        document.getElementById('proofModal').style.display = 'none';
    }
};
</script>
@endsection

</div>
@endsection 