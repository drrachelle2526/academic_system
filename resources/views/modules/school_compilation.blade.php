@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="bg-white rounded-[30px] p-8 shadow-xl border border-sky-100">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-sky-950">School Results Compilation</h1>
                <p class="text-slate-600 mt-2 max-w-2xl">Combine teacher submissions into class and subject summaries before sending them to the Head Teacher.</p>
            </div>
            <div class="rounded-2xl border border-sky-100 bg-sky-50 px-5 py-4 min-w-[220px]">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Completion</p>
                <p class="mt-1 text-2xl font-extrabold text-sky-950">{{ $completionPercent }}%</p>
                <p class="text-sm text-slate-500">{{ $submittedMarks->count() }} of {{ $expectedMarksCount }} marks</p>
            </div>
        </div>

        @if(session('status'))
            <div class="mt-6 rounded-2xl border border-green-100 bg-green-50 p-4 text-sm font-semibold text-green-700">{{ session('status') }}</div>
        @endif

        <div class="mt-8 flex flex-col gap-3 rounded-2xl border border-sky-100 bg-sky-50 p-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="font-extrabold text-sky-950">{{ $exam?->name ?? 'No exam selected' }}</p>
                <p class="text-sm text-slate-600">{{ $missingMarksCount }} missing mark(s) must be cleared before submission.</p>
            </div>
            @if($missingMarksCount === 0 && $exam)
                <form method="POST" action="{{ route('academicTeacher.schoolCompilation.submit') }}">
                    @csrf
                    <button type="submit" class="rounded-full bg-green-600 px-5 py-3 text-sm font-bold text-white shadow hover:bg-green-700">Submit to Head Teacher</button>
                </form>
            @else
                <a href="{{ route('academicTeacher.missingMarks') }}" class="rounded-full bg-amber-500 px-5 py-3 text-sm font-bold text-white shadow hover:bg-amber-600">Review Missing Marks</a>
            @endif
        </div>

        <div class="grid gap-6 mt-8 lg:grid-cols-2">
            <div class="rounded-2xl border border-sky-100 bg-white p-5">
                <h2 class="text-lg font-extrabold text-sky-950">Class Summary</h2>
                <div class="mt-4 space-y-3">
                    @forelse($classSummaries as $class)
                        <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                            <div class="flex items-center justify-between gap-3">
                                <p class="font-bold text-slate-900">{{ $class['name'] }}</p>
                                <p class="text-sm font-bold text-sky-800">{{ $class['average'] ?? 'No avg' }}</p>
                            </div>
                            <p class="mt-1 text-sm text-slate-500">{{ $class['submitted'] }} submitted, {{ $class['missing'] }} missing, {{ $class['learners'] }} learners</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No classes registered yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border border-sky-100 bg-white p-5">
                <h2 class="text-lg font-extrabold text-sky-950">Subject Summary</h2>
                <div class="mt-4 space-y-3">
                    @forelse($subjectSummaries as $subject)
                        <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                            <div class="flex items-center justify-between gap-3">
                                <p class="font-bold text-slate-900">{{ $subject['name'] }}</p>
                                <p class="text-sm font-bold text-sky-800">{{ $subject['average'] ?? 'No avg' }}</p>
                            </div>
                            <p class="mt-1 text-sm text-slate-500">{{ $subject['submitted'] }} submitted, {{ $subject['missing'] }} missing</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No subjects registered yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="grid gap-6 mt-8 lg:grid-cols-2">
            <div class="rounded-2xl border border-sky-100 bg-white p-5">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-lg font-extrabold text-sky-950">Teacher Completion</h2>
                    <a href="{{ route('academicTeacher.teacherSubmissions') }}" class="text-sm font-bold text-sky-700">Open</a>
                </div>
                <div class="mt-4 space-y-3">
                    @forelse($teacherSubmissions->take(6) as $item)
                        <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                            <div class="flex items-center justify-between gap-3">
                                <p class="font-bold text-slate-900">{{ $item['teacher']->name }}</p>
                                <p class="text-sm font-bold text-sky-800">{{ $item['completion'] }}%</p>
                            </div>
                            <p class="mt-1 text-sm text-slate-500">{{ $item['subject']->name ?? 'No subject' }}: {{ $item['submitted'] }} submitted, {{ $item['missing'] }} missing</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No teacher submissions yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border border-sky-100 bg-white p-5">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-lg font-extrabold text-sky-950">Learner Performance</h2>
                    <a href="{{ route('academicTeacher.learnerPerformance') }}" class="text-sm font-bold text-sky-700">Open</a>
                </div>
                <div class="mt-4 space-y-3">
                    @forelse($learnerPerformance->take(6) as $item)
                        <div class="rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                            <div class="flex items-center justify-between gap-3">
                                <p class="font-bold text-slate-900">{{ $item['learner']->name }}</p>
                                <p class="text-sm font-bold text-sky-800">{{ $item['average'] ?? '-' }}</p>
                            </div>
                            <p class="mt-1 text-sm text-slate-500">{{ $item['submitted'] }} submitted, {{ $item['missing'] }} missing</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No learner marks yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
