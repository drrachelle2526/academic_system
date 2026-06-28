<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\School;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        return view('auth.register', compact('schools'));
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
            'role' => ['required', 'string', 'in:weo,headmaster,academic_teacher,teacher'],
            'ward' => ['required_if:role,weo', 'nullable', 'string', 'max:255'],
            'school_id' => ['required_if:role,headmaster,academic_teacher,teacher', 'nullable', 'exists:schools,id'],
        ]);

        $isWeo = $request->role === 'weo';
        $status = $request->role === 'teacher' ? 'approved' : 'pending';

        $user = User::create([
            'name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'role_status' => $status,
            'ward' => $isWeo ? $request->ward : null,
            'school_id' => $isWeo ? null : $request->school_id,
        ]);

        event(new Registered($user));

        if ($status === 'approved') {
            Auth::login($user);

            return redirect()->route('dashboard');
        }

        $approvalMessage = match ($request->role) {
            'weo' => 'Registration successful. Please wait for approval from the System Administrator.',
            'headmaster' => 'Registration successful. Please wait for approval from your WEO.',
            'academic_teacher' => 'Registration successful. Please wait for approval from your Head Teacher.',
            default => 'Registration successful. Please wait for approval.',
        };

        return redirect()->route('login')->with('status', $approvalMessage);
    }
}
