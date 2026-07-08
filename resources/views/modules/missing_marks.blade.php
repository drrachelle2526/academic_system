@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="bg-white rounded-[30px] p-8 shadow-xl border border-sky-100">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-sky-950">Missing Marks Check</h1>
                <p class="text-slate-600 mt-2 max-w-2xl">Review incomplete learner-subject marks before school results are sent to the Head Teacher.</p>
            </div>
            <div class="rounded-2xl border border-sky-100 bg-sky-50 px-5 py-4 min-w-[220px]">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Missing</p>
                <p class="mt-1 text-3xl font-extrabold {{ $missingMarksCount > 0 ? 'text-amber-700' : 'text-green-700' }}">{{ $missingMarksCount }}</p>
            </div>
        </div>

        <div class="grid gap-6 mt-8 lg:grid-cols-2">
            <div class="rounded-2xl border border-sky-100 bg-white p-5">
                <h2 class="text-lg font-extrabold text-sky-950">By Class</h2>
                <div class="mt-4 space-y-3">
                    @foreach($classSummaries as $class)
                        <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                            <div>
                                <p class="font-bold text-slate-900">{{ $class['name'] }}</p>
                                <p class="text-sm text-slate-500">{{ $class['submitted'] }} of {{ $class['expected'] }} submitted</p>
                            </div>
                            <span class="rounded-full {{ $class['missing'] > 0 ? 'bg-amber-100 text-amber-800' : 'bg-green-100 text-green-800' }} px-3 py-1 text-sm font-bold">{{ $class['missing'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-sky-100 bg-white p-5">
                <h2 class="text-lg font-extrabold text-sky-950">By Subject</h2>
                <div class="mt-4 space-y-3">
                    @foreach($subjectSummaries as $subject)
                        <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                            <div>
                                <p class="font-bold text-slate-900">{{ $subject['name'] }}</p>
                                <p class="text-sm text-slate-500">{{ $subject['submitted'] }} of {{ $subject['expected'] }} submitted</p>
                            </div>
                            <span class="rounded-full {{ $subject['missing'] > 0 ? 'bg-amber-100 text-amber-800' : 'bg-green-100 text-green-800' }} px-3 py-1 text-sm font-bold">{{ $subject['missing'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-8 rounded-2xl border border-sky-100 bg-white p-5">
            <h2 class="text-lg font-extrabold text-sky-950">Missing Learner Marks</h2>
            <div class="mt-4 divide-y divide-slate-100">
                @forelse($missingSamples as $item)
                    <div class="grid gap-2 py-3 md:grid-cols-[1.3fr_1fr_1fr] md:items-center">
                        <p class="font-bold text-slate-900">{{ $item['learner']->name }}</p>
                        <p class="text-sm text-slate-600">{{ $item['learner']->schoolClass?->name ?? 'No class' }}</p>
                        <p class="text-sm font-semibold text-sky-800">{{ $item['subject']->name }}</p>
                    </div>
                @empty
                    <p class="text-sm font-medium text-green-700">All expected marks are complete.</p>
                @endforelse
            </div>
            @if($missingMarksCount > $missingSamples->count())
                <p class="mt-4 text-sm text-slate-500">Showing first {{ $missingSamples->count() }} missing marks.</p>
            @endif
        </div>
    </div>
</div>
@endsection
