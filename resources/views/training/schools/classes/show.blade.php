@extends('layouts.nav')

@section('content')
<div class="page-container">
    <div class="header-section">
        <div class="header-left">
            <button onclick="window.history.back()" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Back
            </button>
            <h2>Class Details - {{ $class->name }}</h2>
        </div>
    </div>

    <div class="details-section">
        <div class="detail-row">
            <div class="detail-label">Class ID:</div>
            <div class="detail-value">{{ $class->class_id }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Class Name:</div>
            <div class="detail-value">{{ $class->name }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">School:</div>
            <div class="detail-value">{{ $school->name }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Total Students:</div>
            <div class="detail-value">{{ $class->students->count() }}</div>
        </div>
    </div>


    <h3>Students in this Class</h3>
    
    <div class="content-wrapper">
        <div class="table-container">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th width="20%">Student ID</th>
                        <th width="60%">Name</th>
                        <th width="20%">Batch</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($class->students as $student)
                        <tr>
                            <td>{{ $student->user_id }}</td>
                            <td>{{ $student->user_fname }} {{ $student->user_lname }}</td>
                            <td>{{ $student->studentDetail->batch ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="empty-message">No students in this class</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.page-container {
    padding: 20px 40px 20px 20px;
    width: 100%;
    box-sizing: border-box;
}

.header-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.header-section h2 {
    font-size: 24px;
    color: #333;
    margin: 0;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 20px;
}

.back-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: #ff9933;
    color: white;
    border-radius: 4px;
    border: none;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.back-button:hover {
    background:rgb(253, 141, 28);
}

.details-section {
    background: white;
    border-radius: 4px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.detail-row {
    display: flex;
    margin-bottom: 16px;
    border-bottom: 1px solid #eee;
    padding-bottom: 16px;
}

.detail-row:last-child {
    margin-bottom: 0;
    border-bottom: none;
    padding-bottom: 0;
}

.detail-label {
    width: 150px;
    font-weight: bold;
    color: #555;
}

.detail-value {
    flex: 1;
    color: #333;
}

.action-section {
    margin-bottom: 20px;
}

.create-btn {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    background: #4CAF50;
    color: white;
    border-radius: 4px;
    text-decoration: none;
    font-size: 14px;
}

h3 {
    margin: 0 0 20px 0;
    color: #333;
    font-size: 20px;
}

.content-wrapper {
    background: white;
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    overflow: hidden;
    width: 100%;
}

.table-container {
    width: 100%;
}

.custom-table {
    width: 100%;
    table-layout: fixed;
    border-collapse: collapse;
}

.custom-table th {
    background: #4CAF50;
    color: white;
    padding: 16px 24px;
    text-align: left;
    font-weight: normal;
    font-size: 14px;
    white-space: nowrap;
}

.custom-table td {
    padding: 16px 24px;
    border-bottom: 1px solid #eee;
    background: #f8f9fa;
    font-size: 14px;
}

.custom-table tr:hover td {
    background: #f1f1f1;
}

.empty-message {
    text-align: center;
    color: #666;
}

@media (max-width: 768px) {
    .page-container {
        padding: 16px;
    }
    
    .header-section {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .detail-row {
        flex-direction: column;
        gap: 8px;
    }
    
    .detail-label {
        width: 100%;
    }

    .table-container {
        overflow-x: auto;
    }

    .custom-table {
        min-width: 800px;
    }
}

.fixed-back-btn {
    position: fixed;
    top: 100px;
    right: 40px;
    z-index: 1000;
}
</style>
@endsection 