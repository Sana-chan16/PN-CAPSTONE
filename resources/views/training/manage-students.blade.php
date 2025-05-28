@extends('layouts.nav')

@section('content')

<link rel="stylesheet" href="{{ asset('css/training/school.css') }}">

<h1 class="text-4xl font-semibold text-[#2c3e50] mt-6">Schools</h1>
<hr>
<div class="page-container">
    <div class="header-section">
        <a href="{{ route('training.schools.create') }}" class="btn btn-primary">
            Add New School
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-md bg-[#dcfce7] border border-[#bbf7d0] text-[#166534]">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 rounded-md bg-[#fee2e2] border border-[#fecaca] text-[#dc2626]">
            {{ session('error') }}
        </div>
    @endif

    <div class="table-wrapper">
        <div class="table-header">
            <div class="header-cell">ID</div>
            <div class="header-cell">School</div>
            <div class="header-cell">Department</div>
            <div class="header-cell">Course</div>
            <div class="header-cell">Actions</div>
        </div>
        
        @forelse($schools as $school)
            <div class="table-row">
                @if(is_object($school))
                    <div class="cell">{{ $school->school_id }}</div>
                    <div class="cell">{{ $school->name }}</div>
                    <div class="cell">{{ $school->department }}</div>
                    <div class="cell">{{ $school->course }}</div>
                    <div class="cell">
                        <div class="action-buttons">
                            <a href="{{ route('training.schools.show', $school) }}" class="btn btn-view">
                                view
                            </a>
                            <a href="{{ route('training.schools.edit', $school) }}" class="btn btn-edit">
                                edit
                            </a>
                            <form action="{{ route('training.schools.destroy', $school) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete" onclick="return confirm('Are you sure?')">
                                    delete
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="cell" colspan="5">Invalid school data</div>
                @endif
            </div>
        @empty
            <div class="table-row">
                <div class="cell empty-message">No schools found</div>
            </div>
        @endforelse
    </div>
</div>

@endsection
