<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function loginView()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            $rules = [
                'email'     => 'required|email',
                'password'  => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return back()->withErrors($validator->errors())->withInput();
            }

            $credentials = $validator->validate();

            $user = User::where('email', $credentials['email'])->first();

            if (!$user) {
                return back()->withErrors([
                    'error' => 'Akun tidak ditemukan, silahkan registrasi akun baru.'
                ]);
            }

            if (!$user->is_active) {
                return back()->withErrors([
                    'error' => 'Status akun tidak aktif, saat ini anda tidak bisa login menggunakan akun ini.'
                ]);
            }

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->intended(route('dashboard.index'));
            }

            return back()->withErrors([
                'error' => 'Email atau kata sandi tidak sesuai.'
            ])->withInput();
        } catch (\Throwable $th) {
            return back()->withErrors([
                'error' => 'Terjadi kesalahan pada sistem, silahkan coba lagi nanti.'
            ]);
        }
    }

    public function logout()
    {
        $userId = Auth::id();

        Cache::forget("user-is-online-{$userId}");
        Cache::forget("user-last-seen-{$userId}");

        session()->invalidate();
        session()->regenerateToken();

        Auth::logout();
        return redirect()->route('login');
    }
}
