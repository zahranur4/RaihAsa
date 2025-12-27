<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // Tampilkan form login (GET /login)
    public function showLogin()
    {
        return view('login.index');
    }

    // Proses login (POST /login)
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => ['required','string','min:8','regex:/^(?=.*[0-9])(?=.*[a-zA-Z]).{8,}$/'],
        ], [
            'password.regex' => 'Password harus minimal 8 karakter dan mengandung huruf serta angka.',
        ]);

        $user = DB::table('users')->where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.'])->withInput();
        }

        if (! Hash::check($request->password, $user->kata_sandi)) {
            return back()->withErrors(['password' => 'Password salah.'])->withInput();
        }

        // Login manual menggunakan model User agar session bekerja
        $userModel = \App\Models\User::find($user->id);
        Auth::login($userModel, $request->boolean('remember'));

        return redirect()->intended('/home');
    }

    // Proses logout (POST /logout)
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
