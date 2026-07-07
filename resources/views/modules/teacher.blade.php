@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="bg-white rounded-[30px] p-8 shadow-xl border border-sky-100">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <x-application-logo class="h-11 w-11" />
                    <span class="text-sm font-bold uppercase tracking-wider text-sky-500">EduMonitor</span>
                </div>
                <h1 class="text-3xl font-extrabold text-sky-950 mb-3">Teacher Module</h1>
                <p class="text-slate-600 max-w-2xl">Welcome @if(isset($user)) {{ $user->name }} @endif. Access your class lists, record marks, and manage attendance from your assigned teaching area.</p>
            </div>

            <div class="grid gap-3 sm:grid-cols-2 lg:min-w-[360px]">
                <div class="rounded-2xl border border-sky-100 bg-sky-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Subject</p>
                    <p class="mt-1 font-extrabold text-sky-950">{{ $user->teachingSubject->name ?? 'Not selected' }}</p>
                </div>
                <div class="rounded-2xl border border-sky-100 bg-sky-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Classes</p>
                    <p class="mt-1 font-extrabold text-sky-950">
                        @if($user->teachingClasses->isNotEmpty())
                            {{ $user->teachingClasses->map(fn($class) => $class->name.($class->stream !== 'main' ? ' '.$class->stream : ''))->join(', ') }}
                        @else
                            Not selected
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-8">
            <a href="{{ route('teacher.classlists') }}" class="block p-6 bg-sky-50 rounded-2xl border border-sky-100 hover:shadow-md transition-shadow">
                <h3 class="font-bold mb-2 text-sky-950">Class Lists</h3>
                <p class="text-sm text-slate-600">View and manage the class lists assigned to you.</p>
            </a>

            <a href="{{ route('teacher.recordMarks') }}" class="block p-6 bg-sky-50 rounded-2xl border border-sky-100 hover:shadow-md transition-shadow">
                <h3 class="font-bold mb-2 text-sky-950">Record Marks</h3>
                <p class="text-sm text-slate-600">Enter assessment marks and grades for your students.</p>
            </a>

            <a href="{{ route('teacher.attendance') }}" class="block p-6 bg-sky-50 rounded-2xl border border-sky-100 hover:shadow-md transition-shadow">
                <h3 class="font-bold mb-2 text-sky-950">Attendance</h3>
                <p class="text-sm text-slate-600">Record and review student attendance.</p>
            </a>

            <a href="{{ route('teacher.reports') }}" class="block p-6 bg-sky-50 rounded-2xl border border-sky-100 hover:shadow-md transition-shadow">
                <h3 class="font-bold mb-2 text-sky-950">Reports</h3>
                <p class="text-sm text-slate-600">Generate class summary reports and export PDFs.</p>
            </a>
        </div>
    </div>
</div>
@endsection
