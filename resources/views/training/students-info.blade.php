@extends('layouts.nav')

@section('content')
<h1>Students Information</h1>
    <section class="students-info">
        <div class="table-responsive">
            <table class="students-table">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td>{{ $student->user_id }}</td>
                            <td>{{ $student->user_lname }}</td>
                            <td>{{ $student->user_fname }}</td>
                            <td>{{ $student->user_email }}</td>
                            <td class="actions">
                                <a href="{{ route('training.students.edit', $student->user_id) }}" class="edit-btn">
                                    Edit
                                </a>
                                <form action="{{ route('training.students.destroy', $student->user_id) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this student?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
    
    <style>
        .students-info {
            padding: 20px;
        }

        /* .table-responsive {
            width: 100%;
            max-width: 1200px;
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
        }

        .students-table {
            width: 90%;
            border-collapse: collapse;
            margin: 0 auto;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .students-table th,
        .students-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        .students-table th {
            background-color: #4CAF50;
            color: white;
            font-weight: 500;
        }

        .students-table tr:hover {
            background-color: #f5f5f5;
        }

        .actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

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
