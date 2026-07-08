@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="bg-white rounded-[30px] p-8 shadow-xl border border-sky-100">
        <h1 class="text-3xl font-extrabold text-sky-950">Learner Register</h1>
        <p class="text-slate-600 mt-2 max-w-2xl">Read-only learner list for academic checking.</p>

        <form method="GET" action="{{ route('academicTeacher.learnerRegister') }}" class="mt-8 grid gap-4 rounded-2xl border border-sky-100 bg-sky-50 p-5 sm:grid-cols-[1fr_auto_auto] sm:items-center">
            <input type="search" name="learner_search" value="{{ $learnerSearch }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200" placeholder="Search name, admission number, class, stream, gender">
            <button type="submit" class="rounded-full bg-sky-500 px-5 py-3 text-sm font-bold text-white shadow hover:bg-sky-600">Search</button>
            <div class="flex gap-2">
                <button type="button" onclick="window.print()" class="rounded-full bg-white px-5 py-3 text-sm font-bold text-sky-700 shadow-sm ring-1 ring-sky-100 hover:bg-sky-50">Print</button>
                <a href="{{ route('academicTeacher.learnerRegister') }}" class="rounded-full bg-white px-5 py-3 text-sm font-bold text-sky-700 shadow-sm ring-1 ring-sky-100 hover:bg-sky-50">Reset</a>
            </div>
        </form>

        <div class="mt-8 overflow-hidden rounded-2xl border border-sky-100">
            <table class="w-full text-left text-sm">
                <thead class="bg-sky-50 text-xs font-bold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Learner</th>
                        <th class="px-4 py-3">Admission No.</th>
                        <th class="px-4 py-3">Class</th>
                        <th class="px-4 py-3">Gender</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($learners as $learner)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-slate-800">{{ $learner->name }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $learner->admission_number ?? '-' }}</td>
                            <td class="px-4 py-3 text-sky-800 font-bold">{{ $learner->schoolClass?->name ?? 'No class' }}{{ $learner->schoolClass && $learner->schoolClass->stream !== 'main' ? ' '.$learner->schoolClass->stream : '' }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $learner->gender ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-5 text-slate-500">No learners registered yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
