@extends('layouts.nav')

@section('content')
<div class="page-container">

    <div class="school-details">
        <button onclick="window.history.back()" class="back-button">
        Back
    </button>
        <div class="school-header">
            <div class="header-left">
                <div class="school-title">
                    <h1>{{ $school->name }}</h1>
                    <div class="school-id">School ID: {{ $school->school_id }}</div>
                </div>
            </div>
            <div class="school-actions">
                <a href="{{ route('training.schools.edit', $school) }}" class="btn edit-btn">
                    <i class="fas fa-edit"></i> Edit School
                </a>
                <a href="{{ route('training.schools.classes.create', $school) }}" class="btn add-btn">
                    <i class="fas fa-plus"></i> Add Class
                </a>
            </div>
        </div>

        <div class="school-content">
            <div class="info-section">
                <div class="info-card">
                    <h2>School Information</h2>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="label">Department</span>
                            <span class="value">{{ $school->department }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Course</span>
                            <span class="value">{{ $school->course }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Number of Semesters</span>
                            <span class="value">{{ $school->num_semesters }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Passing Grade Range</span>
                            <span class="value">{{ $school->passing_grade_range }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Failing Grade Range</span>
                            <span class="value">{{ $school->failing_grade_range }}</span>
                        </div>
                    </div>
                </div>

                <div class="info-card">
                    <h2>Terms</h2>
                    <div class="terms-list">
                        @if($school->terms)
                            @php
                                $terms = is_string($school->terms) ? json_decode($school->terms) : $school->terms;
                            @endphp
                            @foreach($terms ?? [] as $term)
                                <span class="term-badge">{{ $term }}</span>
                            @endforeach
                        @else
                            <p class="empty-message">No terms defined</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="info-card">
                <div class="section-header">
                    <h2>Subjects</h2>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Offer Code</th>
                                <th>Subject Name</th>
                                <th>Instructor</th>
                                <th>Schedule</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $subjects = is_string($school->subjects) ? json_decode($school->subjects, true) : $school->subjects;
                            @endphp
                            @if(!empty($subjects))
                                @foreach($subjects as $subject)
                                    <tr>
                                        <td>{{ $subject['offer_code'] ?? 'N/A' }}</td>
                                        <td>{{ $subject['name'] ?? 'N/A' }}</td>
                                        <td>{{ $subject['instructor'] ?? 'N/A' }}</td>
                                        <td>{{ $subject['schedule'] ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="empty-message">No subjects defined</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="info-card">
                <div class="section-header">
                    <h2>Classes</h2>
                </div>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Class ID</th>
                                <th>Class Name</th>
                                <th>Total Students</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($school->classes as $class)
                                <tr>
                                    <td>{{ $class->class_id }}</td>
                                    <td>{{ $class->name }}</td>
                                    <td>{{ $class->students->count() }}</td>
                                    <td class="action-cell">
                                        <a href="{{ route('training.schools.classes.show', ['school' => $school, 'class' => $class]) }}" class="action-btn view-btn">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('training.schools.classes.edit', ['school' => $school, 'class' => $class]) }}" class="action-btn edit-btn">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('training.schools.classes.destroy', ['school' => $school, 'class' => $class]) }}" method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <!-- <button type="submit" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this class?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button> -->
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="empty-message">No classes found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.back-button {
    background-color: #ff9933;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
}

.page-container {
    padding: 32px;
    max-width: 1400px;
    margin: 0 auto;
    width: 100%;
}

.school-details {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 32px;
    width: 100%;
}

.school-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
}

.back-btn {
    padding: 8px 16px;
    background-color: #ff9933;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: background-color 0.3s;
}

.back-btn:hover {
    background-color:rgb(248, 133, 19);
}

.school-title {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.school-title h1 {
    margin: 0;
    font-size: 24px;
    font-weight: 600;
    color: #2d3748;
}

.school-id {
    color: #718096;
    font-size: 14px;
}

.school-actions {
    display: flex;
    gap: 12px;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s;
}

.edit-btn {
    background-color: #22bbea;
    color: white;
}

.edit-btn:hover {
    background-color: #3182ce;
}

.add-btn {
    background-color:rgb(45, 210, 33);
    color: white;
}

.add-btn:hover {
    background-color:rgb(0, 165, 38);
}

.school-content {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.info-section {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
}

.info-card {
    background: white;
    border-radius: 8px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.info-card h2 {
    margin: 0 0 16px 0;
    font-size: 18px;
    font-weight: 600;
    color: #2d3748;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.info-item .label {
    color: #718096;
    font-size: 14px;
}

.info-item .value {
    color: #2d3748;
    font-weight: 500;
}

.terms-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.term-badge {
    background: #e2e8f0;
    color: #4a5568;
    padding: 4px 12px;
    border-radius: 16px;
    font-size: 14px;
}

.section-header {
    margin-bottom: 16px;
}

.section-header h2 {
    margin: 0;
    color: #333;
    font-size: 20px;
    border-bottom: 2px solid #e0e0e0;
    padding-bottom: 8px;
}

.table-container {
    overflow-x: auto;
    margin: 0 -24px;
    padding: 0 24px;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.data-table th {
    background: #4CAF50;
    text-align: left;
    padding: 12px;
    font-weight: 600;
    color:rgb(255, 255, 255);
    border-bottom: 2px solid #e2e8f0;
}

.data-table td {
    padding: 12px;
    border-bottom: 1px solid #e2e8f0;
    color: #2d3748;
}

.action-cell {
    display: flex;
    gap: 8px;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 13px;
    text-decoration: none;
    transition: all 0.3s;
}

.view-btn {
    background-color: #ff9933;
    color: white;
}

.view-btn:hover {
    background-color:rgb(253, 140, 27);
}

.delete-btn {
    background-color: #e53e3e;
    color: white;
    border: none;
    cursor: pointer;
}

.delete-btn:hover {
    background-color: #c53030;
}

.delete-form {
    display: inline;
}

@media (max-width: 1024px) {
    .info-section {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .page-container {
        padding: 16px;
    }

    .school-details {
        padding: 24px;
    }

    .school-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .school-actions {
        width: 100%;
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .action-cell {
        flex-wrap: wrap;
    }
}

.empty-message {
    text-align: center;
    color: #718096;
    padding: 16px;
    font-style: italic;
}
</style>
@endsection 