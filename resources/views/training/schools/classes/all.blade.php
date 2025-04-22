@extends('layouts.nav')

@section('content')
<div class="page-container">
    <div class="header-section">
        <h2>All Classes</h2>
    </div>

    <div class="content-wrapper">
        <div class="table-container">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th width="10%">Class ID</th>
                        <th width="15%">Name</th>
                        <th width="45%">School</th>
                        <th width="10%">Students</th>
                        <th width="20%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classes as $class)
                        <tr>
                            <td>{{ $class->class_id }}</td>
                            <td>{{ $class->name }}</td>
                            <td>{{ $class->school->name }}</td>
                            <td class="text-center">{{ $class->students->count() }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('training.schools.classes.show', ['school' => $class->school->school_id, 'class' => $class->class_id]) }}" 
                                       class="action-btn view">
                                        view
                                    </a>
                                    <a href="{{ route('training.schools.classes.edit', ['school' => $class->school->school_id, 'class' => $class->class_id]) }}" 
                                       class="action-btn edit">
                                        edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-message">No classes found</td>
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
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
}

.header-section h2 {
    font-size: 30px;
    color: #333;
    margin: 0;
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
    overflow-x: auto;
}

.custom-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

.custom-table th {
    background: #4CAF50;
    color: white;
    padding: 16px;
    text-align: left;
    font-weight: 500;
    font-size: 16px;
    white-space: nowrap;
}

.custom-table td {
    padding: 16px;
    border-bottom: 1px solid #eee;
    font-size: 16px;
    background: white;
}

.custom-table tr:hover td {
    background: #f8f9fa;
}

.text-center {
    text-align: center;
}

.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: flex-start;
}

.action-btn {
    min-width: 80px;
    padding: 8px 16px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 14px;
    text-align: center;
    text-transform: capitalize;
    transition: all 0.3s ease;
}

.view {
    background: #17a2b8;
    color: white;
}

.view:hover {
    background: #138496;
}

.edit {
    background: #28a745;
    color: white;
}

.edit:hover {
    background: #218838;
}

.empty-message {
    text-align: center;
    color: #666;
    padding: 24px !important;
}

@media (max-width: 768px) {
    .page-container {
        padding: 16px;
    }
    
    .custom-table {
        min-width: 800px;
    }
}
</style>
@endsection 