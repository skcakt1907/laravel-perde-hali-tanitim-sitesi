<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('admin.profile', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name'             => 'required|string|max:120',
            'email'            => 'required|email|max:120|unique:users,email,' . $user->id,
            'phone'            => 'nullable|string|max:30',
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password'         => ['nullable', 'confirmed', Password::min(10)->letters()->numbers()],
        ], [
            'current_password.current_password' => 'Mevcut şifreniz hatalı.',
            'current_password.required_with'    => 'Şifre değiştirmek için mevcut şifrenizi girin.',
            'password.confirmed'                => 'Yeni şifre tekrarı eşleşmiyor.',
            'password.min'                      => 'Yeni şifre en az 10 karakter olmalı.',
            'password.letters'                  => 'Yeni şifre en az bir harf içermeli.',
            'password.numbers'                  => 'Yeni şifre en az bir rakam içermeli.',
        ]);

        // role mass-assign edilmez; alanlar explicit atanır.
        $user->name  = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? null;

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return back()->with('success', 'Profiliniz güncellendi.');
    }
}
