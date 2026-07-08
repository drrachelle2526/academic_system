@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="bg-white rounded-[30px] p-8 shadow-xl border border-sky-100">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h1 class="text-2xl font-extrabold text-sky-950 mb-3">Record Marks</h1>
                <p class="text-slate-600">Enter marks for {{ $user->teachingSubject->name ?? 'your subject' }} in your assigned classes.</p>
            </div>
            <form method="GET" action="{{ route('teacher.recordMarks') }}">
                <select name="school_class_id" onchange="this.form.submit()" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200">
                    @foreach($user->teachingClasses as $class)
                        <option value="{{ $class->id }}" @selected($selectedClass?->id === $class->id)>{{ $class->name }}{{ $class->stream !== 'main' ? ' '.$class->stream : '' }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        @if(session('status'))
            <div class="mt-6 rounded-2xl border border-green-100 bg-green-50 p-4 text-sm font-semibold text-green-700">{{ session('status') }}</div>
        @endif

        @if($errors->any())
            <div class="mt-6 rounded-2xl border border-red-100 bg-red-50 p-4 text-sm font-semibold text-red-700">{{ $errors->first() }}</div>
        @endif

        @if(!$user->teachingSubject)
            <p class="mt-6 text-sm font-semibold text-amber-700">Choose your teaching subject first from the Teacher Dashboard.</p>
        @elseif(!$exam)
            <p class="mt-6 text-sm font-semibold text-amber-700">No current exam has been set. Ask the Head Teacher to create the exam first.</p>
        @elseif(!$selectedClass)
            <p class="mt-6 text-sm font-semibold text-amber-700">No classes are assigned to your account yet.</p>
        @elseif($learners->isEmpty())
            <p class="mt-6 text-sm font-semibold text-amber-700">No learners registered in this class yet.</p>
        @else
            <form method="POST" action="{{ route('teacher.recordMarks.store') }}" class="mt-6">
                @csrf
                <input type="hidden" name="school_class_id" value="{{ $selectedClass->id }}">
                <div class="overflow-hidden rounded-2xl border border-sky-100">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-sky-50 text-xs font-bold uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-4 py-3">Learner</th>
                                <th class="px-4 py-3">Admission No.</th>
                                <th class="px-4 py-3">Score</th>
                                <th class="px-4 py-3">Current Grade</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($learners as $learner)
                                @php($mark = $marks->get($learner->id))
                                <tr>
                                    <td class="px-4 py-3 font-semibold text-slate-800">{{ $learner->name }}</td>
                                    <td class="px-4 py-3 text-slate-500">{{ $learner->admission_number ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <input type="number" name="scores[{{ $learner->id }}]" value="{{ old("scores.{$learner->id}", $mark?->score) }}" min="0" max="100" step="0.01" class="w-28 rounded-xl border border-slate-200 px-3 py-2 text-sm outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200">
                                    </td>
                                    <td class="px-4 py-3 font-bold text-sky-800">{{ $mark?->grade ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="mt-5 rounded-full bg-sky-500 px-5 py-3 text-sm font-bold text-white shadow hover:bg-sky-600">Save Marks</button>
            </form>
        @endif
    </div>
</div>
@endsection
