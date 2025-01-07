<?php

namespace App\Http\Controllers\ManagementSystem;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\ManagementSystem\Menu;
use App\Models\ManagementSystem\Role;
use Illuminate\Support\Facades\Validator;
use App\Models\ManagementSystem\Permission;
use App\Models\ManagementSystem\RoleMenuPermission;
use Illuminate\Support\Facades\Crypt;

class MenuHakAksesController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Urutkan permission actions dengan urutan tetap untuk view, create, update, delete
        $priority_actions = ['view', 'create', 'update', 'delete'];
        $additional_actions = Permission::select('permission_action')
            ->whereNotIn('permission_action', $priority_actions)
            ->groupBy('permission_action')
            ->get()
            ->map(function ($action) {
                return $action->permission_action;
            });

        // Gabungkan prioritas actions dengan tambahan (diurutkan manual)
        $permission_actions = collect($priority_actions)->merge($additional_actions);

        // Ambil menu dan permissions terkait
        $menus = Menu::getMenuPermissionsByActiveUser(null);

        $roles = Role::where('role_name', '!=', 'customer')
        ->get()
        ->map(function ($role) {
            $role->encrypted_id = Crypt::encrypt($role->id);
            return $role;
        });
        $currentRoleId  = Auth::user()->roles->firstWhere('pivot.is_active', true)->id;
        $allowedRoles = ['admin', 'superadmin'];

        // Periksa apakah pengguna memiliki salah satu role yang diizinkan
        $userRoles = Auth::user()->roles->pluck('role_name')->toArray();
        $accessModifyData = array_intersect($allowedRoles, $userRoles);

        if ($roles->isEmpty() || !$currentRoleId) {
            $roles = [];
            $currentRoleId = null;
        }

        $menu_access = $this->getMenuPermissionsByKey('menu-hak-akses');

        return view('dashboard.management_system.menu_hak_akses.index', compact('permission_actions', 'menus', 'roles', 'currentRoleId', 'accessModifyData', 'menu_access'));
    }

    public function fetchMenusByRole(Request $request)
    {
        try {
            $roleId = $request->input('role_id');
            $roleId = Crypt::decrypt($roleId);

            if (!$roleId || !Role::where('id', $roleId)->exists()) {
                return $this->api_response_error('Role tidak ditemukan.', []);
            }

            $menus = $roleId
                    ? Menu::getMenuPermissionsByActiveUser($roleId)
                    : Menu::getMenuPermissionsByActiveUser(null);

            return $this->api_response_success('Data menu berhasil diambil.', $menus);
        } catch (\Throwable $th) {
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function update(Request $request)
    {
        try {
            $roleId         = Crypt::decrypt($request->input('role_id'));
            $permissionId   = Crypt::decrypt($request->input('permission_id'));

            $rules = [
                'is_allowed' => 'required|boolean'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->api_response_validator('Silahkan periksa kembali data yang anda isi.', []);
            }

            if (!Role::where('id', $roleId)->exists()) {
                return $this->api_response_error('Role tidak ditemukan.', []);
            }

            if (!RoleMenuPermission::where('id', $permissionId)->exists()) {
                return $this->api_response_error('Permission tidak ditemukan.', []);
            }

            DB::beginTransaction();
            $permission = RoleMenuPermission::where('role_id', $roleId)
                ->where('permission_id', $permissionId)
                ->first();

            if (!$permission) {
                return $this->api_response_error('Permission tidak ditemukan untuk role ini.', []);
            }

            $permission->is_allowed = $request->input('is_allowed');
            $permission->save();
            DB::commit();

            return $this->api_response_success('Hak Akses berhasil diperbarui.', []);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

}
