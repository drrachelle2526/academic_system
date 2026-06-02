@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="bg-white rounded-[20px] p-8 shadow">
        <h1 class="text-2xl font-bold mb-4">Class Lists</h1>
        <p class="text-slate-600">This page shows the class lists assigned to you.</p>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            @if(!empty($classLists))
                @foreach($classLists as $class)
                    <div class="p-4 border rounded-lg bg-sky-50">
                        <h3 class="font-semibold">{{ $class['name'] }}</h3>
                        <p class="text-sm text-slate-600">Students: {{ $class['students'] }}</p>
                        <p class="text-sm text-slate-600">Subjects: {{ implode(', ', $class['subjects']) }}</p>
                        <div class="mt-3">
                            <a href="{{ route('teacher.recordMarks') }}" class="text-sky-600 hover:underline mr-4">Record Marks</a>
                            <a href="{{ route('teacher.attendance') }}" class="text-sky-600 hover:underline">Attendance</a>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-slate-600">No classes found.</p>
            @endif
        </div>
</div>
@endsection
