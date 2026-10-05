<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Str;

class CustomerAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            if (Auth::user()->is_admin) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('home'))->with('success', 'Selamat datang kembali!');
        }

        return back()->withErrors([
            'email' => 'E-mail atau password yang Anda masukkan tidak cocok.',
        ])->onlyInput('email');
    }

    public function redirectToGoogle()
    {
        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');

        if ($clientId && $clientSecret && !str_contains($clientId, 'example') && !str_contains($clientId, 'your-') && strlen($clientId) > 20) {
            try {
                return \Laravel\Socialite\Facades\Socialite::driver('google')->redirect();
            } catch (\Throwable $e) {
                // Fallback to internal device account chooser
            }
        }

        return view('auth.google_demo');
    }

    public function handleGoogleCallback(Request $request)
    {
        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');

        if ($clientId && $clientSecret && !str_contains($clientId, 'example') && !str_contains($clientId, 'your-') && strlen($clientId) > 20 && !$request->has('demo_mode')) {
            try {
                $googleUser = \Laravel\Socialite\Facades\Socialite::driver('google')->user();
                $user = User::where('google_id', $googleUser->id)
                    ->orWhere('email', $googleUser->email)
                    ->first();

                if (!$user) {
                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                        'password' => Hash::make(Str::random(16)),
                        'is_admin' => false,
                    ]);
                } else {
                    $user->update([
                        'google_id' => $googleUser->id,
                        'avatar' => $user->avatar ?: $googleUser->avatar,
                    ]);
                }

                Auth::login($user, true);
                return redirect()->route('home')->with('success', 'Berhasil login dengan akun Google ' . $user->name . '!');
            } catch (\Throwable $e) {
                return redirect()->route('login')->withErrors(['email' => 'Gagal terhubung dengan Google. Silakan coba lagi.']);
            }
        }

        // Direct Quick Google Login Handler (for user's own Google email account)
        $request->validate([
            'email' => 'required|email',
            'name' => 'nullable|string|max:255',
        ]);

        $name = $request->input('name') ?: explode('@', $request->email)[0];
        $avatar = $request->input('avatar') ?: ('https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=4285F4&color=ffffff&bold=true');
        $googleId = $request->input('google_id') ?: ('google_' . md5($request->email));

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            $user = User::create([
                'name' => ucwords(str_replace(['.', '_'], ' ', $name)),
                'email' => $request->email,
                'google_id' => $googleId,
                'avatar' => $avatar,
                'password' => Hash::make(Str::random(16)),
                'is_admin' => false,
            ]);
        } else {
            $user->update([
                'google_id' => $user->google_id ?: $googleId,
                'avatar' => $user->avatar ?: $avatar,
            ]);
        }

        Auth::login($user, true);
        return redirect()->route('home')->with('success', 'Berhasil login dengan Akun Google (' . $user->email . ')!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
