@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="bg-white rounded-lg p-8 shadow">
        <h1 class="text-2xl font-bold mb-4">Head Teacher Module</h1>
        <p class="text-slate-600 mb-6">Verify school results before they are submitted to the WEO.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <a href="{{ route('headTeacher.verifyResults') }}" class="block p-6 bg-sky-50 rounded-lg border border-sky-100 hover:shadow">
                <h3 class="font-bold mb-2">Verify Results</h3>
                <p class="text-sm text-slate-600">Review compiled results and approve school submissions.</p>
            </a>
            <a href="{{ route('headTeacher.approvedReports') }}" class="block p-6 bg-sky-50 rounded-lg border border-sky-100 hover:shadow">
                <h3 class="font-bold mb-2">Approved Reports</h3>
                <p class="text-sm text-slate-600">Track reports already approved for WEO review.</p>
            </a>
        </div>
    </div>
</div>
@endsection
