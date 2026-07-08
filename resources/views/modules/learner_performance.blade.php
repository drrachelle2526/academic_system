@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="bg-white rounded-[30px] p-8 shadow-xl border border-sky-100">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-sky-950">Learner Performance</h1>
                <p class="text-slate-600 mt-2 max-w-2xl">Per-learner totals, averages, and subject marks for the current exam.</p>
            </div>
            <button type="button" onclick="window.print()" class="rounded-full bg-white px-5 py-3 text-sm font-bold text-sky-700 shadow-sm ring-1 ring-sky-100 hover:bg-sky-50">Print / Save PDF</button>
        </div>

        <div class="mt-8 space-y-4">
            @forelse($learnerPerformance as $item)
                <div class="rounded-2xl border border-sky-100 bg-white p-5">
                    <div class="grid gap-3 lg:grid-cols-[1fr_repeat(4,auto)] lg:items-center">
                        <div>
                            <p class="font-extrabold text-slate-900">{{ $item['learner']->name }}</p>
                            <p class="text-sm text-slate-500">{{ $item['learner']->schoolClass?->name ?? 'No class' }}{{ $item['learner']->schoolClass && $item['learner']->schoolClass->stream !== 'main' ? ' '.$item['learner']->schoolClass->stream : '' }}</p>
                        </div>
                        <p class="text-sm font-bold text-sky-800">Total: {{ $item['total'] }}</p>
                        <p class="text-sm font-bold text-sky-800">Average: {{ $item['average'] ?? '-' }}</p>
                        <p class="text-sm font-bold text-green-700">{{ $item['submitted'] }} submitted</p>
                        <p class="text-sm font-bold {{ $item['missing'] > 0 ? 'text-amber-700' : 'text-green-700' }}">{{ $item['missing'] }} missing</p>
                    </div>
                    <div class="mt-4 grid gap-2 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach($item['marks'] as $subjectMark)
                            <div class="rounded-xl border border-slate-100 bg-slate-50 px-3 py-2">
                                <p class="text-xs font-bold uppercase tracking-wide text-slate-500">{{ $subjectMark['subject']->name }}</p>
                                <p class="mt-1 font-extrabold text-slate-900">{{ $subjectMark['mark']?->score ?? '-' }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <p class="text-sm font-medium text-slate-500">No learners registered yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
