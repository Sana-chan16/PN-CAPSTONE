@extends('layouts.nav')

@section('content')
<div class="monitor-container">
    <div class="monitor-card">
        <div class="card-header-custom">
            <h2>Intern Grades</h2>
        </div>

        <div class="card-body-custom">
            @if(isset($message))
                <div class="alert-custom alert-warning-custom">
                    {{ $message }}
                </div>
            @endif

            {{-- Filtering Form --}}
            <div class="filter-section">
                <h3>Filter Intern Grades</h3>
                <form action="{{ route('training.intern-grades.index') }}" method="GET" class="filter-form-custom">
                    <div class="form-group-custom filter-group">
                        <label for="school_filter">Filter by School:</label>
                        <select name="school_filter" id="school_filter" class="form-control-custom">
                            <option value="">All Schools</option>
                            @foreach ($schools as $school)
                                <option value="{{ $school->school_id }}" {{ request('school_filter') == $school->school_id ? 'selected' : '' }}>
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-custom btn-primary-custom">Filter</button>
                    <a href="{{ route('training.intern-grades.index') }}" class="btn-custom btn-secondary-custom">Reset</a>
                </form>
            </div>

            <div class="table-responsive-custom">
                <table class="grade-monitor-table">
                    <thead>
                        <tr>
                            <th>Intern Name</th>
                            <th>School</th>
                            <th>Class</th>
                            <th>Subject</th>
                            <th>Grade</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($internGrades as $grade)
                            <tr>
                                <td>{{ $grade->intern_name }}</td>
                                <td>{{ $grade->school_name }}</td>
                                <td>{{ $grade->class_name }}</td>
                                <td>{{ $grade->subject_name }}</td>
                                <td class="text-center-custom">{{ $grade->grade }}</td>
                                <td class="text-center-custom">
                                    <span class="status-badge status-{{ strtolower($grade->status) }}">
                                        {{ $grade->status }}
                                    </span>
                                </td>
                                <td class="text-center-custom">
                                    <a href="{{ route('training.intern-grades.edit', $grade->id) }}" 
                                       class="btn-custom btn-primary-custom btn-sm-custom">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center-custom">No intern grades found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .status-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-approved {
        background-color: #d4edda;
        color: #155724;
    }

    .status-rejected {
        background-color: #f8d7da;
        color: #721c24;
    }
</style>
@endsection 