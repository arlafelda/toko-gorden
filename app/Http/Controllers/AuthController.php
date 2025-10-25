<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('landing');
        }
        return view('User.loginUser');
    }

    // Login manual
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials['email'] = strtolower(trim($credentials['email']));

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('landing'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    // Halaman register
    public function showRegisterForm()
    {
        return view('User.RegisterUser');
    }

    // Register user baru
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => strtolower(trim($request->email)),
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login.');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // =========================
    // Google OAuth
    // =========================

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            /** @var \Laravel\Socialite\Two\AbstractProvider $provider */
            $provider = Socialite::driver('google');
            $googleUser = $provider->stateless()->user();

            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => strtolower($googleUser->getEmail()),
                    'phone_number' => null,
                    'password' => Hash::make(uniqid()),
                    'google_id' => $googleUser->getId(),
                ]);
            } elseif (!$user->google_id) {
                $user->update(['google_id' => $googleUser->getId()]);
            }

            Auth::login($user);

            return redirect()->route('landing');
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'google' => 'Login Google gagal: ' . $e->getMessage()
            ]);
        }
    }

    // =========================
    // Lupa Password
    // =========================

    public function showLinkRequestForm()
    {
        return view('User.LupaPassword');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $email = strtolower(trim($request->email));

        $status = Password::sendResetLink(['email' => $email]);

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => 'Link reset password berhasil dikirim ke email.'])
            : back()->withErrors(['email' => 'Email tidak ditemukan atau belum terdaftar.']);
    }

    public function showResetForm(Request $request, $token)
    {
        return view('User.PasswordBaru', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        Auth::logout();

        $status = Password::reset(
            [
                'email' => strtolower(trim($request->email)),
                'password' => $request->password,
                'password_confirmation' => $request->password_confirmation,
                'token' => $request->token,
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Kata sandi berhasil diubah.')
            : back()->withErrors(['email' => __($status)]);
    }
}
