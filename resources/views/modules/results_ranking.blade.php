@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="bg-white rounded-[30px] p-8 shadow-xl border border-sky-100">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-sky-950">Class Results Ranking</h1>
                <p class="text-slate-600 mt-2 max-w-2xl">
                    Position learners from first to last by total marks, search any learner, then print or download the results.
                </p>
            </div>
            <div class="rounded-2xl border border-sky-100 bg-sky-50 px-5 py-4 min-w-[240px]">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Current Exam</p>
                <p class="mt-1 font-extrabold text-sky-950">{{ $exam?->name ?? 'No exam set' }}</p>
                <p class="text-sm text-slate-500">{{ $exam ? $exam->term.' '.$exam->year : 'Set exam first' }}</p>
            </div>
        </div>

        <form method="GET" action="{{ request()->routeIs('headTeacher.*') ? route('headTeacher.resultsRanking') : route('academicTeacher.resultsRanking') }}" class="mt-8 grid gap-4 rounded-2xl border border-sky-100 bg-sky-50 p-5 lg:grid-cols-[1fr_1fr_auto_auto] lg:items-end">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Class</label>
                <select name="school_class_id" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200">
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" @selected($selectedClass?->id === $class->id)>{{ $class->name }}{{ $class->stream !== 'main' ? ' '.$class->stream : '' }} ({{ $class->learners_count }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Search Learner</label>
                <input type="search" name="search" value="{{ $search }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200" placeholder="Name or admission number">
            </div>
            <button type="submit" class="rounded-full bg-sky-500 px-5 py-3 text-sm font-bold text-white shadow hover:bg-sky-600">Search</button>
            <div class="flex gap-2">
                <button type="button" onclick="window.print()" class="rounded-full bg-white px-5 py-3 text-sm font-bold text-sky-700 shadow-sm ring-1 ring-sky-100 hover:bg-sky-50">Print</button>
                <a href="{{ (request()->routeIs('headTeacher.*') ? route('headTeacher.resultsRanking.download') : route('academicTeacher.resultsRanking.download')).'?'.http_build_query(request()->only(['school_class_id', 'search'])) }}" class="rounded-full bg-white px-5 py-3 text-sm font-bold text-sky-700 shadow-sm ring-1 ring-sky-100 hover:bg-sky-50">Download</a>
            </div>
        </form>

        <div class="mt-8 overflow-x-auto rounded-2xl border border-sky-100 print:overflow-visible">
            <table class="w-full min-w-[980px] text-left text-sm print:min-w-0">
                <thead class="bg-sky-50 text-xs font-bold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Pos</th>
                        <th class="px-4 py-3">Learner</th>
                        <th class="px-4 py-3">Adm No.</th>
                        <th class="px-4 py-3">Class</th>
                        @foreach($subjects as $subject)
                            <th class="px-4 py-3">{{ $subject->name }}</th>
                        @endforeach
                        <th class="px-4 py-3">Total</th>
                        <th class="px-4 py-3">Avg</th>
                        <th class="px-4 py-3">Missing</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($rankedLearners as $item)
                        <tr>
                            <td class="px-4 py-3 text-lg font-extrabold text-sky-800">{{ $item['position'] }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $item['learner']->name }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $item['learner']->admission_number ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $item['class_name'] }}</td>
                            @foreach($item['subject_marks'] as $mark)
                                <td class="px-4 py-3 font-semibold text-slate-700">{{ $mark?->score ?? '-' }}</td>
                            @endforeach
                            <td class="px-4 py-3 font-extrabold text-slate-900">{{ $item['total'] }}</td>
                            <td class="px-4 py-3 font-bold text-sky-800">{{ $item['average'] ?? '-' }}</td>
                            <td class="px-4 py-3 font-bold {{ $item['missing'] > 0 ? 'text-amber-700' : 'text-green-700' }}">{{ $item['missing'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 8 + $subjects->count() }}" class="px-4 py-5 text-slate-500">No learners found for this class/search.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
