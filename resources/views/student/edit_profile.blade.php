@extends('layouts.student_layout')

@section('content')
<div class="container mt-1" style="max-width: 500px; margin-top: 20px !important;">
    <div class="card">
        <div class="card-body d-flex flex-column align-items-center justify-content-center" style="text-align: left;">
            <h3 style="margin-bottom: 18px;">Student Information</h3>
            @php
                $userId = Auth::user()->user_id;
                $imageExtensions = ['jpg', 'jpeg', 'png'];
                $profileImage = null;
                foreach ($imageExtensions as $ext) {
                    $candidate = 'storage/profile_images/profile_' . $userId . '.' . $ext;
                    if (file_exists(public_path($candidate))) {
                        $profileImage = asset($candidate);
                        break;
                    }
                }
                $Name = trim($user->user_fname . ' ' . $user->user_mInitial . ' ' . $user->user_lname . ' ' . ($user->suffix ?? ''));
            @endphp
            <img src="{{ $profileImage ? $profileImage . '?v=' . time() : asset('images/default-profile.png') }}"
                 class="rounded-circle mb-3" width="180" height="180" style="object-fit:cover; border: 3px solid #22bbea;" alt="Profile Image">
            <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data" style="width: 100%; max-width: 350px; margin: 18px auto 18px auto;">
                @csrf
                <div class="mb-2">
                    <input type="file" name="profile_image" class="form-control">
                </div>
                <button class="btn btn-primary w-100" type="submit">Update Profile</button>
            </form>
            @if(session('success'))
                <div class="alert alert-success mt-2">{{ session('success') }}</div>
            @endif
            <div class="mb-3" style="width: 100%; max-width: 350px; margin: 0 auto; text-align: left;">
                <div style="font-size: 1.1em; color: #222;"><b>Name:</b> {{ $Name }}</div>
                <div style="font-size: 1.1em; color: #222;"><b>Email:</b> <input type="email" class="form-control" value="{{ $user->user_email }}" readonly style="background:#f8f9fa; color:#222; border:none; padding-left:0; font-size:1.1em; display:inline; width:auto;"></div>
                <div style="font-size: 1.1em; color: #222;"><b>User ID:</b> {{ $user->user_id }}</div>
                <div style="font-size: 1.1em; color: #222;"><b>Role:</b> {{ ucfirst($user->user_role) }}</div>
                <div style="font-size: 1.1em; color: #222;"><b>Status:</b> {{ ucfirst($user->status ?? 'N/A') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
@media (max-width: 600px) {
    .container.mt-1 {
        max-width: 100dvw !important;
        width: 100dvw !important;
        padding: 0 8px;
        margin-top: 10px !important;
        overflow-y: auto;
    }
    .card-body img.rounded-circle {
        width: 120px !important;
        height: 120px !important;
    }
    .card-body form {
        max-width: 100% !important;
    }
    .mb-3[style] {
        max-width: 100% !important;
    }
}
</style> 