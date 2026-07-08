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
                <h1 class="text-3xl font-extrabold text-sky-950">Head Teacher Workspace</h1>
                <p class="text-slate-600 mt-2 max-w-2xl">Approve staff roles, register subjects and classes, and review teacher assignments for your school.</p>
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

        <div class="grid grid-cols-1 gap-4 mt-8 sm:grid-cols-2 lg:grid-cols-4">
            <a href="{{ route('headTeacher.verifyResults') }}" class="block rounded-2xl border border-sky-100 bg-white p-5 shadow-sm hover:shadow">
                <h3 class="font-bold text-sky-950 mb-2">Verify Results</h3>
                <p class="text-sm text-slate-600">Review compiled results and approve school submissions.</p>
            </a>
            <a href="{{ route('headTeacher.approvedReports') }}" class="block rounded-2xl border border-sky-100 bg-white p-5 shadow-sm hover:shadow">
                <h3 class="font-bold text-sky-950 mb-2">Approved Reports</h3>
                <p class="text-sm text-slate-600">Track reports already approved for WEO review.</p>
            </a>
            <a href="{{ route('headTeacher.resultsRanking') }}" class="block rounded-2xl border border-sky-100 bg-white p-5 shadow-sm hover:shadow">
                <h3 class="font-bold text-sky-950 mb-2">Class Ranking</h3>
                <p class="text-sm text-slate-600">Search, print, and download class positions.</p>
            </a>
            <a href="#staff-roles" class="block rounded-2xl border border-sky-100 bg-white p-5 shadow-sm hover:shadow">
                <h3 class="font-bold text-sky-950 mb-2">Staff Roles</h3>
                <p class="text-sm text-slate-600">Assign pending staff as Teacher or Academic Teacher.</p>
            </a>
            <a href="#academic-setup" class="block rounded-2xl border border-sky-100 bg-white p-5 shadow-sm hover:shadow">
                <h3 class="font-bold text-sky-950 mb-2">Academic Setup</h3>
                <p class="text-sm text-slate-600">Register subjects and classes teachers will use.</p>
            </a>
            <a href="#learners" class="block rounded-2xl border border-sky-100 bg-white p-5 shadow-sm hover:shadow">
                <h3 class="font-bold text-sky-950 mb-2">Learners</h3>
                <p class="text-sm text-slate-600">Add learners to each grade and stream.</p>
            </a>
        </div>

        <section id="staff-roles" class="mt-10">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-extrabold text-sky-950">Staff Role Assignment</h2>
                    <p class="text-sm text-slate-500 mt-1">Pending staff appear here after registration.</p>
                </div>
                <span class="rounded-full bg-sky-50 px-4 py-2 text-sm font-bold text-sky-800">{{ $pendingUsers->count() }} pending</span>
            </div>

            <div class="mt-4 rounded-2xl border border-sky-100 bg-white p-5">
                @forelse($pendingUsers as $pendingUser)
                    <div class="flex flex-col gap-4 border-b border-slate-100 py-4 last:border-b-0 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="font-bold text-slate-900">{{ $pendingUser->name }}</p>
                            <p class="text-sm text-slate-500">{{ $pendingUser->email }}</p>
                            <p class="text-sm text-slate-500">{{ $pendingUser->teachingSubject->name ?? 'No subject selected' }}</p>
                        </div>
                        <form method="POST" action="{{ route('role-verifications.approve', $pendingUser) }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1">Assign Role</label>
                                <select name="assigned_role" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200">
                                    <option value="teacher" {{ $pendingUser->role === 'teacher' ? 'selected' : '' }}>Teacher</option>
                                    <option value="academic_teacher" {{ $pendingUser->role === 'academic_teacher' ? 'selected' : '' }}>Academic Teacher</option>
                                </select>
                            </div>
                            <button type="submit" class="rounded-full bg-green-600 px-5 py-2.5 text-sm font-bold text-white shadow hover:bg-green-700">Approve</button>
                        </form>
                    </div>
                @empty
                    <p class="text-sm font-medium text-slate-500">No pending staff registrations for this school.</p>
                @endforelse
            </div>
        </section>

        <section id="academic-setup" class="mt-10">
            <div>
                <h2 class="text-xl font-extrabold text-sky-950">Subjects and Classes</h2>
                <p class="text-sm text-slate-500 mt-1">Saving a subject or class publishes it inside the system for teacher registration and assignment.</p>
            </div>

        <div class="grid gap-6 mt-4 lg:grid-cols-2">
            <form method="POST" action="{{ route('headTeacher.subjects.store') }}" class="rounded-2xl border border-sky-100 bg-sky-50 p-5">
                @csrf
                <h3 class="text-lg font-extrabold text-sky-950">Register Subject</h3>
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
                <h3 class="text-lg font-extrabold text-sky-950">Register Class</h3>
                <div class="mt-4 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Grade</label>
                        <select name="name" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-800 outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200">
                            <option value="">Choose grade</option>
                            @for($grade = 1; $grade <= 7; $grade++)
                                <option value="Grade {{ $grade }}" @selected(old('name') === "Grade {$grade}")>Grade {{ $grade }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Stream</label>
                        <input type="text" name="stream" value="{{ old('stream') }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-800 outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200" placeholder="A, B, Blue">
                    </div>
                    <button type="submit" class="rounded-full bg-sky-500 px-5 py-3 text-sm font-bold text-white shadow hover:bg-sky-600">Save Class</button>
                </div>
            </form>
        </div>

        <div class="grid gap-6 mt-8 lg:grid-cols-2">
            <div class="rounded-2xl border border-sky-100 bg-white p-5">
                <h3 class="text-lg font-extrabold text-sky-950">Registered Subjects</h3>
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
                <h3 class="text-lg font-extrabold text-sky-950">Registered Classes</h3>
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
        </section>

        <section id="learners" class="mt-10">
            <div>
                <h2 class="text-xl font-extrabold text-sky-950">Learners</h2>
                <p class="text-sm text-slate-500 mt-1">Register learners under the correct grade and stream before teachers record marks or attendance.</p>
            </div>

            <div class="grid gap-6 mt-4 lg:grid-cols-[1fr_1.2fr]">
                <div class="space-y-6">
                    <form method="POST" action="{{ route('headTeacher.learners.upload') }}" enctype="multipart/form-data" class="rounded-2xl border border-sky-100 bg-sky-50 p-5">
                        @csrf
                        <h3 class="text-lg font-extrabold text-sky-950">Upload Learners</h3>
                        <p class="mt-1 text-sm text-slate-500">Upload CSV exported from Excel with headers: admission_number, name, grade, stream, gender, date_of_birth.</p>
                        <div class="mt-4 space-y-4">
                            <input type="file" name="learners_file" accept=".csv,.txt" required class="block w-full rounded-xl border border-slate-200 bg-white p-3 text-sm text-slate-700">
                            <button type="submit" class="rounded-full bg-sky-500 px-5 py-3 text-sm font-bold text-white shadow hover:bg-sky-600">Upload Learners</button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('headTeacher.learners.store') }}" class="rounded-2xl border border-sky-100 bg-sky-50 p-5">
                        @csrf
                        <h3 class="text-lg font-extrabold text-sky-950">Register One Learner</h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Grade and Stream</label>
                                <select name="school_class_id" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-800 outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200">
                                    <option value="">Choose class</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" @selected(old('school_class_id') == $class->id)>{{ $class->name }}{{ $class->stream !== 'main' ? ' '.$class->stream : '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Learner Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-800 outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200" placeholder="Learner full name">
                            </div>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Admission Number</label>
                                    <input type="text" name="admission_number" value="{{ old('admission_number') }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-800 outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200" placeholder="Optional">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Gender</label>
                                    <select name="gender" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-800 outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200">
                                        <option value="">Optional</option>
                                        <option value="Female" @selected(old('gender') === 'Female')>Female</option>
                                        <option value="Male" @selected(old('gender') === 'Male')>Male</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1">Date of Birth</label>
                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-800 outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200">
                            </div>
                            <button type="submit" class="rounded-full bg-white px-5 py-3 text-sm font-bold text-sky-700 shadow-sm ring-1 ring-sky-100 hover:bg-sky-50">Save One Learner</button>
                        </div>
                    </form>
                </div>

                <div class="rounded-2xl border border-sky-100 bg-white p-5">
                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                        <h3 class="text-lg font-extrabold text-sky-950">Registered Learners</h3>
                        <span class="text-sm font-bold text-sky-800">{{ $learners->count() }} total</span>
                    </div>
                    <div class="mt-4 max-h-96 space-y-2 overflow-y-auto">
                        @forelse($learners as $learner)
                            <div class="grid gap-2 rounded-xl border border-slate-100 bg-slate-50 px-4 py-3 sm:grid-cols-[1fr_auto] sm:items-center">
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $learner->name }}</p>
                                    <p class="text-sm text-slate-500">{{ $learner->admission_number ?? 'No admission number' }}</p>
                                </div>
                                <span class="text-sm font-bold text-sky-800">{{ $learner->schoolClass?->name ?? 'No class' }}{{ $learner->schoolClass && $learner->schoolClass->stream !== 'main' ? ' '.$learner->schoolClass->stream : '' }}</span>
                            </div>
                        @empty
                            <p class="text-sm font-medium text-slate-500">No learners have been registered yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-10">
            <div>
                <h2 class="text-xl font-extrabold text-sky-950">Teacher Assignments</h2>
                <p class="text-sm text-slate-500 mt-1">Learner totals are counted from the classes assigned to each teacher.</p>
            </div>

            <form method="GET" action="{{ route('module.headTeacher') }}" class="mt-4 grid gap-3 rounded-2xl border border-sky-100 bg-sky-50 p-4 sm:grid-cols-[1fr_auto_auto] sm:items-center">
                <input type="search" name="teacher_search" value="{{ $teacherSearch }}" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200" placeholder="Search teacher, subject, role, class, or stream">
                <button type="submit" class="rounded-full bg-sky-500 px-5 py-3 text-sm font-bold text-white shadow hover:bg-sky-600">Search</button>
                <a href="{{ route('module.headTeacher') }}" class="rounded-full bg-white px-5 py-3 text-center text-sm font-bold text-sky-700 shadow-sm ring-1 ring-sky-100 hover:bg-sky-50">Reset</a>
            </form>

            @if(isset($unlinkedTeachers) && $unlinkedTeachers->isNotEmpty())
                <div class="mt-4 rounded-2xl border border-amber-100 bg-amber-50 p-5">
                    <h3 class="font-extrabold text-amber-900">Approved Staff Without School</h3>
                    <div class="mt-3 space-y-3">
                        @foreach($unlinkedTeachers as $teacher)
                            <form method="POST" action="{{ route('headTeacher.teachers.school.attach', $teacher) }}" class="flex flex-col gap-3 rounded-xl bg-white px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                                @csrf
                                @method('PATCH')
                                <div>
                                    <p class="font-bold text-slate-900">{{ $teacher->name }}</p>
                                    <p class="text-sm text-slate-500">{{ $teacher->email }}</p>
                                </div>
                                <button type="submit" class="rounded-full bg-amber-500 px-5 py-2.5 text-sm font-bold text-white shadow hover:bg-amber-600">Link to {{ $user->school->name ?? 'this school' }}</button>
                            </form>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mt-4 rounded-2xl border border-sky-100 bg-white p-5">
                @forelse($teachers as $teacher)
                    <form method="POST" action="{{ route('headTeacher.teachers.assignment.update', $teacher) }}" class="grid gap-4 border-b border-slate-100 py-4 last:border-b-0 lg:grid-cols-[1fr_1.1fr_1.6fr_auto] lg:items-start">
                        @csrf
                        @method('PATCH')
                        <div>
                            <p class="font-bold text-slate-900">{{ $teacher->name }}</p>
                            <p class="text-sm text-slate-500">{{ ucwords(str_replace('_', ' ', $teacher->role)) }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1">Subject</label>
                            <select name="teaching_subject_id" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-200">
                                <option value="">Not selected</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" @selected($teacher->teaching_subject_id === $subject->id)>{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <p class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Classes</p>
                            <div class="grid gap-2 sm:grid-cols-2">
                                @forelse($classes as $class)
                                    <label class="flex items-center gap-2 rounded-xl border border-slate-100 bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-700">
                                        <input type="checkbox" name="teaching_class_ids[]" value="{{ $class->id }}" @checked($teacher->teachingClasses->contains('id', $class->id)) class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                                        <span>{{ $class->name }}{{ $class->stream !== 'main' ? ' '.$class->stream : '' }}</span>
                                    </label>
                                @empty
                                    <span class="text-sm text-slate-500">No classes registered</span>
                                @endforelse
                            </div>
                        </div>
                        <div class="space-y-3">
                            <button type="submit" class="w-full rounded-full bg-sky-500 px-5 py-2.5 text-sm font-bold text-white shadow hover:bg-sky-600">Save</button>
                            <div class="rounded-xl bg-slate-50 px-4 py-2 text-center">
                            <p class="text-lg font-extrabold text-sky-950">{{ $teacher->assigned_learners_count }}</p>
                            <p class="text-xs font-semibold text-slate-500">learners</p>
                            </div>
                        </div>
                    </form>
                @empty
                    <p class="text-sm font-medium text-slate-500">No approved teachers assigned yet.</p>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection
