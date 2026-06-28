@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="bg-white rounded-lg p-8 shadow">
        <h1 class="text-2xl font-bold mb-4">Academic Teacher Module</h1>
        <p class="text-slate-600 mb-6">Compile school results from teacher submissions and prepare the official template without changing its format.</p>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="{{ route('academicTeacher.schoolCompilation') }}" class="block p-6 bg-sky-50 rounded-lg border border-sky-100 hover:shadow">
                <h3 class="font-bold mb-2">School Compilation</h3>
                <p class="text-sm text-slate-600">Combine subject marks into class and school result summaries.</p>
            </a>
            <a href="{{ route('academicTeacher.missingMarks') }}" class="block p-6 bg-sky-50 rounded-lg border border-sky-100 hover:shadow">
                <h3 class="font-bold mb-2">Missing Marks</h3>
                <p class="text-sm text-slate-600">Check incomplete teacher submissions before verification.</p>
            </a>
            <a href="{{ route('academicTeacher.officialTemplate') }}" class="block p-6 bg-sky-50 rounded-lg border border-sky-100 hover:shadow">
                <h3 class="font-bold mb-2">Official Template</h3>
                <p class="text-sm text-slate-600">Prepare data for export into the government template.</p>
            </a>
        </div>
    </div>
</div>
@endsection
