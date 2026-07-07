@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="bg-white rounded-[30px] p-8 shadow-xl border border-sky-100">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <x-application-logo class="h-11 w-11" />
                    <span class="text-sm font-bold uppercase tracking-wider text-sky-500">Head Teacher Module</span>
                </div>
                <h1 class="text-3xl font-extrabold text-sky-950">Subjects and Classes</h1>
                <p class="text-slate-600 mt-2 max-w-2xl">Create the official subjects and classes teachers will choose from during assignment.</p>
            </div>

            <div class="rounded-2xl border border-sky-100 bg-sky-50 px-5 py-4 min-w-[240px]">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">School</p>
                <p class="mt-1 font-extrabold text-sky-950">{{ $user->school->name ?? 'School not assigned' }}</p>
                <p class="text-sm text-slate-500">{{ $user->school->ward ?? 'Ward not assigned' }}</p>
            </div>
        </div>

        @if(session('status'))
            <div class="mt-6 rounded-2xl border border-green-100 bg-green-50 p-4 text-sm font-semibold text-green-700">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mt-6 rounded-2xl border border-red-100 bg-red-50 p-4 text-sm font-semibold text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="grid gap-6 mt-8 lg:grid-cols-2">
            <form method="POST" action="{{ route('headTeacher.subjects.store') }}" class="rounded-2xl border border-sky-100 bg-sky-50 p-5">
                @csrf
                <h2 class="text-lg font-extrabold text-sky-950">Add Subject</h2>
                <div class="mt-4 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Subject Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-800 outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200" placeholder="Mathematics">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Subject Code</label>
                        <input type="text" name="code" value="{{ old('code') }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-800 outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200" placeholder="MATH">
                    </div>
                    <button type="submit" class="rounded-full bg-sky-500 px-5 py-3 text-sm font-bold text-white shadow hover:bg-sky-600">Save Subject</button>
                </div>
            </form>

            <form method="POST" action="{{ route('headTeacher.classes.store') }}" class="rounded-2xl border border-sky-100 bg-sky-50 p-5">
                @csrf
                <h2 class="text-lg font-extrabold text-sky-950">Add Class</h2>
                <div class="mt-4 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Class Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-800 outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200" placeholder="Standard 4">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Stream</label>
                        <input type="text" name="stream" value="{{ old('stream') }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-800 outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200" placeholder="A">
                    </div>
                    <button type="submit" class="rounded-full bg-sky-500 px-5 py-3 text-sm font-bold text-white shadow hover:bg-sky-600">Save Class</button>
                </div>
            </form>
        </div>

        <div class="grid gap-6 mt-8 lg:grid-cols-2">
            <div class="rounded-2xl border border-sky-100 bg-white p-5">
                <h2 class="text-lg font-extrabold text-sky-950">Subjects</h2>
                <div class="mt-4 space-y-2">
                    @forelse($subjects as $subject)
                        <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                            <span class="font-semibold text-slate-800">{{ $subject->name }}</span>
                            <span class="text-sm font-medium text-slate-500">{{ $subject->code ?? 'No code' }}</span>
                        </div>
                    @empty
                        <p class="text-sm font-medium text-slate-500">No subjects have been added yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border border-sky-100 bg-white p-5">
                <h2 class="text-lg font-extrabold text-sky-950">Classes</h2>
                <div class="mt-4 space-y-2">
                    @forelse($classes as $class)
                        <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                            <span class="font-semibold text-slate-800">{{ $class->name }}{{ $class->stream !== 'main' ? ' '.$class->stream : '' }}</span>
                            <span class="text-sm font-medium text-slate-500">{{ $class->learners_count }} learners</span>
                        </div>
                    @empty
                        <p class="text-sm font-medium text-slate-500">No classes have been added for this school yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
