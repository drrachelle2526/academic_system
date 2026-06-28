@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="bg-white rounded-lg p-8 shadow">
        <h1 class="text-2xl font-bold mb-4">System Administrator Module</h1>
        <p class="text-slate-600 mb-6">Manage system setup, schools, users, and official reporting templates.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <a href="{{ route('admin.schools') }}" class="block p-6 bg-sky-50 rounded-lg border border-sky-100 hover:shadow">
                <h3 class="font-bold mb-2">Schools and Wards</h3>
                <p class="text-sm text-slate-600">Maintain the official school and ward list used for approvals.</p>
            </a>
            <a href="{{ route('admin.templates') }}" class="block p-6 bg-sky-50 rounded-lg border border-sky-100 hover:shadow">
                <h3 class="font-bold mb-2">Official Templates</h3>
                <p class="text-sm text-slate-600">Store government templates for export without changing their format.</p>
            </a>
        </div>
    </div>
</div>
@endsection
