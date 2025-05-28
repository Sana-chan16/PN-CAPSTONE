@extends('layouts.student_layout')

@section('content')
<div class="submission-view-container" style="padding: 20px; max-width: 800px; margin: 20px auto; background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <a href="{{ route('student.dashboard') }}" style="text-decoration: none; color: #7c3aed; font-size: 1.5em; vertical-align: middle; margin-right: 8px;">&larr;</a>
    <span style="font-size: 2em; font-weight: 700; color: #dc3545; vertical-align: middle;">Failed Subjects</span>
    <div style="margin-top: 32px;">
    @if($subjects->isEmpty())
        <table style="width:100%; border-collapse:collapse; margin-bottom: 12px;">
            <thead>
                <tr>
                    <th style="font-weight: bold; font-size: 1.1em; padding: 8px 12px;">Subject Name</th>
                    <th style="font-weight: bold; font-size: 1.1em; padding: 8px 12px;">Grade</th>
                </tr>
            </thead>
        </table>
        <div style="font-size: 1.1em; margin-top: 8px;">No Failed subjects found.</div>
    @else
        <table class="subjects-table" style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f8f9fa;">
                    <th style="font-weight: bold; font-size: 1.1em; padding: 8px 12px;">Subject Name</th>
                    <th style="font-weight: bold; font-size: 1.1em; padding: 8px 12px;">Grade</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subjects as $entry)
                    <tr>
                        <td style="padding:10px 12px; border:1px solid #ddd;">{{ $entry->subject_name ?? 'N/A' }}</td>
                        <td style="padding:10px 12px; border:1px solid #ddd;">{{ $entry->grade ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    </div>
</div>
@endsection 