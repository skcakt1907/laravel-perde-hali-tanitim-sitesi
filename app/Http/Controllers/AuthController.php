<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Yalnızca yönetim girişi. Tanıtım sitesi olduğu için müşteri üyeliği yoktur.
 */
class AuthController extends Controller
{
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

        if (! Auth::attempt($data, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'E-posta veya şifre hatalı.',
            ]);
        }

        $request->session()->regenerate();

        if (! Auth::user()->isAdmin()) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => 'Bu hesabın yönetim paneline erişimi yok.',
            ]);
        }

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
