<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $schools = School::orderBy('name', 'asc')->get();
        $schoolClasses = SchoolClass::orderBy('name')->orderBy('stream')->get();
        $subjects = Subject::orderBy('name')->get();
        $wards = School::select('ward')
            ->distinct()
            ->orderBy('ward')
            ->pluck('ward');

        return view('auth.register', compact('schools', 'schoolClasses', 'subjects', 'wards'));
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'ward' => ['required', 'string', 'exists:schools,ward'],
            'school_id' => ['required', 'exists:schools,id'],
            'teaching_subject_id' => ['nullable', 'exists:subjects,id'],
            'teaching_class_ids' => ['nullable', 'array'],
            'teaching_class_ids.*' => ['integer', 'exists:school_classes,id'],
        ]);

        $teachingClassIds = collect();

        if ($request->filled('teaching_subject_id') && $request->filled('teaching_class_ids')) {
            $teachingClassIds = collect($request->teaching_class_ids)->map(fn ($id) => (int) $id)->unique()->values();

            $validClassCount = SchoolClass::whereIn('id', $teachingClassIds)
                ->where('school_id', $request->school_id)
                ->count();

            abort_if($validClassCount !== $teachingClassIds->count(), 422, 'Choose classes from the selected school.');
        }

        $user = User::create([
            'name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
            'role_status' => 'pending',
            'school_id' => $request->school_id,
            'teaching_subject_id' => $request->teaching_subject_id,
            'teaching_class_id' => $teachingClassIds->first(),
        ]);

        if ($teachingClassIds->isNotEmpty()) {
            $user->teachingClasses()->sync($teachingClassIds);
        }

        event(new Registered($user));

        return redirect()->route('login')->with('status', 'Registration successful. Please wait for role assignment and approval.');
    }
}
