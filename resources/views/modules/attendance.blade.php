@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="bg-white rounded-[30px] p-8 shadow-xl border border-sky-100">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h1 class="text-2xl font-extrabold text-sky-950 mb-3">Attendance</h1>
                <p class="text-slate-600">Record daily attendance only for your assigned classes.</p>
            </div>
            <form method="GET" action="{{ route('teacher.attendance') }}" class="flex flex-col gap-3 sm:flex-row">
                <input type="date" name="date" value="{{ $date }}" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200">
                <select name="school_class_id" onchange="this.form.submit()" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200">
                    @foreach($user->teachingClasses as $class)
                        <option value="{{ $class->id }}" @selected($selectedClass?->id === $class->id)>{{ $class->name }}{{ $class->stream !== 'main' ? ' '.$class->stream : '' }}</option>
                    @endforeach
                </select>
                <button type="submit" class="rounded-full bg-white px-5 py-3 text-sm font-bold text-sky-700 shadow-sm ring-1 ring-sky-100 hover:bg-sky-50">Load</button>
            </form>
        </div>

        @if(session('status'))
            <div class="mt-6 rounded-2xl border border-green-100 bg-green-50 p-4 text-sm font-semibold text-green-700">{{ session('status') }}</div>
        @endif

        @if($errors->any())
            <div class="mt-6 rounded-2xl border border-red-100 bg-red-50 p-4 text-sm font-semibold text-red-700">{{ $errors->first() }}</div>
        @endif

        @if(!$selectedClass)
            <p class="mt-6 text-sm font-semibold text-amber-700">No classes are assigned to your account yet.</p>
        @elseif($learners->isEmpty())
            <p class="mt-6 text-sm font-semibold text-amber-700">No learners registered in this class yet.</p>
        @else
            <form method="POST" action="{{ route('teacher.attendance.store') }}" class="mt-6">
                @csrf
                <input type="hidden" name="school_class_id" value="{{ $selectedClass->id }}">
                <input type="hidden" name="attendance_date" value="{{ $date }}">
                <div class="overflow-hidden rounded-2xl border border-sky-100">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-sky-50 text-xs font-bold uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-4 py-3">Learner</th>
                                <th class="px-4 py-3">Admission No.</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($learners as $learner)
                                @php($attendance = $attendances->get($learner->id))
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-slate-800">{{ $learner->name }}</td>
                                    <td class="px-4 py-3 text-slate-500">{{ $learner->admission_number ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <select name="statuses[{{ $learner->id }}]" class="rounded-xl border border-slate-200 px-3 py-2 text-sm outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200">
                                            <option value="present" @selected(($attendance?->status ?? 'present') === 'present')>Present</option>
                                            <option value="absent" @selected($attendance?->status === 'absent')>Absent</option>
                                            <option value="late" @selected($attendance?->status === 'late')>Late</option>
                                            <option value="excused" @selected($attendance?->status === 'excused')>Excused</option>
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="mt-5 rounded-full bg-sky-500 px-5 py-3 text-sm font-bold text-white shadow hover:bg-sky-600">Save Attendance</button>
            </form>
        @endif
    </div>
</div>
@endsection
