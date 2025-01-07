<?php

namespace App\Http\Controllers\ManagementSystem;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ManagementSystem\Menu;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\BaseController;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ManagementSystem\Permission;
use App\Http\Requests\ManagementSystem\MenuRequest;
use App\Models\ManagementSystem\RoleMenuPermission;

class ManagementMenuController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $menu_access = $this->getMenuPermissionsByKey('management-menu');
        return view('dashboard.management_system.menus.index', compact('menu_access'));
    }

    public function indexJson(Request $request)
    {
        try {
            $data = Menu::select(
                'menus.id',
                'menus.menu_name',
                'menus.path',
                'menus.key',
                'menus.menu_icon',
                'menus.parent_id',
                'menus.ordering'
            );

            if (!empty($request->search)) {
                $data->where(function ($query) use ($request) {
                    $query->where('menus.menu_name',    'ilike', '%' . $request->search . '%')
                        ->orWhere('menus.path',         'ilike', '%' . $request->search . '%')
                        ->orWhere('menus.key',          'ilike', '%' . $request->search . '%')
                        ->orWhere('menus.menu_icon',    'ilike', '%' . $request->search . '%')
                        ->orWhere('menus.ordering',     'ilike', '%' . $request->search . '%');
                });
            }

            return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('path', function ($row) {
                return $row->path ? $row->path : '<span class="badge badge-light-danger">-</span>';
            })
            ->addColumn('menu_icon', function ($row) {
                return $row->menu_icon 
                    ? '<div class="d-block text-center justify-content-center">
                            <i class="ki-outline ' . $row->menu_icon . ' me-2"></i>
                            <p>' . $row->menu_icon . '</p>
                        </div>' 
                    : '<span class="badge badge-light-danger">-</span>';
            })
            ->addColumn('parent_id', function ($row) {
                return $row->parent_id ? Menu::where('id', $row->parent_id)->first()->menu_name : '<span class="badge badge-light-danger">-</span>';
            })
            ->addColumn('action', function ($row) {
                $menu_access = $this->getMenuPermissionsByKey('management-menu');

                $can_update = $menu_access['can_update'] ?? false;
                $can_delete = $menu_access['can_delete'] ?? false;

                return $this->renderActions($row, $can_update, $can_delete);
            })
            ->rawColumns(['path', 'menu_icon', 'parent_id', 'action'])
            ->toArray();
        } catch (\Throwable $th) {
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function store(MenuRequest $request)
    {
        try {
            $validated = $request->validated();

            DB::beginTransaction();
            Menu::create($validated);
            DB::commit();

            return $this->api_response_success('Berhasil menambahkan data Menu baru.', $validated);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function detailJson($encryptedId)
    {
        try {
            $menu_id    = Crypt::decrypt($encryptedId);
            $menu       = Menu::select(
                'id', 'menu_name', 'path', 'key', 'menu_icon', 'parent_id', 'ordering'
            )
            ->where('id', $menu_id)
            ->first();

            return $menu
                ? $this->api_response_success('Berhasil menampilkan data Menu.', $menu->toArray())
                : $this->api_response_error('Data Menu tidak ditemukan.');
        } catch (\Throwable $th) {
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function update(MenuRequest $request, $encryptedId)
    {
        try {
            $menu_id    = Crypt::decrypt($encryptedId);
            $menu       = Menu::where('id', $menu_id)->first();

            if (!$menu) {
                return $this->api_response_error('Data tidak ditemukan.');
            }

            $validated = $request->validated();

            DB::beginTransaction();
            $menu->update($validated);
            DB::commit();

            return $this->api_response_success('Berhasil memperbarui data Menu.', $validated);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }

    public function destroy($encryptedId)
    {
        try {
            $menu_id    = Crypt::decrypt($encryptedId);
            $menu       = Menu::where('id', $menu_id)->first();

            if (!$menu) {
                return $this->api_response_error('Data tidak ditemukan.');
            }

            DB::beginTransaction();
            $permissions = Permission::where('menu_id', $menu_id)->get();
            foreach ($permissions as $permission) {
                $permission->delete();
            }
    
            $roleMenuPermissions = RoleMenuPermission::where('menu_id', $menu_id)->get();
            foreach ($roleMenuPermissions as $roleMenuPermission) {
                $roleMenuPermission->delete();
            }

            $menuChilds = Menu::where('parent_id', $menu_id)->get();
            foreach($menuChilds as $menuChild) {
                $menuChild->delete();
            }

            $menu->delete();
            DB::commit();

            return $this->api_response_success('Berhasil menghapus data Menu.', []);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->api_response_error($th->getMessage() . ' - ' . $th->getLine(), [], $th->getTrace());
        }
    }
}
