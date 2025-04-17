@extends('layouts.admin_layout')
@section('content')

<div class="create-user-container">
    <h1>Create New User</h1>

    <form action="{{ route('admin.pnph_users.store') }}" method="POST" class="create-user-form" id="createUserForm">
        @csrf
        <div class="form-group">
            <label for="user_id">User ID</label>
            <input type="text" name="user_id" id="user_id" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="user_lname">Last Name</label>
            <input type="text" name="user_lname" id="user_lname" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="user_fname">First Name</label>
            <input type="text" name="user_fname" id="user_fname" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="user_mInitial">Middle Initial</label>
            <input type="text" name="user_mInitial" id="user_mInitial" class="form-control">
        </div>

        <div class="form-group">
            <label for="user_suffix">Suffix</label>
            <input type="text" name="user_suffix" id="user_suffix" class="form-control">
        </div>

        <div class="form-group">
            <label for="user_email">Email</label>
            <input type="email" name="user_email" id="user_email" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="user_role">Role</label>
            <select name="user_role" id="user_role" class="form-control" required>
                <option value="">Select Role</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Create User</button>
            <a href="{{ route('admin.pnph_users.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<style>
.create-user-container {
    padding: 20px;
    max-width: 800px;
    margin: 0 auto;
}

.create-user-form {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
}

.form-control {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.form-control:focus {
    border-color: #22bbea;
    outline: none;
    box-shadow: 0 0 0 2px rgba(34, 187, 234, 0.2);
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    text-decoration: none;
    display: inline-block;
}

.btn-primary {
    background-color: #22bbea;
    color: white;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn:hover {
    opacity: 0.9;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createUserForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Basic form validation
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.style.borderColor = 'red';
            } else {
                field.style.borderColor = '#ddd';
            }
        });
        
        if (isValid) {
            form.submit();
        }
    });
});
</script>

@endsection