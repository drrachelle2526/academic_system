<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-xl text-sky-950 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(Auth::user()->role === 'academic_teacher' && Auth::user()->role_status === 'pending')
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded shadow-sm" role="alert">
                    <p class="font-bold">Account Verification Pending</p>
                    <p class="text-sm">You are currently logged in with standard teacher permissions. Your request for Academic Teacher access is waiting for Headmaster verification.</p>
                </div>
            @endif

            @if(Auth::user()->role === 'teacher' && Auth::user()->role_status === 'pending')
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded shadow-sm" role="alert">
                    <p class="font-bold">Account Verification Pending</p>
                    <p class="text-sm">Your teacher registration is waiting for Head Teacher approval.</p>
                </div>
            @endif

            @if(Auth::user()->role === 'headmaster' && Auth::user()->role_status === 'pending')
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded shadow-sm" role="alert">
                    <p class="font-bold">Account Verification Pending</p>
                    <p class="text-sm">Your request for Head Teacher access is waiting for WEO verification.</p>
                </div>
            @endif

            @if(Auth::user()->role === 'weo' && Auth::user()->role_status === 'pending')
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded shadow-sm" role="alert">
                    <p class="font-bold">Account Verification Pending</p>
                    <p class="text-sm">Your request for WEO access is waiting for System Administrator verification.</p>
                </div>
            @endif

            @if(session('status'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                    <p class="font-bold">Success</p>
                    <p class="text-sm">{{ session('status') }}</p>
                </div>
            @endif

            @if(Auth::user()->role === 'teacher')
                <div class="bg-white border border-sky-100 shadow-xl rounded-[30px] overflow-hidden">
                    <div class="p-6 md:p-8">
                        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                            <div>
                                <div class="flex items-center gap-3 mb-4">
                                    <x-application-logo class="h-11 w-11" />
                                    <div>
                                        <p class="text-sm font-bold uppercase tracking-wider text-sky-500">Teacher Dashboard</p>
                                        <h3 class="text-2xl font-extrabold text-sky-950">Welcome, {{ Auth::user()->name }}</h3>
                                    </div>
                                </div>
                                <p class="text-slate-600 max-w-2xl">
                                    Your dashboard shows the official subject and classes assigned from your school setup. Use the quick actions below to work only with your assigned classes.
                                </p>
                            </div>

                            <div class="rounded-2xl border border-sky-100 bg-sky-50 px-5 py-4 min-w-[220px]">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Approval Status</p>
                                <p class="mt-1 text-lg font-extrabold {{ Auth::user()->role_status === 'approved' ? 'text-green-700' : 'text-amber-700' }}">
                                    {{ ucwords(Auth::user()->role_status) }}
                                </p>
                                <p class="text-sm text-slate-500">{{ Auth::user()->school->name ?? 'School not selected' }}</p>
                            </div>
                        </div>

                        <div class="grid gap-4 mt-8 md:grid-cols-3">
                            <div class="rounded-2xl border border-sky-100 bg-sky-50 p-5">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Subject</p>
                                <p class="mt-2 text-xl font-extrabold text-sky-950">{{ Auth::user()->teachingSubject->name ?? 'Not selected' }}</p>
                            </div>

                            <div class="rounded-2xl border border-sky-100 bg-sky-50 p-5 md:col-span-2">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Assigned Classes</p>
                                @if(Auth::user()->teachingClasses->isNotEmpty())
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        @foreach(Auth::user()->teachingClasses as $class)
                                            <span class="rounded-full bg-white border border-sky-100 px-3 py-1.5 text-sm font-bold text-sky-800">
                                                {{ $class->name }}{{ $class->stream !== 'main' ? ' '.$class->stream : '' }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="mt-2 text-sm font-medium text-amber-700">No classes selected yet.</p>
                                @endif
                            </div>
                        </div>

                        <div class="grid gap-4 mt-8 sm:grid-cols-2 lg:grid-cols-4">
                            <a href="{{ route('teacher.classlists') }}" class="rounded-2xl border border-sky-100 bg-white p-5 shadow-sm hover:shadow-md transition-shadow">
                                <p class="font-extrabold text-sky-950">Class Lists</p>
                                <p class="text-sm text-slate-500 mt-1">View learners in your classes.</p>
                            </a>
                            <a href="{{ route('teacher.recordMarks') }}" class="rounded-2xl border border-sky-100 bg-white p-5 shadow-sm hover:shadow-md transition-shadow">
                                <p class="font-extrabold text-sky-950">Record Marks</p>
                                <p class="text-sm text-slate-500 mt-1">Enter results for your subject.</p>
                            </a>
                            <a href="{{ route('teacher.attendance') }}" class="rounded-2xl border border-sky-100 bg-white p-5 shadow-sm hover:shadow-md transition-shadow">
                                <p class="font-extrabold text-sky-950">Attendance</p>
                                <p class="text-sm text-slate-500 mt-1">Track daily attendance.</p>
                            </a>
                            <a href="{{ route('teacher.reports') }}" class="rounded-2xl border border-sky-100 bg-white p-5 shadow-sm hover:shadow-md transition-shadow">
                                <p class="font-extrabold text-sky-950">Reports</p>
                                <p class="text-sm text-slate-500 mt-1">Review class summaries.</p>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($pendingUsers) && $pendingUsers->isNotEmpty())
                <div class="bg-white border border-gray-200 shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-800">Pending Role Verifications</h3>
                        <div class="mt-4 divide-y divide-gray-100">
                            @foreach($pendingUsers as $pendingUser)
                                <div class="py-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $pendingUser->name }}</p>
                                        <p class="text-sm text-gray-600">
                                            @if($pendingUser->school)
                                                {{ $pendingUser->school->name }} - {{ $pendingUser->school->ward }}
                                            @elseif($pendingUser->ward)
                                                {{ $pendingUser->ward }}
                                            @endif
                                        </p>
                                        <p class="text-sm text-gray-500">{{ $pendingUser->email }}</p>
                                    </div>
                                    <form method="POST" action="{{ route('role-verifications.approve', $pendingUser) }}" class="space-y-3 lg:space-y-0 lg:flex lg:items-center lg:gap-3">
                                        @csrf
                                        @method('PATCH')
                                        @if(Auth::user()->role === 'weo')
                                            <input type="hidden" name="assigned_role" value="headmaster">
                                            <div class="min-w-[180px]">
                                                <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1">Assign Role</label>
                                                <div class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-700">Head Teacher</div>
                                            </div>
                                        @elseif(Auth::user()->role === 'headmaster')
                                            <div class="min-w-[180px]">
                                                <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1">Assign Role</label>
                                                <select name="assigned_role" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200">
                                                    <option value="teacher" {{ $pendingUser->role === 'teacher' ? 'selected' : '' }}>Teacher</option>
                                                    <option value="academic_teacher" {{ $pendingUser->role === 'academic_teacher' ? 'selected' : '' }}>Academic Teacher</option>
                                                </select>
                                            </div>
                                        @endif
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Approve
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if(Auth::user()->role === 'academic_teacher' && Auth::user()->role_status === 'approved')
                <div class="bg-sky-50 border border-sky-200 shadow-sm sm:rounded-lg">
                    <div class="p-6 text-sky-900">
                        <h3 class="text-lg font-bold text-sky-800 flex items-center gap-2">
                            Academic Management Module
                        </h3>
                        <p class="text-sm text-sky-700 mt-1">This module is unlocked. You can now manage school schedules, subjects, and compile ward reports.</p>
                        </div>
                </div>
            @endif


            @if(Auth::user()->role === 'headmaster' && Auth::user()->role_status === 'approved')
                <div class="bg-white border border-sky-100 shadow-xl rounded-[30px] overflow-hidden">
                    <div class="p-6 md:p-8">
                        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                            <div>
                                <div class="flex items-center gap-3 mb-4">
                                    <x-application-logo class="h-11 w-11" />
                                    <div>
                                        <p class="text-sm font-bold uppercase tracking-wider text-sky-500">Head Teacher Dashboard</p>
                                        <h3 class="text-2xl font-extrabold text-sky-950">Welcome, {{ Auth::user()->name }}</h3>
                                    </div>
                                </div>
                                <p class="text-slate-600 max-w-2xl">
                                    Manage your school staff approvals, subjects, classes, and result verification from one place.
                                </p>
                            </div>

                            <div class="rounded-2xl border border-sky-100 bg-sky-50 px-5 py-4 min-w-[240px]">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">School</p>
                                <p class="mt-1 text-lg font-extrabold text-sky-950">{{ Auth::user()->school->name ?? 'School not assigned' }}</p>
                                <p class="text-sm text-slate-500">{{ Auth::user()->school->ward ?? 'Ward not assigned' }}</p>
                            </div>
                        </div>

                        <div class="grid gap-4 mt-8 md:grid-cols-3">
                            <div class="rounded-2xl border border-sky-100 bg-sky-50 p-5">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Pending Staff</p>
                                <p class="mt-2 text-3xl font-extrabold text-sky-950">{{ isset($pendingUsers) ? $pendingUsers->count() : 0 }}</p>
                            </div>
                            <div class="rounded-2xl border border-sky-100 bg-sky-50 p-5">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Role Assignment</p>
                                <p class="mt-2 text-sm font-semibold text-slate-700">Approve staff as Teacher or Academic Teacher.</p>
                            </div>
                            <div class="rounded-2xl border border-sky-100 bg-sky-50 p-5">
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Academic Setup</p>
                                <p class="mt-2 text-sm font-semibold text-slate-700">Upload and manage subjects and classes.</p>
                            </div>
                        </div>

                        <div class="grid gap-4 mt-8 sm:grid-cols-2 lg:grid-cols-4">
                            <a href="{{ route('module.headTeacher') }}" class="rounded-2xl border border-sky-100 bg-white p-5 shadow-sm hover:shadow-md transition-shadow">
                                <p class="font-extrabold text-sky-950">Head Teacher Module</p>
                                <p class="text-sm text-slate-500 mt-1">Open the management workspace.</p>
                            </a>
                            <a href="{{ route('headTeacher.verifyResults') }}" class="rounded-2xl border border-sky-100 bg-white p-5 shadow-sm hover:shadow-md transition-shadow">
                                <p class="font-extrabold text-sky-950">Verify Results</p>
                                <p class="text-sm text-slate-500 mt-1">Review compiled school results.</p>
                            </a>
                            <a href="{{ route('headTeacher.approvedReports') }}" class="rounded-2xl border border-sky-100 bg-white p-5 shadow-sm hover:shadow-md transition-shadow">
                                <p class="font-extrabold text-sky-950">Approved Reports</p>
                                <p class="text-sm text-slate-500 mt-1">View reports ready for WEO.</p>
                            </a>
                            <a href="{{ route('profile.edit') }}" class="rounded-2xl border border-sky-100 bg-white p-5 shadow-sm hover:shadow-md transition-shadow">
                                <p class="font-extrabold text-sky-950">Profile</p>
                                <p class="text-sm text-slate-500 mt-1">Check your account details.</p>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            @if(Auth::user()->role === 'weo' && Auth::user()->role_status === 'approved')
                <div class="bg-sky-50 border border-sky-200 shadow-sm sm:rounded-lg">
                    <div class="p-6 text-sky-900">
                        <h3 class="text-lg font-bold text-sky-800 flex items-center gap-2">
                            WEO Verification Module
                        </h3>
                        <p class="text-sm text-sky-700 mt-1">You can verify pending Head Teacher accounts for schools in your ward.</p>
                    </div>
                </div>
            @endif

            @if(Auth::user()->role === 'admin' && Auth::user()->role_status === 'approved')
                <div class="bg-sky-50 border border-sky-200 shadow-sm sm:rounded-lg">
                    <div class="p-6 text-sky-900">
                        <h3 class="text-lg font-bold text-sky-800 flex items-center gap-2">
                            System Administrator Verification Module
                        </h3>
                        <p class="text-sm text-sky-700 mt-1">You can verify pending WEO accounts.</p>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
