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
    # Login View
    public function loginView()
    {
        return view('auth.login');
    }

    # Login Method
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
                return back()->withErrors(['error' => 'Akun tidak ditemukan, silakan registrasi akun baru.']);
            }
    
            if (!$user->is_active) {
                return back()->withErrors(['error' => 'Status akun tidak aktif, saat ini Anda tidak bisa login menggunakan akun ini.']);
            }
    
            $activeRole = $user->roles->firstWhere('pivot.is_active', true);
    
            if (!$activeRole) {
                return back()->withErrors(['error' => 'Akun Anda tidak memiliki role aktif. Silakan hubungi administrator.']);
            }
    
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                if ($activeRole->role_name === 'customer' || $activeRole->type_role === 'Customer') {
                    return redirect()->route('landing-index');
                }
    
                return redirect()->intended(route('dashboard.index'));
            }

            return back()->withErrors([ 'error' => 'Email atau kata sandi tidak sesuai.' ])->withInput();
        } catch (\Throwable $th) {
            return back()->withErrors([ 'error' => 'Terjadi kesalahan pada sistem, silahkan coba lagi nanti.' ]);
        }
    }

    # Logout Method
    public function logout()
    {
        session()->invalidate();
        session()->regenerateToken();
        Auth::logout();
        return redirect()->route('landing-index');
    }
}
