@extends('layouts.student_layout')

@section('content')
<div class="container mt-4" style="max-width: 800px;">
    <div class="card shadow-sm">
        <div class="card-body">
            @if(isset($debugInfo))
            <div style="background:#f8f9fa; border:1px solid #ddd; padding:10px; margin-bottom:16px; font-size:0.95em; color:#333;">
                <strong>Debug Info:</strong><br>
                <b>Selected Semester:</b> {{ $debugInfo['selectedSemester'] ?? '-' }}<br>
                <b>Parsed:</b> Semester: {{ $debugInfo['parsed']['semester'] ?? '-' }}, Term: {{ $debugInfo['parsed']['term'] ?? '-' }}, Year: {{ $debugInfo['parsed']['year'] ?? '-' }}<br>
                <b>Submission found:</b> {{ $debugInfo['submission_found'] ? 'Yes' : 'No' }}<br>
                <b>Grades found:</b> {{ $debugInfo['grades_count'] ?? 0 }}<br>
                <b>Approved grades:</b> {{ $debugInfo['approved_grades_count'] ?? 0 }}<br>
            </div>
            @endif
            <h2 class="text-center mb-3" style="font-weight: 700; color: #007bff;">
                Grade Status for {{ $selectedSemester }} | Term: {{ $selectedTerm }} | AY: {{ $selectedYear }}
            </h2>
            <form method="GET" action="" style="margin-bottom: 18px;">
                <label for="term_sem" style="font-weight: 500; margin-right: 8px;">Select Semester & Term:</label>
                <select id="term_sem" name="term_sem" onchange="this.form.submit()" style="padding: 6px 12px; border-radius: 6px;">
                    @foreach($semesters as $sem)
                        <option value="{{ $sem->label }}" {{ $selectedKey == $sem->label ? 'selected' : '' }}>
                            {{ $sem->label }}
                        </option>
                    @endforeach
                </select>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">Subject Name</th>
                            <th class="text-center">Semester</th>
                            <th class="text-center">Term</th>
                            <th class="text-center">Grade</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($grades as $grade)
                            <tr>
                                <td class="text-center">{{ $grade->subject_name ?? 'N/A' }}</td>
                                <td class="text-center">{{ $grade->semester ?? 'N/A' }}</td>
                                <td class="text-center">{{ $grade->term ?? 'N/A' }}</td>
                                <td class="text-center">{{ $grade->grade ?? 'N/A' }}</td>
                                <td class="text-center">
                                    @if($grade->status === 'approved')
                                        @if(is_numeric($grade->grade) && $grade->grade >= 1.0 && $grade->grade <= 3.0)
                                            <span style="color: green; font-weight: 600;">Passed</span>
                                        @elseif(is_numeric($grade->grade) && $grade->grade > 3.0 && $grade->grade <= 5.0)
                                            <span style="color: red; font-weight: 600;">Failed</span>
                                        @elseif(strtoupper($grade->grade) === 'INC')
                                            <span style="color: orange; font-weight: 600;">INC</span>
                                        @elseif(strtoupper($grade->grade) === 'NC')
                                            <span style="color: #00bcd4; font-weight: 600;">NC</span>
                                        @elseif(in_array(strtoupper($grade->grade), ['DR', 'DROPOUT', 'DROP OUT']))
                                            <span style="color: #6c757d; font-weight: 600;">DR</span>
                                        @else
                                            <span style="color: gray;">N/A</span>
                                        @endif
                                    @elseif($grade->status === 'pending')
                                        <span style="color: orange; font-weight: 600;">Pending</span>
                                    @elseif($grade->status === 'submitted')
                                        <span style="color: #007bff; font-weight: 600;">Submitted</span>
                                    @elseif($grade->status === 'rejected')
                                        <span style="color: #dc3545; font-weight: 600;">Rejected</span>
                                    @else
                                        <span style="color: gray;">N/A</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No grades available for this semester.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <h3 class="text-center mb-3 mt-4" style="font-weight: 600; color: #000;">Summary for {{ $selectedSemester }}</h3>
            <div class="table-responsive" style="max-width: 400px; margin: 0 auto 24px auto;">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">Passed</th>
                            <th class="text-center">Failed</th>
                            <th class="text-center">INC</th>
                            <th class="text-center">NC</th>
                            <th class="text-center">DR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center" style="color: #28a745; font-weight: bold;">{{ $selectedSummary['passed'] }}</td>
                            <td class="text-center" style="color: #dc3545; font-weight: bold;">{{ $selectedSummary['failed'] }}</td>
                            <td class="text-center" style="color: #ff9900; font-weight: bold;">{{ $selectedSummary['inc'] }}</td>
                            <td class="text-center" style="color: #00bcd4; font-weight: bold;">{{ $selectedSummary['nc'] }}</td>
                            <td class="text-center" style="color: #6c757d; font-weight: bold;">{{ $selectedSummary['dr'] }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection