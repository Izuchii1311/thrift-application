<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\ManagementSystem\Menu;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Request $request): void
    {
        Paginator::useBootstrap();

        View::composer('layouts.dashboard_components.sidebar', function ($view) {
            $menus = Menu::getMenuByActiveUser(Auth::user());
            $view->with('menus', $menus);
        });

        View::composer('layouts.dashboard_components.toolbar', function ($view) {
            $menus = Menu::getMenuByActiveUser(Auth::user());
        
            $currentPath = request()->path();
        
            // Fungsi untuk "flatten" (meratakan) struktur menu yang hierarkis menjadi array datar
            $flattenMenus = function ($menus) use (&$flattenMenus) {
                $flat = [];
                foreach ($menus as $menu) {
                    // Menambahkan menu saat ini ke array datar
                    $flat[] = $menu;
        
                    // Jika menu memiliki children (menu turunan), lakukan flatten pada children-nya secara rekursif
                    if (!empty($menu['children'])) {
                        $flat = array_merge($flat, $flattenMenus($menu['children']));
                    }
                }
                return $flat;
            };
        
            // Menggunakan fungsi flattenMenus untuk meratakan seluruh menu yang didapatkan
            $flatMenus = $flattenMenus($menus);
        
            // Fungsi untuk mencari menu yang sedang aktif berdasarkan path URL yang sedang diakses
            $findActiveMenu = function ($menus) use ($currentPath) {
                foreach ($menus as $menu) {
                    // Mencocokkan apakah path menu dimulai dengan path yang sedang diakses
                    if ($menu['path'] && str_starts_with($currentPath, trim($menu['path'], '/'))) {
                        return $menu;
                    }
                }
                return null;
            };
        
            // Fungsi untuk mendapatkan hierarki breadcrumbs berdasarkan menu aktif
            $getBreadcrumbs = function ($menu, $flatMenus) {
                $breadcrumbs = [];

                // Selama menu masih ada, lakukan pencarian breadcrumbs
                while ($menu) {
                    // Menambahkan menu saat ini ke posisi awal array breadcrumbs
                    // Fungsi array_unshift menambah elemen di awal array, 
                    // sehingga menu tertinggi (parent) akan berada di urutan pertama.
                    array_unshift($breadcrumbs, $menu);

                    // Mencari menu induk (parent) dari menu saat ini dengan mencari berdasarkan parent_id
                    // 'firstWhere' mencari elemen pertama di $flatMenus yang id-nya sesuai dengan parent_id menu saat ini
                    // Proses ini akan mencari menu induk (parent) secara rekursif.
                    $menu = collect($flatMenus)->firstWhere('id', $menu['parent_id']);
                }

                return $breadcrumbs;
            };

            // Menemukan menu yang sedang aktif
            $activeMenu = $findActiveMenu($flatMenus);
            
            // Menentukan breadcrumbs jika menu aktif ditemukan, jika tidak breadcrumbs akan kosong
            $breadcrumbs = $activeMenu ? $getBreadcrumbs($activeMenu, $flatMenus) : [];
        
            $view->with('menus', $menus)
                 ->with('activeMenu', $activeMenu)
                 ->with('breadcrumbs', $breadcrumbs);
        });        

        View::composer('layouts.dashboard_components.footer', function ($view) {
            $roles = Auth::user()->roles->map(function ($data) {
                return [
                    'id'           => $data->id,
                    'role_name'    => $data->role_name,
                    'display_name' => $data->display_name,
                ];
            })->toArray();

            $roleActiveId = Auth::user()
                ->roles
                ->firstWhere('pivot.is_active', true)
                ->pivot->role_id;

            if (!$roleActiveId) {
                return null;
            }

            $view->with([
                'roles'          => $roles,
                'role_active_id' => $roleActiveId,
            ]);
        });

        View::composer('*', function ($view) {
            $currentPath = '/' . request()->path();
            $menu = Menu::where('path', $currentPath)->first();

            if ($menu) {
                $view->with('menu_path', $menu);
            } else {
                $view->with('menu_path', null);
            }
        });
    }
}
