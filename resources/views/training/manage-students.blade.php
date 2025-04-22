@extends('layouts.nav')

@section('content')
    <div class="header-container">
        <h1>Manage Schools</h1>
        <a href="{{ route('manage.schools.create') }}" class="add-school-btn">Add School</a>
    </div>
    <section class="manage-schools">
        <div class="table-responsive">
            <table class="schools-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name of School</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schools as $school)
                        <tr>
                            <td>{{ $school->id }}</td>
                            <td>{{ $school->school_name }}</td>
                            <td class="actions">
                                <a href="{{ route('manage.schools.view', $school->id) }}" class="view-btn">View</a>
                                <a href="{{ route('manage.schools.edit', $school->id) }}" class="edit-btn">Edit</a>
                                <form action="{{ route('manage.schools.destroy', $school->id) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this school?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <style>
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 0 20px;
            position: relative;
        }

        .add-school-btn {
            background-color: #ff9933;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .add-school-btn:hover {
            background-color: #e68a00;
            transform: translateY(-2px);
        }

        .manage-schools {
            padding: 20px;
        }

        /* .table-responsive {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
        } */

        h1 {
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
            flex: 1;
        }

        .schools-table {
            width: 90%;
            border-collapse: collapse;
            margin: 0 auto;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .schools-table th,
        .schools-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        .schools-table th {
            background-color: #4CAF50;
            color: white;
            font-weight: 500;
        }

        .schools-table th:nth-child(3),
        .schools-table td:nth-child(3) {
            text-align: center;
        }

        .schools-table tr:hover {
            background-color: #f5f5f5;
        }

        .actions {
            display: flex;
            gap: 5px;
            justify-content: center;
        }

        .view-btn,
        .edit-btn,
        .delete-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .view-btn {
            background-color: #4CAF50;
            color: white;
        }

        .view-btn:hover {
            background-color: #45a049;
        }

        .edit-btn {
            background-color: #22bbea;
            color: white;
        }

        .edit-btn:hover {
            background-color: #1a9bc8;
        }

        .delete-btn {
            background-color: #ff9933;
            color: white;
        }

        .delete-btn:hover {
            background-color: #e68a00;
        }

        .delete-form {
            display: inline;
            margin: 0;
        }
    </style>
@endsection

@extends('layouts.nav')

@section('content')
<div class="page-container">
    <div class="header-section">
        <h2>Schools</h2>
        <a href="{{ route('training.schools.create') }}" class="add-button">Add New School</a>
    </div>

    <div class="content-wrapper">
        <div class="table-container">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th width="15%">ID</th>
                        <th width="55%">School</th>
                        <th width="30%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $schools = $schools ?? collect([]);
                    @endphp
                    
                    @if($schools->isNotEmpty())
                        @foreach($schools as $school)
                            <tr>
                                <td>{{ $school->school_id ?? 'N/A' }}</td>
                                <td>{{ $school->name ?? 'N/A' }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('training.schools.show', $school) }}" class="action-btn view">
                                            <i class="fas fa-eye"></i>
                                            view
                                        </a>
                                        <a href="{{ route('training.schools.edit', $school) }}" class="action-btn edit">
                                            <i class="fas fa-edit"></i>
                                            edit
                                        </a>
                                        <form action="{{ route('training.schools.destroy', $school) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn delete" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                                delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="empty-message">No schools found. Click "Add New School" to create one.</td>
                        </tr>
                    @endif
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
    font-size: 24px;
    color: #333;
    margin: 0;
}

.add-button {
    background: #ff9933;
    color: white;
    padding: 8px 16px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 14px;
    border: none;
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

.custom-table th:first-child {
    width: 200px;
}

.custom-table th:last-child {
    width: 300px;
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

.action-buttons {
    display: flex;
    gap: 8px;
}

.action-btn {
    width: auto;
    min-width: 40px;
    height: 35px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 12px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    color: white;
    font-size: 14px;
    gap: 4px;
}

.view {
    background: #17a2b8;
}

.edit {
    background: #28a745;
}

.delete {
    background: #dc3545;
}

.empty-message {
    text-align: center;
    color: #666;
}

@media (max-width: 768px) {
    .page-container {
        padding: 16px;
    }
    
    .table-container {
        overflow-x: auto;
    }
    
    .custom-table {
        min-width: 1000px;
    }
}
</style>
@endsection
