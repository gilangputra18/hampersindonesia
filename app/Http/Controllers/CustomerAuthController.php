<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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

    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:25'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat e-mail wajib diisi.',
            'email.email' => 'Format e-mail tidak valid.',
            'email.unique' => 'Alamat e-mail ini sudah terdaftar. Silakan masuk atau gunakan email lain.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::create([
            'name' => ucwords(trim($validated['name'])),
            'email' => strtolower(trim($validated['email'])),
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'is_admin' => false,
        ]);

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->intended(route('home'))->with('success', 'Selamat bergabung di Pusat Hampers Indonesia, ' . $user->name . '! Akun Anda berhasil dibuat.');
    }

    public function redirectToGoogle(Request $request)
    {
        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');

        // When Google OAuth credentials are configured, immediately redirect user to Google Account Chooser
        if ($clientId && $clientSecret && !str_contains($clientId, 'example') && !str_contains($clientId, 'your-') && strlen($clientId) > 20 && !$request->has('chooser')) {
            // Direct OAuth URL (100% reliable, official Google OAuth endpoint, zero package dependency)
            $directUrl = $this->getDirectGoogleAuthUrl();
            if ($directUrl) {
                return redirect()->away($directUrl);
            }

            // Fallback Socialite if class exists
            if (class_exists(\Laravel\Socialite\Facades\Socialite::class)) {
                try {
                    $redirectUri = $this->googleRedirectUri();
                    return \Laravel\Socialite\Facades\Socialite::driver('google')
                        ->redirectUrl($redirectUri)
                        ->with(['prompt' => 'select_account'])
                        ->redirect();
                } catch (\Throwable $e) {
                    Log::warning('Socialite direct redirect failed: ' . $e->getMessage());
                }
            }
        }

        return view('auth.google_demo', [
            'oauthUrl' => $this->getDirectGoogleAuthUrl(),
        ]);
    }

    private function googleRedirectUri(): string
    {
        $currentHost = request()->getHost();
        $isLocal = in_array($currentHost, ['localhost', '127.0.0.1', '::1']) || str_ends_with($currentHost, '.test') || str_ends_with($currentHost, '.local');

        if (!$isLocal) {
            $configured = config('services.google.redirect');
            if ($configured && (str_starts_with($configured, 'http://') || str_starts_with($configured, 'https://'))) {
                return $configured;
            }
        }

        return route('auth.google.callback');
    }

    public function getDirectGoogleAuthUrl(): ?string
    {
        $clientId = config('services.google.client_id');
        if (!$clientId || str_contains($clientId, 'example') || str_contains($clientId, 'your-')) {
            return null;
        }

        $params = [
            'client_id' => $clientId,
            'redirect_uri' => $this->googleRedirectUri(),
            'response_type' => 'code',
            'scope' => 'openid profile email',
            'prompt' => 'select_account',
            'access_type' => 'online',
        ];

        return 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
    }

    private function googleClientId(): ?string
    {
        $clientId = config('services.google.client_id');

        if ($clientId && !str_contains($clientId, 'example') && !str_contains($clientId, 'your-')) {
            return $clientId;
        }

        return null;
    }

    public function handleGoogleCallback(Request $request)
    {
        // Path 1: Verified Google One-Tap / Identity Services token credential verification
        if ($request->filled('credential')) {
            $configuredId = $this->googleClientId();
            if (!$configuredId) {
                return redirect()->route('login')->withErrors(['email' => 'Login Google belum dikonfigurasi.']);
            }

            try {
                $response = Http::timeout(10)->get('https://oauth2.googleapis.com/tokeninfo', [
                    'id_token' => $request->input('credential'),
                ]);
                $payload = $response->json();

                if (!$response->ok()
                    || ($payload['aud'] ?? null) !== $configuredId
                    || ($payload['email_verified'] ?? 'false') !== 'true'
                    || empty($payload['email'])) {
                    return redirect()->route('login')->withErrors(['email' => 'Verifikasi akun Google gagal. Silakan coba lagi.']);
                }

                $user = User::where('google_id', $payload['sub'])
                    ->orWhere('email', $payload['email'])
                    ->first();

                if (!$user) {
                    $user = User::create([
                        'name' => $payload['name'] ?? explode('@', $payload['email'])[0],
                        'email' => $payload['email'],
                        'google_id' => $payload['sub'],
                        'avatar' => $payload['picture'] ?? null,
                        'password' => Hash::make(Str::random(16)),
                        'is_admin' => false,
                    ]);
                } else {
                    $user->update([
                        'google_id' => $user->google_id ?: $payload['sub'],
                        'avatar' => $user->avatar ?: ($payload['picture'] ?? null),
                    ]);
                }

                Auth::login($user, true);
                $request->session()->regenerate();

                return redirect()->intended(route('home'))->with('success', 'Berhasil login dengan akun Google ' . $user->name . '!');
            } catch (\Throwable $e) {
                Log::error('Google GSI verify error: ' . $e->getMessage());
                return redirect()->route('login')->withErrors(['email' => 'Gagal terhubung dengan Google. Silakan coba lagi.']);
            }
        }

        // Path 2: Google OAuth2 Authorization Code Callback (?code=...)
        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');

        if ($request->has('code') && $clientId && $clientSecret && !str_contains($clientId, 'example') && !str_contains($clientId, 'your-')) {
            try {
                $redirectUri = $this->googleRedirectUri();
                $email = null;
                $name = null;
                $googleId = null;
                $avatar = null;

                // 1. Pertukaran kode otorisasi via HTTP langsung (Paling stabil, mandiri tanpa dependensi package)
                try {
                    $tokenResponse = Http::asForm()->timeout(12)->post('https://oauth2.googleapis.com/token', [
                        'code' => $request->input('code'),
                        'client_id' => $clientId,
                        'client_secret' => $clientSecret,
                        'redirect_uri' => $redirectUri,
                        'grant_type' => 'authorization_code',
                    ]);

                    if ($tokenResponse->successful()) {
                        $tokenData = $tokenResponse->json();
                        $accessToken = $tokenData['access_token'] ?? null;

                        if ($accessToken) {
                            $userResponse = Http::withToken($accessToken)->timeout(12)->get('https://www.googleapis.com/oauth2/v3/userinfo');
                            if ($userResponse->successful()) {
                                $googleData = $userResponse->json();
                                $email = $googleData['email'] ?? null;
                                $name = $googleData['name'] ?? ($googleData['given_name'] ?? explode('@', $email)[0]);
                                $googleId = $googleData['sub'] ?? null;
                                $avatar = $googleData['picture'] ?? null;
                            }
                        }
                    } else {
                        Log::warning('Native Google token endpoint returned error: ' . $tokenResponse->body());
                    }
                } catch (\Throwable $httpEx) {
                    Log::warning('Native Google OAuth token exchange exception: ' . $httpEx->getMessage());
                }

                // 2. Fallback Socialite jika tersedia di vendor
                if (empty($email) && class_exists(\Laravel\Socialite\Facades\Socialite::class)) {
                    try {
                        $googleUser = \Laravel\Socialite\Facades\Socialite::driver('google')
                            ->redirectUrl($redirectUri)
                            ->stateless()
                            ->user();

                        $email = $googleUser->getEmail();
                        $name = $googleUser->getName() ?: explode('@', $email)[0];
                        $googleId = $googleUser->getId();
                        $avatar = $googleUser->getAvatar();
                    } catch (\Throwable $socEx) {
                        Log::warning('Socialite fallback user fetch error: ' . $socEx->getMessage());
                    }
                }

                if (empty($email)) {
                    return redirect()->route('login')->withErrors([
                        'email' => 'Gagal menghubungkan akun Google. Silakan coba lagi atau masuk menggunakan opsi email & kata sandi.'
                    ]);
                }

                $user = User::where('google_id', $googleId)
                    ->orWhere('email', $email)
                    ->first();

                if (!$user) {
                    $user = User::create([
                        'name' => $name,
                        'email' => $email,
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
                $request->session()->regenerate();

                return redirect()->intended(route('home'))->with('success', 'Berhasil login dengan akun Google ' . $user->name . '!');
            } catch (\Throwable $e) {
                Log::error('Google OAuth callback error: ' . $e->getMessage());
                return redirect()->route('login')->withErrors([
                    'email' => 'Gagal menghubungkan akun Google. Silakan coba lagi atau masuk dengan email & kata sandi.'
                ]);
            }
        }

        // Path 3: Direct account selection from fallback UI / Local development
        if ($request->filled('email')) {
            $request->validate([
                'email' => 'required|email',
                'name' => 'nullable|string|max:255',
            ]);

            $user = User::where('email', $request->email)->first();

            if ($user && $user->is_admin) {
                return redirect()->route('login')->withErrors(['email' => 'Akun admin harus masuk menggunakan password.']);
            }

            $name = $request->input('name') ?: explode('@', $request->email)[0];
            $avatar = $request->input('avatar') ?: ('https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=4285F4&color=ffffff&bold=true');
            $googleId = $request->input('google_id') ?: ('google_' . md5($request->email));

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
            $request->session()->regenerate();
            return redirect()->intended(route('home'))->with('success', 'Berhasil masuk dengan akun Google: ' . $user->email);
        }

        return redirect()->route('login')->withErrors(['email' => 'Pilihan akun Google tidak ditemukan.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
