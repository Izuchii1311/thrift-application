<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ManagementSystem\Role;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    # Register View
    public function registerView()
    {
        return view('auth.register');
    }

    # Register Method
    public function register(Request $request)
    {
        try {
            $rules = [
                'username' => 'required|string|max:12|unique:users,username',
                'name'     => 'required|string|max:25',
                'email'    => 'required|email|unique:users,email',
                'password' => 'required|min:8|confirmed'
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return back()->withErrors($validator->errors())->withInput();
            }
    
            $validated = $validator->validated();
    
            $user_exists = User::where('email', $validated['email'])->first();
            if ($user_exists) {
                return back()->withErrors(['error' => 'Akun Anda sudah terdaftar. Silakan lakukan login.']);
            }
    
            $user = User::create([
                'username'          => $validated['username'],
                'name'              => $validated['name'],
                'email'             => $validated['email'],
                'password'          => bcrypt($validated['password']),
                'profile_picture'   => null,
                'is_active'         => true,
            ]);
    
            $role = Role::where('role_name', 'customer')->first();
            if (!$role) {
                return back()->withErrors(['error' => 'Terjadi kesalahan sistem. Silakan hubungi administrator.']);
            }
    
            $user->roles()->attach($role->id, ['is_active' => true]);
            return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login dengan akun Anda.');
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Terjadi kesalahan pada sistem, silakan coba lagi nanti.']);
        }
    }
    
}
