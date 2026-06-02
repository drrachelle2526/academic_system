@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="bg-white rounded-[20px] p-8 shadow">
        <h1 class="text-2xl font-bold mb-4">Teacher Module</h1>
        <p class="text-slate-600 mb-6">Welcome @if(isset($user)) {{ $user->name }} @endif — access your class lists, record marks, and manage attendance from here.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <a href="{{ route('teacher.classlists') }}" class="block p-6 bg-sky-50 rounded-lg border border-sky-100 hover:shadow">
                <h3 class="font-bold mb-2">Class Lists</h3>
                <p class="text-sm text-slate-600">View and manage the class lists assigned to you.</p>
            </a>

            <a href="{{ route('teacher.recordMarks') }}" class="block p-6 bg-sky-50 rounded-lg border border-sky-100 hover:shadow">
                <h3 class="font-bold mb-2">Record Marks</h3>
                <p class="text-sm text-slate-600">Enter assessment marks and grades for your students.</p>
            </a>

            <a href="{{ route('teacher.attendance') }}" class="block p-6 bg-sky-50 rounded-lg border border-sky-100 hover:shadow">
                <h3 class="font-bold mb-2">Attendance</h3>
                <p class="text-sm text-slate-600">Record and review student attendance.</p>
            </a>

            <a href="{{ route('teacher.reports') }}" class="block p-6 bg-sky-50 rounded-lg border border-sky-100 hover:shadow">
                <h3 class="font-bold mb-2">Reports</h3>
                <p class="text-sm text-slate-600">Generate class summary reports and export PDFs.</p>
            </a>
        </div>
    </div>
</div>
@endsection
