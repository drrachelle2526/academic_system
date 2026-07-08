@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="bg-white rounded-[30px] p-8 shadow-xl border border-sky-100">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <x-application-logo class="h-11 w-11" />
                    <span class="text-sm font-bold uppercase tracking-wider text-sky-500">Academic Teacher Module</span>
                </div>
                <h1 class="text-3xl font-extrabold text-sky-950">Academic Coordination</h1>
                <p class="text-slate-600 mt-2 max-w-2xl">Check teacher submissions, compile school results, and prepare reports for Head Teacher verification.</p>
            </div>

            <div class="rounded-2xl border border-sky-100 bg-sky-50 px-5 py-4 min-w-[240px]">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Current Exam</p>
                <p class="mt-1 font-extrabold text-sky-950">{{ $exam?->name ?? 'No exam set' }}</p>
                <p class="text-sm text-slate-500">{{ $exam ? $exam->term.' '.$exam->year : ($user->school->name ?? 'School not assigned') }}</p>
            </div>
        </div>

        <div class="grid gap-4 mt-8 md:grid-cols-4">
            <div class="rounded-2xl border border-sky-100 bg-sky-50 p-5">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Completion</p>
                <p class="mt-2 text-3xl font-extrabold text-sky-950">{{ $completionPercent }}%</p>
            </div>
            <div class="rounded-2xl border border-sky-100 bg-sky-50 p-5">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Submitted Marks</p>
                <p class="mt-2 text-3xl font-extrabold text-sky-950">{{ $submittedMarks->count() }}</p>
            </div>
            <div class="rounded-2xl border border-sky-100 bg-sky-50 p-5">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Missing Marks</p>
                <p class="mt-2 text-3xl font-extrabold {{ $missingMarksCount > 0 ? 'text-amber-700' : 'text-green-700' }}">{{ $missingMarksCount }}</p>
            </div>
            <div class="rounded-2xl border border-sky-100 bg-sky-50 p-5">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Status</p>
                <p class="mt-2 text-lg font-extrabold text-sky-950">{{ $submission ? ucwords($submission->status) : 'Draft' }}</p>
            </div>
        </div>

        @if(session('status'))
            <div class="mt-6 rounded-2xl border border-green-100 bg-green-50 p-4 text-sm font-semibold text-green-700">{{ session('status') }}</div>
        @endif

        @if($errors->any())
            <div class="mt-6 rounded-2xl border border-red-100 bg-red-50 p-4 text-sm font-semibold text-red-700">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('academicTeacher.exams.store') }}" class="mt-8 rounded-2xl border border-sky-100 bg-sky-50 p-5">
            @csrf
            <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-extrabold text-sky-950">Current Exam Setup</h2>
                    <p class="text-sm text-slate-500 mt-1">Set the active exam teachers will record marks against.</p>
                </div>
                <button type="submit" class="rounded-full bg-sky-500 px-5 py-3 text-sm font-bold text-white shadow hover:bg-sky-600">Save Exam</button>
            </div>
            <div class="mt-4 grid gap-4 md:grid-cols-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Exam Name</label>
                    <input type="text" name="name" value="{{ old('name', $exam?->name ?? 'Annual Examination') }}" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-800 outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Term</label>
                    <input type="text" name="term" value="{{ old('term', $exam?->term ?? 'Term 2') }}" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-800 outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Year</label>
                    <input type="number" name="year" value="{{ old('year', $exam?->year ?? now()->year) }}" required min="2000" max="2100" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-800 outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Start Date</label>
                    <input type="date" name="starts_on" value="{{ old('starts_on', $exam?->starts_on?->format('Y-m-d')) }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-800 outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200">
                </div>
            </div>
            <div class="mt-4 max-w-sm">
                <label class="block text-sm font-semibold text-slate-700 mb-1">End Date</label>
                <input type="date" name="ends_on" value="{{ old('ends_on', $exam?->ends_on?->format('Y-m-d')) }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-800 outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200">
            </div>
        </form>

        <div class="grid grid-cols-1 gap-4 mt-8 sm:grid-cols-2 lg:grid-cols-3">
            <a href="{{ route('academicTeacher.schoolCompilation') }}" class="block rounded-2xl border border-sky-100 bg-white p-5 shadow-sm hover:shadow">
                <h3 class="font-bold text-sky-950 mb-2">School Compilation</h3>
                <p class="text-sm text-slate-600">Review class and subject summaries before submission.</p>
            </a>
            <a href="{{ route('academicTeacher.missingMarks') }}" class="block rounded-2xl border border-sky-100 bg-white p-5 shadow-sm hover:shadow">
                <h3 class="font-bold text-sky-950 mb-2">Missing Marks</h3>
                <p class="text-sm text-slate-600">Find learners and subjects still missing scores.</p>
            </a>
            <a href="{{ route('academicTeacher.teacherSubmissions') }}" class="block rounded-2xl border border-sky-100 bg-white p-5 shadow-sm hover:shadow">
                <h3 class="font-bold text-sky-950 mb-2">Teacher Submissions</h3>
                <p class="text-sm text-slate-600">Track each teacher's subject, classes, and completion.</p>
            </a>
            <a href="{{ route('academicTeacher.learnerPerformance') }}" class="block rounded-2xl border border-sky-100 bg-white p-5 shadow-sm hover:shadow">
                <h3 class="font-bold text-sky-950 mb-2">Learner Performance</h3>
                <p class="text-sm text-slate-600">Review every learner's total, average, and missing marks.</p>
            </a>
            <a href="{{ route('academicTeacher.resultsRanking') }}" class="block rounded-2xl border border-sky-100 bg-white p-5 shadow-sm hover:shadow">
                <h3 class="font-bold text-sky-950 mb-2">Class Ranking</h3>
                <p class="text-sm text-slate-600">Position learners from first to last by class.</p>
            </a>
            <a href="{{ route('academicTeacher.resultsComparison') }}" class="block rounded-2xl border border-sky-100 bg-white p-5 shadow-sm hover:shadow">
                <h3 class="font-bold text-sky-950 mb-2">Results Comparison</h3>
                <p class="text-sm text-slate-600">Compare terms and years by class, subject, and learner.</p>
            </a>
            <a href="{{ route('academicTeacher.learnerRegister') }}" class="block rounded-2xl border border-sky-100 bg-white p-5 shadow-sm hover:shadow">
                <h3 class="font-bold text-sky-950 mb-2">Learner Register</h3>
                <p class="text-sm text-slate-600">Read-only learner list by class and stream.</p>
            </a>
            <a href="{{ route('academicTeacher.officialTemplate') }}" class="block rounded-2xl border border-sky-100 bg-white p-5 shadow-sm hover:shadow">
                <h3 class="font-bold text-sky-950 mb-2">Official Report</h3>
                <p class="text-sm text-slate-600">Prepare the compiled data for official reporting.</p>
            </a>
        </div>
    </div>
</div>
@endsection
