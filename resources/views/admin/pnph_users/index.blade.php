@extends('layouts.admin_layout')
@section('content')

    <div class="users-container">
        <h1>Manage Users</h1>

        <button class="add-user-btn">
            <a href="{{ route('admin.pnph_users.create') }}" >Create New User</a>
        </button>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Middle Initial</th>
                        <th>Suffix</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->user_id }}</td>
                            <td>{{ $user->user_lname }}</td>
                            <td>{{ $user->user_fname }}</td>
                            <td>{{ $user->user_mInitial }}</td>
                            <td>{{ $user->user_suffix }}</td>
                            <td>{{ $user->user_email }}</td>
                            <td>{{ $user->user_role }}</td>
                            <td>{{ $user->status }}</td>
                            <td>
                                <button class="edit-btn">
                                    <a href="{{ route('admin.pnph_users.edit', $user->user_id) }}" class="btn btn-warning">Edit</a>
                                </button>
                                <form action="{{ route('admin.pnph_users.destroy', $user->user_id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

<style>
.users-container {
    padding: 20px;
}

.action-buttons {
    margin-bottom: 20px;
}

.add-user-btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #ff9933;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    border: none;
    cursor: pointer;
}

.add-user-btn:hover {
    background-color: #ffc107;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.table th, .table td {
    padding: 12px;
    text-align: left;
    border: 1px solid #ddd;
}

.table th {
    background-color: #f4f4f4;
    font-weight: 500;
}

.table tr {
    background-color: #fff;
}

.table tr:hover {
    background-color: #f5f5f5;
}

.action-cell {
    display: flex;
    gap: 10px;
    align-items: center;
}

.edit-btn, .delete-btn {
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
}

.edit-btn {
    background-color: #ffc107;
    color: #000;
}

.delete-btn {
    background-color: #dc3545;
    color: #fff;
}

.delete-form {
    display: inline;
    margin: 0;
}

.delete-btn:hover {
    background-color: #c82333;
}

.edit-btn:hover {
    background-color: #e0a800;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prevent row clicks from triggering navigation
    const tableRows = document.querySelectorAll('.table tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('click', function(e) {
            // Only prevent default if clicking on the row itself, not on action buttons
            if (e.target.tagName !== 'A' && e.target.tagName !== 'BUTTON' && !e.target.closest('form')) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });
});
</script>

@endsection
