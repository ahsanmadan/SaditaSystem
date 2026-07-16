<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string'],
        ], [
            'password.required' => 'Password wajib diisi.',
        ]);

        $emailOrUsername  = $request->input('email', '');
        $recaptchaToken   = $request->input('g-recaptcha-response', '');

        // ── reCAPTCHA Verification ─────────────────────────────────────────────
        // Test keys: 6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI (site)
        //            6LeIxAcTAAAAAGG-vFI1TnRWxMHv6KVkoB0Z7IcC (secret)
        // Di local: cukup pastikan token ada (widget sudah handle UX di client).
        // Di production: verifikasi ke Google API.
        if (! app()->runningUnitTests() && empty($recaptchaToken)) {
            return back()->withErrors([
                'captcha' => 'Mohon selesaikan verifikasi reCAPTCHA terlebih dahulu.',
            ])->onlyInput('email');
        }

        if (! app()->runningUnitTests() && app()->isProduction()) {
            try {
                $verify = Http::timeout(5)->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret'   => config('services.recaptcha.secret', '6LeIxAcTAAAAAGG-vFI1TnRWxMHv6KVkoB0Z7IcC'),
                    'response' => $recaptchaToken,
                    'remoteip' => $request->ip(),
                ]);

                if (! $verify->json('success')) {
                    return back()->withErrors([
                        'captcha' => 'Verifikasi reCAPTCHA tidak valid. Silakan coba lagi.',
                    ])->onlyInput('email');
                }
            } catch (\Exception $e) {
                // Jika API Google tidak terjangkau, tolak akses
                return back()->withErrors([
                    'captcha' => 'Layanan verifikasi tidak tersedia. Coba lagi.',
                ])->onlyInput('email');
            }
        }
        // Di local/staging: token ada = cukup (test keys selalu lolos di client-side)
        // ──────────────────────────────────────────────────────────────────────────

        if (Auth::attempt(['email' => $emailOrUsername, 'password' => $request->input('password')])) {
            $user = Auth::user();

            if (! $user?->isActive()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akun Anda sedang dinonaktifkan. Silakan hubungi administrator.',
                ])->onlyInput('email');
            }

            $canAccessAdminPanel = in_array($user?->role, ['owner', 'admin', 'staff'], true);

            if (! $canAccessAdminPanel) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Anda tidak memiliki hak akses untuk masuk ke panel admin.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            return redirect()->intended('/admin');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Alamat email tidak terdaftar dalam sistem.',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Link reset password telah dikirim ke email Anda.');
        }

        $message = match ($status) {
            Password::INVALID_USER => 'Alamat email tidak terdaftar dalam sistem.',
            Password::RESET_THROTTLED => 'Harap tunggu beberapa saat sebelum mencoba kembali.',
            default => 'Gagal mengirimkan link reset password.',
        };

        return back()->withErrors(['email' => $message]);
    }

    public function showResetPassword(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Password Anda telah berhasil diubah. Silakan masuk menggunakan password baru.');
        }

        $message = match ($status) {
            Password::INVALID_USER => 'Alamat email tidak terdaftar.',
            Password::INVALID_TOKEN => 'Token reset password ini tidak valid atau sudah kedaluwarsa.',
            Password::INVALID_PASSWORD => 'Password tidak memenuhi kriteria keamanan.',
            default => 'Gagal merubah password. Silakan hubungi administrator.',
        };

        return back()->withErrors(['email' => $message]);
    }
}
