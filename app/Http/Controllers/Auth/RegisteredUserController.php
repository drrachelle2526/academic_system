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
     * Fetches all database primary schools alphabetically to populate the dropdown.
     */
    public function create(): View
    {
        $schools = School::orderBy('name', 'asc')->get();

        return view('auth.register', compact('schools'));
    }

    /**
     * Handle an incoming registration request.
     * Validates personnel identification, roles, and structural constraints.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Strict Request Validation
        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:weo,headmaster,academic_teacher,teacher'],
            
            // The administrative ward is strictly mandatory ONLY if the user chooses 'weo'
            'ward' => ['required_if:role,weo', 'nullable', 'string', 'max:255'],
            
            // The school_id reference key is strictly mandatory for institutional staff accounts
            'school_id' => ['required_if:role,headmaster,academic_teacher,teacher', 'nullable', 'exists:schools,id'],
        ]);

        // 2. Data Persistence Model Mapping
        // WEOs oversee entire wards, so we clear out school keys if they select 'weo'
        $isWeo = $request->role === 'weo';

        $user = User::create([
            'name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'ward' => $isWeo ? $request->ward : null,
            'school_id' => $isWeo ? null : $request->school_id,
        ]);

        // 3. Dispatch System Events and Initialize Session Connection Thread
        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}