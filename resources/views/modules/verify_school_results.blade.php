@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="bg-white rounded-[30px] p-8 shadow-xl border border-sky-100">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-sky-950">Verify School Results</h1>
                <p class="text-slate-600 mt-2 max-w-2xl">Review compilations submitted by the Academic Teacher before school results move forward.</p>
            </div>
            <div class="rounded-2xl border border-sky-100 bg-sky-50 px-5 py-4 min-w-[220px]">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Submitted Reports</p>
                <p class="mt-1 text-3xl font-extrabold text-sky-950">{{ $submissions->count() }}</p>
            </div>
        </div>

        <div class="mt-8 space-y-3">
            @forelse($submissions as $submission)
                <div class="grid gap-3 rounded-2xl border border-sky-100 bg-sky-50 px-5 py-4 md:grid-cols-[1fr_auto_auto] md:items-center">
                    <div>
                        <p class="font-extrabold text-slate-900">{{ $submission->exam?->name ?? 'No exam' }}</p>
                        <p class="text-sm text-slate-600">{{ $submission->exam ? $submission->exam->term.' '.$submission->exam->year : 'Exam missing' }}</p>
                        <p class="text-sm text-slate-500">Submitted {{ $submission->submitted_at?->format('Y-m-d H:i') ?? 'not submitted' }}</p>
                    </div>
                    <span class="rounded-full bg-white px-4 py-2 text-sm font-bold text-sky-800">{{ ucwords($submission->status) }}</span>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('headTeacher.resultsRanking') }}" class="rounded-full bg-white px-4 py-2 text-sm font-bold text-sky-700 shadow-sm ring-1 ring-sky-100 hover:bg-sky-50">Ranking</a>
                        <a href="{{ route('headTeacher.resultsRanking.download') }}" class="rounded-full bg-sky-500 px-4 py-2 text-sm font-bold text-white shadow hover:bg-sky-600">Download</a>
                    </div>
                </div>
            @empty
                <p class="rounded-2xl border border-slate-100 bg-slate-50 p-5 text-sm font-medium text-slate-500">No school results have been submitted by the Academic Teacher yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
