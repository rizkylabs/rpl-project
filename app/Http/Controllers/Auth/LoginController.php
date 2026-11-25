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
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Masukkan alamat email yang valid.',
            'email.max' => 'Alamat email maksimal 255 karakter.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.string' => 'Kata sandi tidak valid.',
            'password.max' => 'Kata sandi maksimal 72 karakter.',
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
                    'email' => 'Email atau kata sandi tidak sesuai.',
                ])
                ->onlyInput('email');
        }

        RateLimiter::clear($key);

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}