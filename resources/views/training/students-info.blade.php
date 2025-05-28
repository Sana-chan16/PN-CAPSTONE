@extends('layouts.nav')

@section('content')
<link rel="stylesheet" href="{{ asset('css/training/student-info.css') }}">



<div class="page-container">
    <div class="header-section">
        <h1 class="text-4xl font-semibold text-[#2c3e50]">Students Information</h1>
        <hr>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 rounded-md bg-[#dcfce7] border border-[#bbf7d0] text-[#166534]">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 rounded-md bg-[#fee2e2] border border-[#fecaca] text-[#dc2626]">
            {{ session('error') }}
        </div>
    @endif

    <form method="GET" action="{{ route('training.students.index') }}" class="mb-8">
        <div class="flex items-center gap-4">
            <label for="batch" class="text-sm font-medium text-gray-700">Filter by Batch:</label>
            <select name="batch" id="batch" 
                    class="w-64 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#22bbea] focus:border-[#22bbea] bg-white text-gray-700 text-sm"
                    onchange="this.form.submit()">
                <option value="">Select Batch</option>
                @foreach ($batches as $batch)
                    <option value="{{ $batch }}" {{ request('batch') == $batch ? 'selected' : '' }}>
                        {{ $batch }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    <br>

    <div class="table-wrapper">
            <div class="table-header">
                <div class="header-cell">USER ID</div>
                <div class="header-cell">STUDENT ID</div>
                <div class="header-cell">LAST NAME</div>
                <div class="header-cell">FIRST NAME</div>
                <div class="header-cell">MI</div>
                <div class="header-cell">SUFFIX</div>
                <div class="header-cell">SEX</div>
                <div class="header-cell">EMAIL</div>
                <div class="header-cell act1">ACTIONS</div>
            </div>
            
            @forelse($students as $student)
                <div class="table-row">
                    <div class="cell">{{ $student->user_id }}</div>
                    <div class="cell">{{ $student->studentDetail->student_id ?? 'N/A' }}</div>
                    <div class="cell">{{ $student->user_lname }}</div>
                    <div class="cell">{{ $student->user_fname }}</div>
                    <div class="cell">{{ $student->user_mInitial }}</div>
                    <div class="cell">{{ $student->user_suffix ?? '' }}</div>
                    <div class="cell">{{ $student->studentDetail->gender ?? 'N/A' }}</div>
                    <div class="cell">{{ $student->user_email }}</div>
                    <div class="cell">
                        <div class="action-buttons">
                            <a href="{{ route('training.students.view', $student->user_id) }}" class="btn btn-view">View</a>
                            <a href="{{ route('training.students.edit', $student->user_id) }}" class="btn btn-edit">Edit</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="table-row">
                    <div class="cell empty-message">No students found</div>
                </div>
            @endforelse
        </div>
        <div class="pagination-container">
            {{ $students->links() }}
        </div>
    </div>
@endsection
