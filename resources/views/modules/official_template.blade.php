@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="bg-white rounded-[30px] p-8 shadow-xl border border-sky-100">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-sky-950">Official Report Preparation</h1>
                <p class="text-slate-600 mt-2 max-w-2xl">Use the compiled school data to prepare the official report after teacher marks are complete.</p>
            </div>
            <div class="rounded-2xl border border-sky-100 bg-sky-50 px-5 py-4 min-w-[220px]">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Readiness</p>
                <p class="mt-1 text-lg font-extrabold {{ $missingMarksCount > 0 ? 'text-amber-700' : 'text-green-700' }}">
                    {{ $missingMarksCount > 0 ? 'Not Ready' : 'Ready' }}
                </p>
                <p class="text-sm text-slate-500">{{ $submission ? 'Submitted: '.ucwords($submission->status) : 'Draft' }}</p>
                <button type="button" onclick="window.print()" class="mt-3 rounded-full bg-white px-4 py-2 text-sm font-bold text-sky-700 shadow-sm ring-1 ring-sky-100 hover:bg-sky-50">Print / Save PDF</button>
            </div>
        </div>

        <div class="grid gap-4 mt-8 md:grid-cols-4">
            <div class="rounded-2xl border border-sky-100 bg-sky-50 p-5">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Learners</p>
                <p class="mt-2 text-3xl font-extrabold text-sky-950">{{ $learners->count() }}</p>
            </div>
            <div class="rounded-2xl border border-sky-100 bg-sky-50 p-5">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Classes</p>
                <p class="mt-2 text-3xl font-extrabold text-sky-950">{{ $classes->count() }}</p>
            </div>
            <div class="rounded-2xl border border-sky-100 bg-sky-50 p-5">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Subjects</p>
                <p class="mt-2 text-3xl font-extrabold text-sky-950">{{ $subjects->count() }}</p>
            </div>
            <div class="rounded-2xl border border-sky-100 bg-sky-50 p-5">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Average</p>
                <p class="mt-2 text-3xl font-extrabold text-sky-950">{{ $submittedMarks->isNotEmpty() ? round($submittedMarks->avg('score'), 1) : 'N/A' }}</p>
            </div>
        </div>

        <div class="mt-8 rounded-2xl border border-sky-100 bg-white p-5">
            <h2 class="text-lg font-extrabold text-sky-950">Report Checklist</h2>
            <div class="mt-4 space-y-3">
                <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <span class="font-semibold text-slate-800">All classes registered</span>
                    <span class="font-bold {{ $classes->isNotEmpty() ? 'text-green-700' : 'text-amber-700' }}">{{ $classes->isNotEmpty() ? 'Done' : 'Needed' }}</span>
                </div>
                <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <span class="font-semibold text-slate-800">All subjects registered</span>
                    <span class="font-bold {{ $subjects->isNotEmpty() ? 'text-green-700' : 'text-amber-700' }}">{{ $subjects->isNotEmpty() ? 'Done' : 'Needed' }}</span>
                </div>
                <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                    <span class="font-semibold text-slate-800">All marks submitted</span>
                    <span class="font-bold {{ $missingMarksCount === 0 ? 'text-green-700' : 'text-amber-700' }}">{{ $missingMarksCount === 0 ? 'Done' : $missingMarksCount.' missing' }}</span>
                </div>
            </div>
        </div>

        <div class="mt-8 flex flex-col gap-3 rounded-2xl border border-sky-100 bg-sky-50 p-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="font-extrabold text-sky-950">Next step</p>
                <p class="text-sm text-slate-600">Submit the completed compilation from the School Compilation page for Head Teacher verification.</p>
            </div>
            <a href="{{ route('academicTeacher.schoolCompilation') }}" class="rounded-full bg-sky-500 px-5 py-3 text-sm font-bold text-white shadow hover:bg-sky-600">Open Compilation</a>
        </div>
    </div>
</div>
@endsection
