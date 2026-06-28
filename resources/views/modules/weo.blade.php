@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="bg-white rounded-lg p-8 shadow">
        <h1 class="text-2xl font-bold mb-4">WEO Module</h1>
        <p class="text-slate-600 mb-6">Monitor school submissions, compile ward results, and export reports using the official template.</p>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="{{ route('weo.wardSubmissions') }}" class="block p-6 bg-sky-50 rounded-lg border border-sky-100 hover:shadow">
                <h3 class="font-bold mb-2">School Submissions</h3>
                <p class="text-sm text-slate-600">See which schools have submitted verified results.</p>
            </a>
            <a href="{{ route('weo.wardCompilation') }}" class="block p-6 bg-sky-50 rounded-lg border border-sky-100 hover:shadow">
                <h3 class="font-bold mb-2">Ward Compilation</h3>
                <p class="text-sm text-slate-600">Compare school performance across the ward.</p>
            </a>
            <a href="{{ route('weo.templateExport') }}" class="block p-6 bg-sky-50 rounded-lg border border-sky-100 hover:shadow">
                <h3 class="font-bold mb-2">Template Export</h3>
                <p class="text-sm text-slate-600">Export compiled results into the unchanged government template.</p>
            </a>
        </div>
    </div>
</div>
@endsection
