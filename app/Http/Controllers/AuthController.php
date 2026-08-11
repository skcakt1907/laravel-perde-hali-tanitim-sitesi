<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Yalnızca yönetim girişi. Tanıtım sitesi olduğu için müşteri üyeliği yoktur.
 *
 * Kaba kuvvete karşı iki katman:
 *  1) rotada `throttle:5,1` — IP başına dakikada 5 istek
 *  2) burada e-posta+IP başına 5 BAŞARISIZ deneme → 1 dakika kilit
 * İkincisi gerekli çünkü ilki başarılı/başarısız ayrımı yapmaz; ikincisi
 * yalnızca yanlış denemeleri sayar ve hedefli saldırıyı yavaşlatır.
 */
class AuthController extends Controller
{
    private const MAX_DENEME = 5;
    private const KILIT_SANIYE = 60;

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $anahtar = $this->limitAnahtari($request);

        if (RateLimiter::tooManyAttempts($anahtar, self::MAX_DENEME)) {
            $saniye = RateLimiter::availableIn($anahtar);

            throw ValidationException::withMessages([
                'email' => "Çok fazla hatalı deneme. {$saniye} saniye sonra tekrar deneyin.",
            ]);
        }

        if (! Auth::attempt($data, $request->boolean('remember'))) {
            RateLimiter::hit($anahtar, self::KILIT_SANIYE);

            // Hesabın var olup olmadığını sızdırmamak için tek ve genel mesaj
            throw ValidationException::withMessages([
                'email' => 'E-posta veya şifre hatalı.',
            ]);
        }

        if (! Auth::user()->isAdmin()) {
            Auth::logout();
            RateLimiter::hit($anahtar, self::KILIT_SANIYE);

            throw ValidationException::withMessages([
                'email' => 'E-posta veya şifre hatalı.',
            ]);
        }

        RateLimiter::clear($anahtar);

        // Oturum sabitleme (session fixation) önlemi
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function limitAnahtari(Request $request): string
    {
        return 'giris:' . Str::lower((string) $request->input('email')) . '|' . $request->ip();
    }
}
