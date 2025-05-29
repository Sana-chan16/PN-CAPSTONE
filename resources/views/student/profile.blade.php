@extends('layouts.student_layout')

@section('content')
<div class="profile-container">
    <h1>My Profile</h1>
    <div class="student-profile" style="display: flex; align-items: center; gap: 30px; margin-bottom: 30px;">
        <div>
            @if(Auth::user()->profile_image)
                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}?v={{ time() }}" alt="Profile Image" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; border: 2px solid #22BBEA;">
            @else
                <div style="width: 100px; height: 100px; background: #eee; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2em; color: #888; border: 2px solid #22BBEA;">
                    <span>{{ strtoupper(substr(Auth::user()->user_fname,0,1)) }}</span>
                </div>
            @endif
            <form action="{{ route('student.profile.upload') }}" method="POST" enctype="multipart/form-data" style="margin-top: 10px;">
                @csrf
                <input type="file" name="profile_image" accept="image/*" style="margin-bottom: 5px;">
                <button type="submit" style="background: #22BBEA; color: white; border: none; padding: 5px 15px; border-radius: 5px; cursor: pointer;">Upload</button>
            </form>
        </div>
        <div>
            <div><strong>Name:</strong> {{ Auth::user()->user_fname }} {{ Auth::user()->user_mInitial }} {{ Auth::user()->user_lname }} {{ Auth::user()->suffix }}</div>
            <div class="profile-email"><strong>Email:</strong> {{ Auth::user()->user_email }}</div>
            <div><strong>ID Number:</strong> {{ Auth::user()->user_id }}</div>
            <div><strong>Role:</strong> {{ ucfirst(Auth::user()->user_role) }}</div>
        </div>
    </div>
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
</div>

<style>
.profile-container {
    padding: 20px;
    max-width: 600px;
    margin: 40px auto 0 auto;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
.profile-container h1 {
    color: #22BBEA;
    margin-bottom: 25px;
    text-align: center;
}
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: .25rem;
}
.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}
.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}
.profile-email {
    white-space: nowrap;
}
</style>
@endsection 