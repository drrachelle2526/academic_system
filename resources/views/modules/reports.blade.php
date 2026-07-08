@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="bg-white rounded-[30px] p-8 shadow-xl border border-sky-100">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-sky-950">Teacher Report</h1>
                <p class="text-slate-600 mt-2 max-w-2xl">
                    Summary for {{ $user->teachingSubject->name ?? 'your selected subject' }} across only your assigned classes.
                </p>
            </div>
            <div class="rounded-2xl border border-sky-100 bg-sky-50 px-5 py-4 min-w-[220px]">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Current Exam</p>
                <p class="mt-1 font-extrabold text-sky-950">{{ $exam?->name ?? 'No exam set' }}</p>
                <p class="text-sm text-slate-500">{{ $exam ? $exam->term.' '.$exam->year : 'Marks cannot compile yet' }}</p>
            </div>
        </div>

        <form method="GET" action="{{ route('teacher.reports') }}" class="mt-8 grid gap-4 rounded-2xl border border-sky-100 bg-sky-50 p-5 lg:grid-cols-[1fr_1fr_auto_auto] lg:items-end">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Class</label>
                <select name="school_class_id" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200">
                    <option value="">All assigned classes</option>
                    @foreach($user->teachingClasses as $class)
                        <option value="{{ $class->id }}" @selected($selectedClass?->id === $class->id)>{{ $class->name }}{{ $class->stream !== 'main' ? ' '.$class->stream : '' }}</option>
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
                <a href="{{ route('teacher.reports.download').'?'.http_build_query(request()->only(['school_class_id', 'search'])) }}" class="rounded-full bg-white px-5 py-3 text-sm font-bold text-sky-700 shadow-sm ring-1 ring-sky-100 hover:bg-sky-50">Download</a>
            </div>
        </form>

        <div class="grid gap-4 mt-8 md:grid-cols-4">
            <div class="rounded-2xl border border-sky-100 bg-sky-50 p-5">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Expected Marks</p>
                <p class="mt-2 text-3xl font-extrabold text-sky-950">{{ $expected }}</p>
            </div>
            <div class="rounded-2xl border border-sky-100 bg-sky-50 p-5">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Submitted</p>
                <p class="mt-2 text-3xl font-extrabold text-green-700">{{ $submitted }}</p>
            </div>
            <div class="rounded-2xl border border-sky-100 bg-sky-50 p-5">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Unentered Marks</p>
                <p class="mt-2 text-3xl font-extrabold {{ $missing > 0 ? 'text-amber-700' : 'text-green-700' }}">{{ $missing }}</p>
            </div>
            <div class="rounded-2xl border border-sky-100 bg-sky-50 p-5">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Average</p>
                <p class="mt-2 text-3xl font-extrabold text-sky-950">{{ $average ? round($average, 1) : '-' }}</p>
            </div>
        </div>

        <div class="mt-8 overflow-x-auto rounded-2xl border border-sky-100">
            <table class="w-full min-w-[720px] text-left text-sm">
                <thead class="bg-sky-50 text-xs font-bold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Pos</th>
                        <th class="px-4 py-3">Learner</th>
                        <th class="px-4 py-3">Admission No.</th>
                        <th class="px-4 py-3">Class</th>
                        <th class="px-4 py-3">Subject</th>
                        <th class="px-4 py-3">Score</th>
                        <th class="px-4 py-3">Grade</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($learnerResults as $item)
                        <tr>
                            <td class="px-4 py-3 text-lg font-extrabold text-sky-800">{{ $item['position'] }}</td>
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $item['learner']->name }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $item['learner']->admission_number ?? '-' }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $item['class_name'] }}</td>
                            <td class="px-4 py-3 text-slate-700">{{ $user->teachingSubject->name ?? 'No subject' }}</td>
                            <td class="px-4 py-3 font-bold text-slate-900">{{ $item['mark']?->score ?? '-' }}</td>
                            <td class="px-4 py-3 font-bold text-sky-800">{{ $item['mark']?->grade ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-5 text-slate-500">No learners found for this filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-8 rounded-2xl border border-sky-100 bg-white p-5">
            <h2 class="text-lg font-extrabold text-sky-950">Class Breakdown</h2>
            <div class="mt-4 space-y-3">
                @forelse($classReports as $report)
                    <div class="grid gap-3 rounded-xl border border-slate-100 bg-slate-50 px-4 py-3 md:grid-cols-[1fr_repeat(5,auto)] md:items-center">
                        <div>
                            <p class="font-bold text-slate-900">{{ $report['class']->name }}{{ $report['class']->stream !== 'main' ? ' '.$report['class']->stream : '' }}</p>
                            <p class="text-sm text-slate-500">{{ $report['learners_count'] }} learners</p>
                        </div>
                        <p class="text-sm font-semibold text-slate-700">Submitted: {{ $report['submitted'] }}</p>
                        <p class="text-sm font-semibold {{ $report['missing'] > 0 ? 'text-amber-700' : 'text-green-700' }}">Unentered: {{ $report['missing'] }}</p>
                        <p class="text-sm font-semibold text-sky-800">Avg: {{ $report['average'] ?? '-' }}</p>
                        <p class="text-sm font-semibold text-slate-600">High: {{ $report['highest'] ?? '-' }}</p>
                        <p class="text-sm font-semibold text-slate-600">Low: {{ $report['lowest'] ?? '-' }}</p>
                    </div>
                @empty
                    <p class="text-sm font-medium text-slate-500">No classes are assigned to your account yet.</p>
                @endforelse
            </div>
        </div>

        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('teacher.recordMarks') }}" class="rounded-full bg-sky-500 px-5 py-3 text-sm font-bold text-white shadow hover:bg-sky-600">Record Marks</a>
            <a href="{{ route('teacher.classlists') }}" class="rounded-full bg-white px-5 py-3 text-sm font-bold text-sky-700 shadow-sm ring-1 ring-sky-100 hover:bg-sky-50">Class Lists</a>
        </div>
    </div>
</div>
@endsection
