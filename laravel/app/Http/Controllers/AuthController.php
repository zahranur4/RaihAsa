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
        if (Auth::check()) {
            return redirect()->route('home');
        }
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

        // If the user is linked to a panti (recipient), send them to the panti dashboard
        $isRecipient = \Illuminate\Support\Facades\DB::table('panti_asuhan')->where('user_id', $user->id)->exists();
        if ($isRecipient) {
            return redirect()->intended(route('panti.dashboard'));
        }

        // Otherwise go to home page
        return redirect()->intended(route('home'));
    }

    // Proses logout (POST /logout)
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    // Tampilkan form forgot password (GET)
    public function showForgotPassword()
    {
        return view('forgot-password.request-email');
    }

    // Proses request password reset - validasi email
    public function processForgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Check if email exists
        $user = DB::table('users')->where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.'])->withInput();
        }

        // Store email in session to proceed to reset password form
        $request->session()->put('forgot_password_email', $request->email);

        return redirect()->route('reset-password-form');
    }

    // Tampilkan form reset password
    public function showResetPasswordForm()
    {
        $email = session('forgot_password_email');

        if (!$email) {
            return redirect()->route('forgot-password')->withErrors(['session' => 'Session expired. Please try again.']);
        }

        return view('forgot-password.reset-password', compact('email'));
    }

    // Proses reset password
    public function processResetPassword(Request $request)
    {
        $email = session('forgot_password_email');

        if (!$email) {
            return redirect()->route('forgot-password')->withErrors(['session' => 'Session expired. Please try again.']);
        }

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'regex:/^(?=.*[0-9])(?=.*[a-zA-Z]).{8,}$/', 'confirmed'],
            'password_confirmation' => 'required',
        ], [
            'password.regex' => 'Password harus minimal 8 karakter dan mengandung huruf serta angka.',
            'password.confirmed' => 'Password dan konfirmasi password tidak cocok.',
        ]);

        // Update password in database
        DB::table('users')
            ->where('email', $email)
            ->update([
                'kata_sandi' => Hash::make($request->password),
                'updated_at' => now(),
            ]);

        // Clear the session
        $request->session()->forget('forgot_password_email');

        return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan masuk dengan password baru Anda.');
    }
}
