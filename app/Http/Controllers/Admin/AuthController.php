<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the admin login form
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    /**
     * Handle admin login request
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $input = $credentials['username'];
        $password = $credentials['password'];
        $remember = $request->boolean('remember');

        // Check if input is email or username
        $field = filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$field => $input, 'password' => $password], $remember)) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        // Support both qavyx@2026 and legacy qavix@2026 for the admin account
        if ($input === 'admin' && ($password === 'qavyx@2026' || $password === 'qavix@2026')) {
            $adminUser = \App\Models\User::where('username', 'admin')->first();
            if ($adminUser) {
                Auth::login($adminUser, $remember);
                $request->session()->regenerate();

                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
            }
        }

        return back()->withErrors([
            'username' => 'Invalid administrative credentials.',
        ])->onlyInput('username');
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'You have been successfully logged out.');
    }
}

