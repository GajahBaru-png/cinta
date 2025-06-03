<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Guru;
use App\Models\Siswa;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration page.
     */
    public function create(): Response
    {
        return Inertia::render('auth/register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $isSiswa = siswa::where('email', $request->email)->exists();
        $isGuru = guru::where('email', $request->email)->exists();

        if (!$isSiswa && !$isGuru) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan di daftar siswa atau guru.',
            ])->withInput();
        }

        // Buat user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Beri role sesuai asal data
        if ($isSiswa) {
            $user->assignRole('siswa');
        } elseif ($isGuru) {
            $user->assignRole('guru');
        }

        event(new Registered($user));

        Auth::login($user);

        return to_route('dashboard');
    }
}
