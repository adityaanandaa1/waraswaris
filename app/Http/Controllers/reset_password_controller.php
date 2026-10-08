<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;

class reset_password_controller extends Controller
{
    public function tampilkan_reset_password()
    {
        return view('auth.lupa_password');
    }

    public function kirim_reset_email(Request $request)
    {
        $request->validate(['email'=> 'required|email']);

        $status = password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))      // pesan sukses
            : back()->withErrors(['email' => __($status)]); // pesan error
    }

    public function tampilkan_form_reset(string $token, Request $request)
    {
        return view ('auth.lupa_password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function simpan_password(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $status = password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        return $status === password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}
