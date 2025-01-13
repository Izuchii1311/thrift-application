<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\ManagementSystem\Role;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
            }

            $activeRole = $user->roles->firstWhere('pivot.is_active', true);

            if (!$activeRole) {
                Log::warning('Role aktif tidak ditemukan untuk user ID: ' . $user->id);
                return redirect('/login')->with('error', 'Terjadi kesalahan pada sistem. Silakan hubungi administrator.');
            }

            if ($activeRole->role_name === 'customer' || $activeRole->type_role === 'Customer') {
                return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
            }

            return $next($request);
        } catch (\Throwable $e) {
            return redirect('/')->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }
}
