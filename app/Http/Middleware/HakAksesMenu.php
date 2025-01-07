<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\ManagementSystem\Menu;
use Symfony\Component\HttpFoundation\Response;

class HakAksesMenu
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

            $userMenus = Menu::getMenuByActiveUser($user);
            if (empty($userMenus)) {
                if ($request->path() === 'dashboard' || $request->isMethod('GET')) {
                    return $next($request);
                }
                return redirect('/dashboard')->with('info', 'Tidak ada menu yang tersedia. Anda diarahkan ke dashboard.');
            }

            $menuPaths = $this->extractPaths($userMenus);

            $currentPath = '/' . ltrim($request->path(), '/');

            if ($currentPath === '/dashboard') {
                return $next($request);
            }

            // Check if the path is allowed
            if (!$this->isPathAllowed($currentPath, $menuPaths)) {
                return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
            }

            return $next($request);
        } catch (\Throwable $e) {
            return redirect('/dashboard')->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    /**
     * Extract menu paths from menu data.
     *
     * @param array $menus
     * @return array<string>
     */
    private function extractPaths($menus): array
    {
        $paths = [];

        foreach ($menus as $menu) {
            if (!empty($menu['path'])) {
                // Convert dynamic paths (e.g., /product-list/{slug}) to regex patterns
                $regexPath = preg_replace('/\{\w+\}/', '[^/]+', '/' . ltrim($menu['path'], '/'));
                $paths[] = $regexPath;
            }

            if (!empty($menu['children'])) {
                $children = is_array($menu['children']) ? $menu['children'] : $menu['children']->toArray();
                $paths = array_merge($paths, $this->extractPaths($children));
            }
        }

        return $paths;
    }

    /**
     * Check if the current path is allowed.
     *
     * @param string $currentPath
     * @param array $menuPaths
     * @return bool
     */
    private function isPathAllowed(string $currentPath, array $menuPaths): bool
    {
        foreach ($menuPaths as $menuPath) {
            if (!empty($menuPath) && strpos($currentPath, $menuPath) === 0) {
                return true;
            }
        }

        return false;
    }
}
