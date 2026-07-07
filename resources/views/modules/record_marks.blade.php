@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="bg-white rounded-[30px] p-8 shadow-xl border border-sky-100">
        <h1 class="text-2xl font-extrabold text-sky-950 mb-3">Record Marks</h1>
        <p class="text-slate-600">Enter marks for {{ $user->teachingSubject->name ?? 'your subject' }} in your assigned classes.</p>

        <div class="mt-6 flex flex-wrap gap-2">
            @forelse($user->teachingClasses as $class)
                <span class="rounded-full bg-sky-50 border border-sky-100 px-3 py-1.5 text-sm font-bold text-sky-800">
                    {{ $class->name }}{{ $class->stream !== 'main' ? ' '.$class->stream : '' }}
                </span>
            @empty
                <span class="text-sm font-medium text-amber-700">No classes assigned.</span>
            @endforelse
        </div>
    </div>
</div>
@endsection
