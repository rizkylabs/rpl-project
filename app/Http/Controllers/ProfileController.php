<?php

namespace App\Http\Controllers;

use App\Support\PortalAccess;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();
        return view('profile.index', PortalAccess::resolve($user) + ['profileUser' => $user]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        PortalAccess::resolve($user);

        $data = $request->validateWithBag('profile', [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user)],
            'current_password' => ['required', 'string', 'max:72', 'current_password:web'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Masukkan alamat email yang valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
            'current_password.required' => 'Masukkan password saat ini.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'current_password.max' => 'Password maksimal 72 karakter.',
        ]);

        $user->fill(['name' => $data['name'], 'email' => $data['email']]);
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        try {
            $user->save();
        } catch (UniqueConstraintViolationException $exception) {
            return redirect()->route('profile')->withErrors(['email' => 'Email sudah digunakan oleh akun lain.'], 'profile')->withInput($request->only('name', 'email'));
        }

        return redirect()->route('profile')->with('profile_status', 'Informasi akun berhasil diperbarui.');
    }

    public function password(Request $request): RedirectResponse
    {
        $user = $request->user();
        PortalAccess::resolve($user);

        $data = $request->validateWithBag('security', [
            'current_password' => ['required', 'string', 'max:72', 'current_password:web'],
            'password' => ['required', 'string', 'min:12', 'max:72', 'different:current_password', 'confirmed',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (is_string($value) && strlen($value) > 72) {
                        $fail('Password maksimal 72 byte.');
                    }
                },
            ],
        ], [
            'current_password.required' => 'Masukkan password saat ini.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'current_password.max' => 'Password maksimal 72 karakter.',
            'password.required' => 'Masukkan password baru.',
            'password.min' => 'Password baru minimal 12 karakter.',
            'password.max' => 'Password maksimal 72 karakter.',
            'password.different' => 'Password baru harus berbeda dari password saat ini.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        // The User model hashes the password through its existing hashed cast.
        $user->forceFill(['password' => $data['password'], 'remember_token' => Str::random(60)])->save();
        $request->session()->regenerate();

        return redirect()->route('profile')->with('security_status', 'Password berhasil diperbarui. Gunakan password baru saat login berikutnya.');
    }
}
