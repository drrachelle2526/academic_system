<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Show the teacher module dashboard.
     */
    public function show()
    {
        $user = auth()->user()->load(['school', 'teachingSubject', 'teachingClasses']);
        return view('modules.teacher', compact('user'));
    }

    public function classLists()
    {
        $user = auth()->user()->load('teachingSubject');
        $classLists = $user->teachingClasses()
            ->withCount('learners')
            ->orderBy('name')
            ->orderBy('stream')
            ->get();

        return view('modules.classlists', compact('user', 'classLists'));
    }

    public function recordMarks()
    {
        $user = auth()->user()->load(['teachingSubject', 'teachingClasses']);
        return view('modules.record_marks', compact('user'));
    }

    public function attendance()
    {
        $user = auth()->user()->load(['teachingSubject', 'teachingClasses']);
        return view('modules.attendance', compact('user'));
    }

    public function reports()
    {
        $user = auth()->user()->load(['teachingSubject', 'teachingClasses']);
        return view('modules.reports', compact('user'));
    }
}
