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
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
{
    // Fetch all schools from your database alphabetically
    $schools = School::orderBy('name', 'asc')->get();

    // Pass the schools variable straight down into your blade view template
    return view('auth.register', compact('schools'));
}

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    // 1. Strict Validation: Check fields before touching the database
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        'role' => ['required', 'string', 'in:weo,headmaster,teacher'],
        // school_id is only mandatory if they are a headmaster or teacher
        'school_id' => ['required_if:role,headmaster,teacher', 'nullable', 'exists:schools,id'],
    ]);

    // 2. Create the account mapping profile inside your MySQL database
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        // If they are a WEO, they don't have a specific school, so save null
        'school_id' => $request->role === 'weo' ? null : $request->school_id,
    ]);

    event(new Registered($user));

    Auth::login($user);

    return redirect(route('dashboard', absolute: false));
}
}
