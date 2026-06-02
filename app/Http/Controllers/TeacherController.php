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
        $user = auth()->user();
        return view('modules.teacher', compact('user'));
    }

    public function classLists()
    {
        $user = auth()->user();

        // Mock class list data for initial UI
        $classLists = [
            [
                'name' => 'Standard 1 - A',
                'students' => 28,
                'subjects' => ['Mathematics', 'English', 'Science']
            ],
            [
                'name' => 'Standard 2 - B',
                'students' => 32,
                'subjects' => ['Mathematics', 'Kiswahili', 'Science']
            ],
        ];

        return view('modules.classlists', compact('user', 'classLists'));
    }

    public function recordMarks()
    {
        $user = auth()->user();
        return view('modules.record_marks', compact('user'));
    }

    public function attendance()
    {
        $user = auth()->user();
        return view('modules.attendance', compact('user'));
    }

    public function reports()
    {
        $user = auth()->user();
        return view('modules.reports', compact('user'));
    }
}
