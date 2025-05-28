@extends('layouts.nav')

@section('content')
<div class="page-container">
    <div class="header-section">
        <h2>Class Grades</h2>
    </div>
    <div class="filters">
        <label for="schoolSelect">School:</label>
        <select id="schoolSelect" class="form-control">
            <option value="">Select School</option>
        </select>
        <label for="classSelect">Class:</label>
        <select id="classSelect" class="form-control" disabled>
            <option value="">Select Class</option>
        </select>
        <label for="termSelect">Term/Semester/Year:</label>
        <select id="termSelect" class="form-control" disabled>
            <option value="">Select Term</option>
        </select>
    </div>
    <div id="gradesTableContainer" style="margin-top: 2rem;"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load schools on page load
    fetch('{{ route('training.analytics.schools') }}')
        .then(res => res.json())
        .then(data => {
            const schoolSelect = document.getElementById('schoolSelect');
            data.forEach(school => {
                const opt = document.createElement('option');
                opt.value = school.id;
                opt.textContent = school.name;
                schoolSelect.appendChild(opt);
            });
        });

    document.getElementById('schoolSelect').addEventListener('change', function() {
        const schoolId = this.value;
        const classSelect = document.getElementById('classSelect');
        const termSelect = document.getElementById('termSelect');
        classSelect.innerHTML = '<option value="">Select Class</option>';
        termSelect.innerHTML = '<option value="">Select Term</option>';
        classSelect.disabled = true;
        termSelect.disabled = true;
        if (schoolId) {
            fetch(`/training/analytics/classes/${schoolId}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(cls => {
                        const opt = document.createElement('option');
                        opt.value = cls.id;
                        opt.textContent = cls.name;
                        classSelect.appendChild(opt);
                    });
                    classSelect.disabled = false;
                });
            fetch(`/training/analytics/terms/${schoolId}`)
                .then(res => res.json())
                .then(data => {
                    data.forEach(term => {
                        const opt = document.createElement('option');
                        opt.value = term;
                        opt.textContent = term;
                        termSelect.appendChild(opt);
                    });
                    termSelect.disabled = false;
                });
        }
        document.getElementById('gradesTableContainer').innerHTML = '';
    });

    document.getElementById('classSelect').addEventListener('change', fetchGrades);
    document.getElementById('termSelect').addEventListener('change', fetchGrades);

    function fetchGrades() {
        const schoolId = document.getElementById('schoolSelect').value;
        const classId = document.getElementById('classSelect').value;
        const term = document.getElementById('termSelect').value;
        if (schoolId && classId && term) {
            fetch(`/training/analytics/class-grades-data?school_id=${schoolId}&class_id=${classId}&term=${term}`)
                .then(res => res.json())
                .then(data => {
                    renderGradesTable(data);
                });
        }
    }

    function renderGradesTable(data) {
        if (!data || !data.length) {
            document.getElementById('gradesTableContainer').innerHTML = '<div class="alert alert-info">No grades found for this selection.</div>';
            return;
        }
        let subjects = data[0].subjects;
        let table = `<table class="grades-table">
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Full Name</th>`;
        subjects.forEach(sub => {
            table += `<th>${sub}</th>`;
        });
        table += `<th>Average</th><th>Status</th></tr></thead><tbody>`;
        data.forEach(row => {
            table += `<tr><td>${row.student_id}</td><td>${row.full_name}</td>`;
            row.grades.forEach(g => {
                table += `<td>${g}</td>`;
            });
            table += `<td>${row.average}</td><td>${row.status}</td></tr>`;
        });
        table += '</tbody></table>';
        document.getElementById('gradesTableContainer').innerHTML = table;
    }
});
</script>

<style>
.grades-table {
    width: 100%;
    border-collapse: collapse;
}
.grades-table th, .grades-table td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: center;
}
.grades-table th {
    background: #f5f5f5;
}
</style>
@endsection
