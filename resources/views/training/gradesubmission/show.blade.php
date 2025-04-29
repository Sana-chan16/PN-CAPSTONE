@extends('layouts.nav')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <a href="{{ route('training.gradesubmission.index') }}" class="text-blue-500 hover:underline">
            &larr; Back to Classes
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h1 class="text-2xl font-bold mb-4">{{ $class->class_name }}</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <p class="text-gray-600">School: {{ $class->school->name }}</p>
                <p class="text-gray-600">Department: {{ $class->school->department }}</p>
                <p class="text-gray-600">Course: {{ $class->school->course }}</p>
            </div>
            <div>
                <p class="text-gray-600">Batch: {{ $class->batch }}</p>
                <p class="text-gray-600">Number of Students: {{ $class->students->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold mb-4">Create Grade Submission</h2>
        <form action="{{ route('training.gradesubmission.store') }}" method="POST">
            @csrf
            <input type="hidden" name="class_id" value="{{ $class->id }}">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-700 font-bold mb-2" for="semester">
                        Semester
                    </label>
                    <select name="semester" id="semester" class="w-full border rounded px-3 py-2" required>
                        <option value="">Select Semester</option>
                        <option value="1">1st Semester</option>
                        <option value="2">2nd Semester</option>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2" for="term">
                        Term
                    </label>
                    <select name="term" id="term" class="w-full border rounded px-3 py-2" required>
                        <option value="">Select Term</option>
                        @foreach($class->school->terms as $term)
                            <option value="{{ $term }}">{{ $term }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-bold mb-2" for="academic_year">
                        Academic Year
                    </label>
                    <input type="text" name="academic_year" id="academic_year" 
                           class="w-full border rounded px-3 py-2"
                           placeholder="e.g., 2024-2025" required>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">
                    Subjects
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($class->school->subjects as $subject)
                    <div class="flex items-center">
                        <input type="checkbox" name="subjects[]" value="{{ $subject->id }}"
                               id="subject_{{ $subject->id }}" class="mr-2">
                        <label for="subject_{{ $subject->id }}">
                            {{ $subject->name }}
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                    Create Grade Submission
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
