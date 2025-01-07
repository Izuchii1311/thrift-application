<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ManagementSystem\Permission;
use App\Models\ManagementSystem\RoleMenuPermission;

class BaseController extends Controller
{
    /**
     * Get Menu Permission Action By Menu Key
     *
     * @param String $menuKey
     * @return array<String, mixed>
     */
    protected function getMenuPermissionsByKey(string $menuKey): array
    {
        $role = Auth::user()->roles->firstWhere('pivot.is_active', true);

        if (!$role) {
            return [];
        }

        // Get all possible permission_action from the Permission model
        $allPermissionActions = Permission::pluck('permission_action')->toArray();

        // Get Menu by key and role_id
        $menuPermissions = RoleMenuPermission::with(['menu', 'permission'])
            ->whereHas('menu', function ($query) use ($menuKey) {
                $query->where('key', $menuKey);
            })
            ->where('role_id', $role->id)
            ->get();

        if ($menuPermissions->isEmpty()) {
            return [];
        }

        $permissions = [];
        $menu = $menuPermissions->first()->menu;

        // Initialize permissions array with all possible actions set to false
        foreach ($allPermissionActions as $action) {
            $permissions["can_{$action}"] = false;
        }

        // Update permissions based on the role's actual permissions
        foreach ($menuPermissions as $roleMenuPermission) {
            $permissionAction = $roleMenuPermission->permission->permission_action;

            $permissions["can_{$permissionAction}"] = (bool) $roleMenuPermission->is_allowed;
        }

        // Merge menu details with permissions
        $permissions = array_merge([
            'menu_name' => $menu->menu_name,
            'path'      => $menu->path,
            'key'       => $menu->key,
            'ordering'  => $menu->ordering,
            'parent_id' => $menu->parent_id,
            'menu_icon' => $menu->menu_icon,
        ], $permissions);

        return $permissions;
    }


    /**
     * Generate Button Action
     *
     * @param Object $row
     * @param Boolean $can_update
     * @param Boolean $can_delete
     * @return string HTML
     */
    protected function renderActions($row, $can_update, $can_delete, $data_column = null): String
    {
        if (!$can_update && !$can_delete) {
            return '<div class="d-flex justify-content-center">
                        <span class="badge badge-light-danger">Tidak ada izin Aksi.</span>
                    </div>';
        }

        $actions = '<div class="dropdown">
                        <button class="dropdown-toggle btn btn-light btn-active-light-primary btn-flex btn-center btn-sm" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            Actions
                        </button>
                        <ul class="dropdown-menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" aria-labelledby="dropdownMenuButton">';

        $action_data = $data_column ? $data_column : Crypt::encrypt($row->id);

        if ($can_update) {
            $actions .= '<li class="menu-item px-3 rounded">
                            <a onclick="editData(`' . $action_data . '`);" class="dropdown-item px-3 py-2">Edit</a>
                        </li>';
        }

        if ($can_delete) {
            $actions .= '<li class="menu-item px-3 rounded">
                            <a onclick="deleteData(`' . $action_data . '`);" class="dropdown-item px-3 py-2">Delete</a>
                        </li>';
        }

        $actions .= '</ul>
                </div>';

        return $actions;
    }
}
