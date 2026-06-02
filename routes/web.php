<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
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
});

Route::get('/login', function () {
    return view('login');
});
Route::get('/register',function (){
    return view('register');
});
require __DIR__.'/auth.php';
