<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function show(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:72'],
        ]);

        $key = Str::lower($credentials['email'])
            . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()
                ->withErrors([
                    'email' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam '
                        . RateLimiter::availableIn($key) . ' detik.',
                ])
                ->onlyInput('email');
        }

        if (! Auth::attempt($credentials)) {
            RateLimiter::hit($key, 60);

            return back()
                ->withErrors([
                    'email' => 'Email atau password tidak sesuai.',
                ])
                ->onlyInput('email');
        }

        RateLimiter::clear($key);

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function dashboard(Request $request): RedirectResponse
    {
        $user = $request->user();

        $portals = [
            'admin' => 'filament.admin.pages.dashboard',
            'teacher' => 'teacher.dashboard',
            'student' => 'student.dashboard',
            'parent' => 'parent.dashboard',
            'school' => 'school.dashboard',
        ];

        foreach ($portals as $role => $route) {
            if (
                $user->hasRole($role)
                && $user->can('access.' . $role)
            ) {
                return redirect()->route($route);
            }
        }

        abort(403, 'Anda tidak memiliki akses ke portal.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}