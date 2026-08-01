<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterUserRequest;
use App\Services\UserFactory\UserCreator;

/**
 * AuthController — Handles user authentication (Module 2).
 *
 * SECURITY:
 * - Bcrypt password hashing via Hash facade (OWASP A2)
 * - Session regeneration on login to prevent session hijacking (OWASP A7)
 */
class AuthController extends Controller
{
    /** Show registration form. */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle user registration using the Factory Method Pattern.
     * SECURITY: Bcrypt hashing applied via UserCreator subclasses.
     */
    public function register(RegisterUserRequest $request)
    {
        $validated = $request->validated();

        // DESIGN PATTERN: Factory Method (Module 2)
        // Resolves the correct creator based on role
        $creator = UserCreator::resolve($validated['role']);
        $user = $creator->createUser($validated);

        Auth::login($user);

        // SECURITY (Module 2): Regenerate session ID after login to prevent session fixation
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'Registration successful! Welcome to NutriShare.');
    }

    /** Show login form. */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login.
     * SECURITY (Module 2): Session hijacking prevention via session regeneration.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // SECURITY: Regenerate session ID to prevent session hijacking
            $request->session()->regenerate();

            // System Notification on Login
            \App\Models\Notification::create([
                'user_id' => Auth::id(),
                'title' => 'Login Alert',
                'message' => 'Welcome back, ' . Auth::user()->name . '! Signed in successfully as ' . (Auth::user()->isNgo() ? 'NGO' : ucfirst(Auth::user()->role)) . '.',
                'channel' => Auth::user()->notification_preference ?? 'email',
                'sent_at' => now(),
            ]);

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /** Handle logout. */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
