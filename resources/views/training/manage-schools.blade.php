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
        .table-responsive {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
        }
        .schools-table {
            width: 100%;
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
        .schools-table tr:hover {
            background-color: #f5f5f5;
        }
        .actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        .view-btn,
        .edit-btn,
        .delete-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
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