@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="bg-white rounded-lg p-8 shadow space-y-6">
        <h1 class="text-2xl font-bold mb-4">Manage Official Templates</h1>
        <p class="text-slate-600">This page will store official government templates and control which template is used for result exports.</p>

        @if(session('status'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.templates.upload') }}" enctype="multipart/form-data" class="border border-slate-200 rounded-lg p-5 space-y-4">
            @csrf
            <div>
                <label for="template" class="block text-sm font-semibold text-slate-700 mb-2">Upload official template</label>
                <input id="template" name="template" type="file" accept=".pdf,.xlsx,.xls,.docx" required class="block w-full text-sm text-slate-700 border border-slate-200 rounded-lg p-3">
                <p class="text-xs text-slate-500 mt-2">Allowed files: PDF, Excel, or Word templates up to 10MB.</p>
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-sky-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-sky-700">
                Upload Template
            </button>
        </form>

        <div>
            <h2 class="text-lg font-bold text-slate-800 mb-3">Uploaded Templates</h2>

            @if($templates->isEmpty())
                <p class="text-sm text-slate-500">No official templates uploaded yet.</p>
            @else
                <div class="divide-y divide-slate-100 border border-slate-200 rounded-lg">
                    @foreach($templates as $template)
                        <div class="p-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="font-semibold text-slate-900">{{ $template['name'] }}</p>
                                <p class="text-sm text-slate-500">
                                    {{ number_format($template['size'] / 1024, 1) }} KB
                                    - uploaded {{ date('M d, Y H:i', $template['updated_at']) }}
                                </p>
                            </div>
                            <a href="{{ route('admin.templates.download', $template['name']) }}" class="inline-flex items-center justify-center px-4 py-2 bg-slate-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-900">
                                Download
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
