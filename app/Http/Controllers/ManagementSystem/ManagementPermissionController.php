<?php

namespace App\Http\Controllers\ManagementSystem;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\ManagementSystem\Menu;
use App\Models\ManagementSystem\Role;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\BaseController;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ManagementSystem\Permission;
use App\Models\ManagementSystem\RoleMenuPermission;
use App\Http\Requests\ManagementSystem\PermissionRequest;

class ManagementPermissionController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $menu_access = $this->getMenuPermissionsByKey('management-menu');
        return view('dashboard.management_system.permissions.index', compact('menu_access'));
    }

    public function indexJson(Request $request)
    {
        try {
            $data = Permission::select(
                'permissions.id',
                'permissions.permission_name',
                'permissions.permission_action',
                'permissions.description',
                'menus.id as menu_id',
                'menus.menu_name'
            )
            ->join('menus', 'permissions.menu_id', '=', 'menus.id');

            if (!empty($request->search)) {
                $data->where(function ($query) use ($request) {
                    $query->where('permissions.permission_name',    'ilike', '%' . $request->search . '%')
                        ->orWhere('permissions.permission_action',  'ilike', '%' . $request->search . '%')
                        ->orWhere('menus.menu_name',                'ilike', '%' . $request->search . '%');
                });
            }

            return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $menu_access = $this->getMenuPermissionsByKey('management-permission');

                $can_update = $menu_access['can_update'] ?? false;
                $can_delete = $menu_access['can_delete'] ?? false;

                return $this->renderActions($row, $can_update, $can_delete);
            })
            ->rawColumns(['action'])
            ->toJson();
        } catch (\Throwable $th) {
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function store(PermissionRequest $request)
    {
        try {
            $validated = $request->validated();

            DB::beginTransaction();
            $permission = Permission::create($validated);

            $roles = Role::all();

            foreach ($roles as $role) {
                $exists = RoleMenuPermission::where('role_id', $role->id)
                    ->where('permission_id', $permission->id)
                    ->exists();

                if (!$exists) {
                    RoleMenuPermission::create([
                        'role_id'       => $role->id,
                        'menu_id'       => $permission->menu_id,
                        'permission_id' => $permission->id,
                        'is_allowed'    => false,
                    ]);
                }
            }
            DB::commit();

            return $this->api_response_success('Berhasil menambahkan data Permission', $validated);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function detailJson($encryptedId)
    {
        try {
            $permission_id  = Crypt::decrypt($encryptedId);
            $permission     = Permission::select(
                'id', 'menu_id', 'permission_name', 'permission_action', 'description'
            )
            ->where('id', $permission_id)
            ->first();

            return $permission
                ? $this->api_response_success('Berhasil menampilkan data Permission.', $permission->toArray())
                : $this->api_response_error('Data Permission tidak ditemukan.');
        } catch (\Throwable $th) {
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function update(PermissionRequest $request, $encryptedId)
    {
        try {
            $permission_id  = Crypt::decrypt($encryptedId);
            $permission     = Permission::where('id', $permission_id)->first();

            if (!$permission) {
                return $this->api_response_error('Data tidak ditemukan');
            }

            $validated = $request->validated();

            DB::beginTransaction();
            $permission->update($validated);
            DB::commit();

            return $this->api_response_success('Berhasil memperbarui data Permission.', $validated);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function destroy($encryptedId)
    {
        try {
            $permission_id  = Crypt::decrypt($encryptedId);
            $permission     = Permission::where('id', $permission_id)->first();

            if (!$permission) {
                return $this->api_response_error('Data tidak ditemukan.');
            }

            DB::beginTransaction();
            // if ($permission->isCritical()) {
            //     return $this->api_response_error('Permission ini tidak dapat dihapus karena merupakan bagian dari sistem kritis.');
            // }
            RoleMenuPermission::where('permission_id', $permission->id)->delete();

            $permission->delete();
            DB::commit();

            return $this->api_response_success('Berhasil menghapus data Permission.', []);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }
}