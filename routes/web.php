<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DepartmentModuleController;
use App\Http\Controllers\RoleVerificationController;
use App\Http\Controllers\TeacherController;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user()->load(['school', 'teachingSubject', 'teachingClasses']);
    $pendingUsers = collect();
    $teacherSummaries = collect();
    $subjects = collect();
    $classes = collect();

    if (in_array($user->role, ['academic_teacher', 'teacher'], true)) {
        $subjects = Subject::orderBy('name')->get();
        $classes = SchoolClass::where('school_id', $user->school_id)
            ->orderBy('name')
            ->orderBy('stream')
            ->get();
    }

    if ($user->role === 'headmaster' && $user->role_status === 'approved') {
        $teacherSearch = trim((string) request('teacher_search', ''));
        $pendingUsers = User::with('school')
            ->whereIn('role', ['academic_teacher', 'teacher'])
            ->where('role_status', 'pending')
            ->where('school_id', $user->school_id)
            ->orderBy('name')
            ->get();

        $teacherSummaries = User::with(['teachingSubject', 'teachingClasses.learners'])
            ->whereIn('role', ['academic_teacher', 'teacher'])
            ->where('role_status', 'approved')
            ->where('school_id', $user->school_id)
            ->when($teacherSearch !== '', fn ($query) => $query->where(function ($inner) use ($teacherSearch) {
                $inner->where('name', 'like', "%{$teacherSearch}%")
                    ->orWhere('role', 'like', "%{$teacherSearch}%")
                    ->orWhereHas('teachingSubject', fn ($subject) => $subject->where('name', 'like', "%{$teacherSearch}%"))
                    ->orWhereHas('teachingClasses', fn ($class) => $class
                        ->where('name', 'like', "%{$teacherSearch}%")
                        ->orWhere('stream', 'like', "%{$teacherSearch}%"));
            }))
            ->orderBy('name')
            ->get()
            ->map(function (User $teacher) {
                $teacher->assigned_learners_count = $teacher->teachingClasses
                    ->flatMap(fn ($class) => $class->learners)
                    ->unique('id')
                    ->count();

                return $teacher;
            });
    }

    if ($user->role === 'weo' && $user->role_status === 'approved') {
        $pendingUsers = User::with('school')
            ->where('role', 'headmaster')
            ->where('role_status', 'pending')
            ->whereHas('school', fn ($query) => $query->where('ward', $user->ward))
            ->orderBy('name')
            ->get();
    }

    if ($user->role === 'admin' && $user->role_status === 'approved') {
        $pendingUsers = User::with('school')
            ->where('role', 'weo')
            ->where('role_status', 'pending')
            ->orderBy('name')
            ->get();
    }

    return view('dashboard', compact('pendingUsers', 'teacherSummaries', 'subjects', 'classes'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/module/teacher', [TeacherController::class, 'show'])->name('module.teacher');
    Route::patch('/module/teacher/assignment', [TeacherController::class, 'updateAssignment'])->name('teacher.assignment.update');
    Route::get('/module/teacher/classlists', [TeacherController::class, 'classLists'])->name('teacher.classlists');
    Route::get('/module/teacher/record-marks', [TeacherController::class, 'recordMarks'])->name('teacher.recordMarks');
    Route::post('/module/teacher/record-marks', [TeacherController::class, 'storeMarks'])->name('teacher.recordMarks.store');
    Route::get('/module/teacher/attendance', [TeacherController::class, 'attendance'])->name('teacher.attendance');
    Route::post('/module/teacher/attendance', [TeacherController::class, 'storeAttendance'])->name('teacher.attendance.store');
    Route::get('/module/teacher/reports', [TeacherController::class, 'reports'])->name('teacher.reports');
    Route::get('/module/teacher/reports/download', [TeacherController::class, 'downloadReports'])->name('teacher.reports.download');
    Route::patch('/role-verifications/{user}', [RoleVerificationController::class, 'approve'])->name('role-verifications.approve');

    Route::get('/module/academic-teacher', [DepartmentModuleController::class, 'academicTeacher'])->name('module.academicTeacher');
    Route::post('/module/academic-teacher/exams', [DepartmentModuleController::class, 'storeExam'])->name('academicTeacher.exams.store');
    Route::get('/module/academic-teacher/school-compilation', [DepartmentModuleController::class, 'schoolCompilation'])->name('academicTeacher.schoolCompilation');
    Route::post('/module/academic-teacher/school-compilation/submit', [DepartmentModuleController::class, 'submitSchoolCompilation'])->name('academicTeacher.schoolCompilation.submit');
    Route::get('/module/academic-teacher/missing-marks', [DepartmentModuleController::class, 'missingMarks'])->name('academicTeacher.missingMarks');
    Route::get('/module/academic-teacher/learner-register', [DepartmentModuleController::class, 'learnerRegister'])->name('academicTeacher.learnerRegister');
    Route::get('/module/academic-teacher/teacher-submissions', [DepartmentModuleController::class, 'teacherSubmissions'])->name('academicTeacher.teacherSubmissions');
    Route::get('/module/academic-teacher/learner-performance', [DepartmentModuleController::class, 'learnerPerformance'])->name('academicTeacher.learnerPerformance');
    Route::get('/module/academic-teacher/results-ranking', [DepartmentModuleController::class, 'resultsRanking'])->name('academicTeacher.resultsRanking');
    Route::get('/module/academic-teacher/results-ranking/download', [DepartmentModuleController::class, 'downloadResultsRanking'])->name('academicTeacher.resultsRanking.download');
    Route::get('/module/academic-teacher/results-comparison', [DepartmentModuleController::class, 'resultsComparison'])->name('academicTeacher.resultsComparison');
    Route::get('/module/academic-teacher/official-template', [DepartmentModuleController::class, 'officialTemplate'])->name('academicTeacher.officialTemplate');

    Route::get('/module/head-teacher', [DepartmentModuleController::class, 'headTeacher'])->name('module.headTeacher');
    Route::post('/module/head-teacher/subjects', [DepartmentModuleController::class, 'storeSubject'])->name('headTeacher.subjects.store');
    Route::post('/module/head-teacher/classes', [DepartmentModuleController::class, 'storeClass'])->name('headTeacher.classes.store');
    Route::post('/module/head-teacher/learners', [DepartmentModuleController::class, 'storeLearner'])->name('headTeacher.learners.store');
    Route::post('/module/head-teacher/learners/upload', [DepartmentModuleController::class, 'uploadLearners'])->name('headTeacher.learners.upload');
    Route::patch('/module/head-teacher/teachers/{teacher}/school', [DepartmentModuleController::class, 'attachTeacherToSchool'])->name('headTeacher.teachers.school.attach');
    Route::patch('/module/head-teacher/teachers/{teacher}/assignment', [DepartmentModuleController::class, 'updateTeacherAssignment'])->name('headTeacher.teachers.assignment.update');
    Route::get('/module/head-teacher/results-ranking', [DepartmentModuleController::class, 'resultsRanking'])->name('headTeacher.resultsRanking');
    Route::get('/module/head-teacher/results-ranking/download', [DepartmentModuleController::class, 'downloadResultsRanking'])->name('headTeacher.resultsRanking.download');
    Route::get('/module/head-teacher/verify-results', [DepartmentModuleController::class, 'verifySchoolResults'])->name('headTeacher.verifyResults');
    Route::get('/module/head-teacher/approved-reports', [DepartmentModuleController::class, 'approvedReports'])->name('headTeacher.approvedReports');

    Route::get('/module/weo', [DepartmentModuleController::class, 'weo'])->name('module.weo');
    Route::get('/module/weo/ward-submissions', [DepartmentModuleController::class, 'wardSubmissions'])->name('weo.wardSubmissions');
    Route::get('/module/weo/ward-compilation', [DepartmentModuleController::class, 'wardCompilation'])->name('weo.wardCompilation');
    Route::get('/module/weo/template-export', [DepartmentModuleController::class, 'wardTemplateExport'])->name('weo.templateExport');

    Route::get('/module/admin', [DepartmentModuleController::class, 'admin'])->name('module.admin');
    Route::get('/module/admin/schools', [DepartmentModuleController::class, 'manageSchools'])->name('admin.schools');
    Route::get('/module/admin/templates', [DepartmentModuleController::class, 'manageTemplates'])->name('admin.templates');
    Route::post('/module/admin/templates', [DepartmentModuleController::class, 'uploadTemplate'])->name('admin.templates.upload');
    Route::get('/module/admin/templates/{fileName}/download', [DepartmentModuleController::class, 'downloadTemplate'])->name('admin.templates.download');
});

Route::get('/login', function () {
    return view('login');
});
Route::get('/register',function (){
    return view('register');
});
require __DIR__.'/auth.php';
