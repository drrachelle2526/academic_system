<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'EduMonitor') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-[#dff3ff]">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow-sm border-b border-sky-100">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            @auth
                <div class="bg-sky-50/80 border-b border-sky-100">
                    <div class="max-w-7xl mx-auto px-4 py-3 sm:px-6 lg:px-8">
                        <div class="flex flex-wrap items-center gap-2">
                            <button type="button" onclick="history.back()" class="rounded-full bg-white px-4 py-2 text-sm font-bold text-sky-800 shadow-sm ring-1 ring-sky-100 hover:bg-sky-50">
                                Back
                            </button>
                            <a href="{{ route('dashboard') }}" class="rounded-full bg-white px-4 py-2 text-sm font-bold text-sky-800 shadow-sm ring-1 ring-sky-100 hover:bg-sky-50">
                                Dashboard
                            </a>
                            @if(Auth::user()->role === 'teacher')
                                <a href="{{ route('module.teacher') }}" class="rounded-full bg-sky-500 px-4 py-2 text-sm font-bold text-white shadow hover:bg-sky-600">Teacher Module</a>
                            @elseif(Auth::user()->role === 'academic_teacher' && Auth::user()->role_status === 'approved')
                                <a href="{{ route('module.academicTeacher') }}" class="rounded-full bg-sky-500 px-4 py-2 text-sm font-bold text-white shadow hover:bg-sky-600">Academic Module</a>
                            @elseif(Auth::user()->role === 'headmaster' && Auth::user()->role_status === 'approved')
                                <a href="{{ route('module.headTeacher') }}" class="rounded-full bg-sky-500 px-4 py-2 text-sm font-bold text-white shadow hover:bg-sky-600">Head Teacher Module</a>
                            @elseif(Auth::user()->role === 'weo' && Auth::user()->role_status === 'approved')
                                <a href="{{ route('module.weo') }}" class="rounded-full bg-sky-500 px-4 py-2 text-sm font-bold text-white shadow hover:bg-sky-600">WEO Module</a>
                            @elseif(Auth::user()->role === 'admin' && Auth::user()->role_status === 'approved')
                                <a href="{{ route('module.admin') }}" class="rounded-full bg-sky-500 px-4 py-2 text-sm font-bold text-white shadow hover:bg-sky-600">Admin Module</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endauth

            <!-- Page Content -->
            <main>
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </main>
        </div>
    </body>
</html>
