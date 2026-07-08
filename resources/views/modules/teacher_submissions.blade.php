@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="bg-white rounded-[30px] p-8 shadow-xl border border-sky-100">
        <h1 class="text-3xl font-extrabold text-sky-950">Teacher Submissions</h1>
        <p class="text-slate-600 mt-2 max-w-2xl">Track each teacher's subject, assigned classes, and mark completion for the current exam.</p>

        <div class="mt-8 rounded-2xl border border-sky-100 bg-white p-5">
            <div class="space-y-3">
                @forelse($teacherSubmissions as $item)
                    <div class="grid gap-3 rounded-xl border border-slate-100 bg-slate-50 px-4 py-3 lg:grid-cols-[1fr_1fr_1.5fr_repeat(3,auto)] lg:items-center">
                        <div>
                            <p class="font-bold text-slate-900">{{ $item['teacher']->name }}</p>
                            <p class="text-sm text-slate-500">{{ ucwords(str_replace('_', ' ', $item['teacher']->role)) }}</p>
                        </div>
                        <p class="text-sm font-semibold text-slate-700">{{ $item['subject']->name ?? 'No subject' }}</p>
                        <div class="flex flex-wrap gap-2">
                            @forelse($item['classes'] as $class)
                                <span class="rounded-full bg-white px-3 py-1 text-xs font-bold text-sky-800">{{ $class->name }}{{ $class->stream !== 'main' ? ' '.$class->stream : '' }}</span>
                            @empty
                                <span class="text-sm text-slate-500">No classes</span>
                            @endforelse
                        </div>
                        <p class="text-sm font-bold text-green-700">{{ $item['submitted'] }} submitted</p>
                        <p class="text-sm font-bold {{ $item['missing'] > 0 ? 'text-amber-700' : 'text-green-700' }}">{{ $item['missing'] }} missing</p>
                        <p class="text-sm font-bold text-sky-800">{{ $item['completion'] }}%</p>
                    </div>
                @empty
                    <p class="text-sm font-medium text-slate-500">No approved teachers found.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
