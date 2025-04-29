@extends('layouts.nav')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Grade Submission</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($classes as $class)
        <div class="bg-white rounded-lg shadow-md p-4">
            <h2 class="text-xl font-semibold mb-2">{{ $class->class_name }}</h2>
            <p class="text-gray-600 mb-2">School: {{ $class->school->name }}</p>
            <p class="text-gray-600 mb-4">Department: {{ $class->school->department }}</p>
            <a href="{{ route('training.gradesubmission.show', $class->id) }}" 
               class="inline-block bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                View Class
            </a>
        </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $classes->links() }}
    </div>
</div>
@endsection
