<?php

namespace App\Http\Controllers\ManagementSystem;

use Illuminate\Support\Facades\DB;
use App\Models\ManagementSystem\Menu;
use App\Models\ManagementSystem\Role;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\BaseController;
use App\Http\Requests\ManagementSystem\RoleRequest;
use App\Models\ManagementSystem\RoleMenuPermission;

class ManagementRoleController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $roles = Role::with(['permissions' => function($query) {
            $query->wherePivot('is_allowed', true);
        }])->paginate(5);

        $hasRoles = $roles->total() > 0;

        return view('dashboard.management_system.roles.index', compact('roles', 'hasRoles'));
    }

    public function store(RoleRequest $request)
    {
        try {
            $validated = $request->validated();

            DB::beginTransaction();
            $role = Role::create($validated);

            $menus = Menu::with('permissions')->get();

            $role_menu_permissions = [];
            $unique_check = [];
            foreach ($menus as $menu) {
                foreach ($menu->permissions as $permission) {
                    $is_allowed = false;

                    if ($menu->menu_name === 'Dashboard' && $permission->permission_action === 'view') {
                        $is_allowed = true;
                    }

                    // Set key unique
                    $unique_key = "{$menu->id}-{$permission->id}";

                    // Check key
                    if (!in_array($unique_key, $unique_check)) {
                        $role_menu_permissions[] = [
                            'role_id'       => $role->id,
                            'menu_id'       => $menu->id,
                            'permission_id' => $permission->id,
                            'is_allowed'    => $is_allowed,
                            'created_at'    => now(),
                            'updated_at'    => now(),
                        ];

                        // Set latest key
                        $unique_check[] = $unique_key;
                    }
                }
            }

            RoleMenuPermission::insert($role_menu_permissions);
            DB::commit();

            return $this->api_response_success('Berhasil menambahkan data Role baru.', $validated);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function detailJson($encryptedId)
    {
        try {
            $role_id    = Crypt::decrypt($encryptedId);
            $role       = Role::select('id', 'role_name', 'display_name', 'description', 'is_active', 'type_role')
                            ->find($role_id);

            return $role
                ? $this->api_response_success('Berhasil menampilkan data Role.', $role->toArray())
                : $this->api_response_error('Data Role tidak ditemukan.');
        } catch (\Throwable $th) {
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function update(RoleRequest $request, $encryptedId)
    {
        try {
            $role_id    = Crypt::decrypt($encryptedId);
            $role       = Role::where('id', $role_id)->first();

            $validated = $request->validated();

            DB::beginTransaction();
            $role->update($validated);
            DB::commit();

            return $this->api_response_success('Berhasil menambahkan data Role baru.', $validated);
        } catch (\Throwable $th) {
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function destroy($encryptedId)
    {
        try {
            $role_id    = Crypt::decrypt($encryptedId);
            $role       = Role::where('id', $role_id)->first();

            DB::beginTransaction();
            DB::table('role_menu_permissions')->where('role_id', $role_id)->delete();
            $role->delete();
            DB::commit();

            return $this->api_response_success('Berhasil menghapus data Role.', []);
        } catch (\Throwable $th) {
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }
}
