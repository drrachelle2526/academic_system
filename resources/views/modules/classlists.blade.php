@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="bg-white rounded-[30px] p-8 shadow-xl border border-sky-100">
        <h1 class="text-2xl font-extrabold text-sky-950 mb-3">Class Lists</h1>
        <p class="text-slate-600">These are the classes assigned to you for {{ $user->teachingSubject->name ?? 'your subject' }}.</p>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            @if($classLists->isNotEmpty())
                @foreach($classLists as $class)
                    <div class="p-5 border border-sky-100 rounded-2xl bg-sky-50">
                        <h3 class="font-extrabold text-sky-950">{{ $class->name }}{{ $class->stream !== 'main' ? ' '.$class->stream : '' }}</h3>
                        <p class="text-sm text-slate-600 mt-1">Learners: {{ $class->learners_count }}</p>
                        <p class="text-sm text-slate-600">Subject: {{ $user->teachingSubject->name ?? 'Not selected' }}</p>
                        <div class="mt-3">
                            <a href="{{ route('teacher.recordMarks') }}" class="text-sky-600 hover:underline mr-4">Record Marks</a>
                            <a href="{{ route('teacher.attendance') }}" class="text-sky-600 hover:underline">Attendance</a>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-slate-600">No classes are assigned to your account yet.</p>
            @endif
        </div>
    </div>
</div>
@endsection
