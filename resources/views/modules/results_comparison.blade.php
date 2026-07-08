@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="bg-white rounded-[30px] p-8 shadow-xl border border-sky-100">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-sky-950">Results Comparison</h1>
                <p class="text-slate-600 mt-2 max-w-2xl">Compare performance between different terms or years by school, class, subject, and learner.</p>
            </div>
            <div class="rounded-2xl border border-sky-100 bg-sky-50 px-5 py-4 min-w-[220px]">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">School Change</p>
                <p class="mt-1 text-3xl font-extrabold {{ ($schoolComparison['change'] ?? 0) >= 0 ? 'text-green-700' : 'text-red-700' }}">
                    {{ $schoolComparison['change'] !== null ? ($schoolComparison['change'] > 0 ? '+' : '').$schoolComparison['change'] : '-' }}
                </p>
                <button type="button" onclick="window.print()" class="mt-3 rounded-full bg-white px-4 py-2 text-sm font-bold text-sky-700 shadow-sm ring-1 ring-sky-100 hover:bg-sky-50">Print / Save PDF</button>
            </div>
        </div>

        <form method="GET" action="{{ route('academicTeacher.resultsComparison') }}" class="mt-8 grid gap-4 rounded-2xl border border-sky-100 bg-sky-50 p-5 md:grid-cols-[1fr_1fr_auto] md:items-end">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Current Exam</label>
                <select name="current_exam_id" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200">
                    @foreach($exams as $exam)
                        <option value="{{ $exam->id }}" @selected($currentExam?->id === $exam->id)>{{ $exam->name }} - {{ $exam->term }} {{ $exam->year }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Compare Against</label>
                <select name="previous_exam_id" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200">
                    @foreach($exams as $exam)
                        <option value="{{ $exam->id }}" @selected($previousExam?->id === $exam->id)>{{ $exam->name }} - {{ $exam->term }} {{ $exam->year }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="rounded-full bg-sky-500 px-5 py-3 text-sm font-bold text-white shadow hover:bg-sky-600">Compare</button>
        </form>

        <div class="grid gap-4 mt-8 md:grid-cols-3">
            <div class="rounded-2xl border border-sky-100 bg-sky-50 p-5">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Current Avg</p>
                <p class="mt-2 text-3xl font-extrabold text-sky-950">{{ $schoolComparison['current'] ?? '-' }}</p>
            </div>
            <div class="rounded-2xl border border-sky-100 bg-sky-50 p-5">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Previous Avg</p>
                <p class="mt-2 text-3xl font-extrabold text-sky-950">{{ $schoolComparison['previous'] ?? '-' }}</p>
            </div>
            <div class="rounded-2xl border border-sky-100 bg-sky-50 p-5">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Movement</p>
                <p class="mt-2 text-3xl font-extrabold {{ ($schoolComparison['change'] ?? 0) >= 0 ? 'text-green-700' : 'text-red-700' }}">{{ $schoolComparison['change'] !== null ? ($schoolComparison['change'] > 0 ? '+' : '').$schoolComparison['change'] : '-' }}</p>
            </div>
        </div>

        <div class="grid gap-6 mt-8 lg:grid-cols-2">
            <div class="rounded-2xl border border-sky-100 bg-white p-5">
                <h2 class="text-lg font-extrabold text-sky-950">By Class</h2>
                <div class="mt-4 space-y-3">
                    @foreach($classComparisons as $item)
                        <div class="grid gap-2 rounded-xl border border-slate-100 bg-slate-50 px-4 py-3 sm:grid-cols-[1fr_repeat(3,auto)] sm:items-center">
                            <p class="font-bold text-slate-900">{{ $item['name'] }}</p>
                            <p class="text-sm font-semibold text-slate-600">Current: {{ $item['current'] ?? '-' }}</p>
                            <p class="text-sm font-semibold text-slate-600">Previous: {{ $item['previous'] ?? '-' }}</p>
                            <p class="text-sm font-bold {{ ($item['change'] ?? 0) >= 0 ? 'text-green-700' : 'text-red-700' }}">{{ $item['change'] !== null ? ($item['change'] > 0 ? '+' : '').$item['change'] : '-' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-sky-100 bg-white p-5">
                <h2 class="text-lg font-extrabold text-sky-950">By Subject</h2>
                <div class="mt-4 space-y-3">
                    @foreach($subjectComparisons as $item)
                        <div class="grid gap-2 rounded-xl border border-slate-100 bg-slate-50 px-4 py-3 sm:grid-cols-[1fr_repeat(3,auto)] sm:items-center">
                            <p class="font-bold text-slate-900">{{ $item['name'] }}</p>
                            <p class="text-sm font-semibold text-slate-600">Current: {{ $item['current'] ?? '-' }}</p>
                            <p class="text-sm font-semibold text-slate-600">Previous: {{ $item['previous'] ?? '-' }}</p>
                            <p class="text-sm font-bold {{ ($item['change'] ?? 0) >= 0 ? 'text-green-700' : 'text-red-700' }}">{{ $item['change'] !== null ? ($item['change'] > 0 ? '+' : '').$item['change'] : '-' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-8 rounded-2xl border border-sky-100 bg-white p-5">
            <h2 class="text-lg font-extrabold text-sky-950">Learner Movement</h2>
            <div class="mt-4 max-h-[520px] space-y-3 overflow-y-auto">
                @forelse($learnerComparisons as $item)
                    <div class="grid gap-2 rounded-xl border border-slate-100 bg-slate-50 px-4 py-3 md:grid-cols-[1fr_1fr_repeat(3,auto)] md:items-center">
                        <div>
                            <p class="font-bold text-slate-900">{{ $item['learner']->name }}</p>
                            <p class="text-sm text-slate-500">{{ $item['learner']->admission_number ?? 'No admission number' }}</p>
                        </div>
                        <p class="text-sm font-semibold text-sky-800">{{ $item['class_name'] }}</p>
                        <p class="text-sm font-semibold text-slate-600">Current: {{ $item['current'] ?? '-' }}</p>
                        <p class="text-sm font-semibold text-slate-600">Previous: {{ $item['previous'] ?? '-' }}</p>
                        <p class="text-sm font-bold {{ ($item['change'] ?? 0) >= 0 ? 'text-green-700' : 'text-red-700' }}">{{ $item['change'] !== null ? ($item['change'] > 0 ? '+' : '').$item['change'] : '-' }}</p>
                    </div>
                @empty
                    <p class="text-sm font-medium text-slate-500">No learners available for comparison.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
