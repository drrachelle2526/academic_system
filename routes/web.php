<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DepartmentModuleController;
use App\Http\Controllers\RoleVerificationController;
use App\Http\Controllers\TeacherController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user()->load(['school', 'teachingSubject', 'teachingClasses']);
    $pendingUsers = collect();

    if ($user->role === 'headmaster' && $user->role_status === 'approved') {
        $pendingUsers = User::with('school')
            ->whereIn('role', ['academic_teacher', 'teacher'])
            ->where('role_status', 'pending')
            ->where('school_id', $user->school_id)
            ->orderBy('name')
            ->get();
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

    return view('dashboard', compact('pendingUsers'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/module/teacher', [TeacherController::class, 'show'])->name('module.teacher');
    Route::get('/module/teacher/classlists', [TeacherController::class, 'classLists'])->name('teacher.classlists');
    Route::get('/module/teacher/record-marks', [TeacherController::class, 'recordMarks'])->name('teacher.recordMarks');
    Route::get('/module/teacher/attendance', [TeacherController::class, 'attendance'])->name('teacher.attendance');
    Route::get('/module/teacher/reports', [TeacherController::class, 'reports'])->name('teacher.reports');
    Route::patch('/role-verifications/{user}', [RoleVerificationController::class, 'approve'])->name('role-verifications.approve');

    Route::get('/module/academic-teacher', [DepartmentModuleController::class, 'academicTeacher'])->name('module.academicTeacher');
    Route::get('/module/academic-teacher/school-compilation', [DepartmentModuleController::class, 'schoolCompilation'])->name('academicTeacher.schoolCompilation');
    Route::get('/module/academic-teacher/missing-marks', [DepartmentModuleController::class, 'missingMarks'])->name('academicTeacher.missingMarks');
    Route::get('/module/academic-teacher/official-template', [DepartmentModuleController::class, 'officialTemplate'])->name('academicTeacher.officialTemplate');

    Route::get('/module/head-teacher', [DepartmentModuleController::class, 'headTeacher'])->name('module.headTeacher');
    Route::post('/module/head-teacher/subjects', [DepartmentModuleController::class, 'storeSubject'])->name('headTeacher.subjects.store');
    Route::post('/module/head-teacher/subjects/upload', [DepartmentModuleController::class, 'uploadSubjects'])->name('headTeacher.subjects.upload');
    Route::post('/module/head-teacher/classes', [DepartmentModuleController::class, 'storeClass'])->name('headTeacher.classes.store');
    Route::post('/module/head-teacher/classes/upload', [DepartmentModuleController::class, 'uploadClasses'])->name('headTeacher.classes.upload');
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
