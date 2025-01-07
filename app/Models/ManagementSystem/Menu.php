<?php

namespace App\Models\ManagementSystem;

use App\Models\User;
use App\Models\BaseModel;
use Illuminate\Support\Facades\Auth;
use App\Models\ManagementSystem\Role;
use Illuminate\Support\Facades\Crypt;
use App\Models\ManagementSystem\Permission;
use App\Models\ManagementSystem\RoleMenuPermission;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Menu extends BaseModel
{
    protected $table = 'menus';
    protected $guarded = ['id'];

    /**
     * Relation Menu with Roles - Many to Many
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_menu_permissions')
                    ->withPivot('permission_id', 'is_allowed');
    }

    /**
     * Relation Menu with Permission - Many to Many
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_menu_permissions')
                    ->withPivot('role_id', 'is_allowed');
    }

    /**
     * Get menu with parent menu
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    /**
     * Get menu with child menu
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function child(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id', 'id')->orderBy('ordering');
    }

    /**
     * Get infomation by user active now, and return menu with permissions.
     *
     * @param \App\Models\User $user
     * @return array<string, mixed>
     */
    public static function getMenuPermissionsByActiveUser($roleId): array
    {
        if (!$roleId) {
            $role_id = Auth::user()->roles->firstWhere('pivot.is_active', true)->id;
        } else {
            $role_id = $roleId;
        }

        if (!$role_id) {
            return [];
        }

        // Return data grouping by menu_id, and return menu with permission
        $menus = RoleMenuPermission::with(['menu', 'permission'])
            ->where('role_id', $role_id)
            ->get()
            ->groupBy('menu_id');

        if ($menus->isEmpty()) {
            return [];
        }

        return $menus->map(function ($menuPermissions) {
            // Get first menu
            $menu = $menuPermissions->first()->menu;
            // Get all permisions info
            $permissions = $menuPermissions->map(function ($roleMenuPermission) {
                $permission = $roleMenuPermission->permission;

                return [
                    'encrypt_id'            => Crypt::encrypt($permission->id),
                    'permission_name'       => $permission->permission_name,
                    'permission_action'     => $permission->permission_action,
                    'description'           => $permission->description,
                    'is_allowed'            => $roleMenuPermission->is_allowed,
                ];
            });

            return [
                'id'            => $menu->id,
                'menu_name'     => $menu->menu_name,
                'path'          => $menu->path,
                'key'           => $menu->key,
                'parent_id'     => $menu->parent_id,
                'ordering'      => $menu->ordering,
                'permissions'   => $permissions,
            ];
        })->sortBy('ordering')->values()->toArray();
    }

    /**
     * Showing all menu with child menu
     *
     * @param \App\Models\User $user
     * @return array<string, mixed>
     */
    public static function getMenuByActiveUser(User $user): array
    {
        $role = $user->roles->firstWhere('pivot.is_active', true);

        if (!$role) {
            return [];
        }

        // Get the menu data owned by the currently active role based on the is_allowed permission value being true and if there is a view permission on the menu then get the menu id.
        $roleMenuPermissions = RoleMenuPermission::with(['menu', 'permission'])
            ->where('role_id', $role->id)
            ->whereHas('permission', function ($query) {
                $query->where('permission_action', 'view');
            })
            ->where('is_allowed', true)
            ->get()
            ->groupBy('menu_id');

        $allowedMenuIds = $roleMenuPermissions->keys();

        // Get all menus and their children based on the ID from $allowedMenuIds.
        // Use eager loading('child.child') to ensure child menu data is fetched all at once in one query.
        $menus = Menu::with('child.child')
            ->whereIn('id', $allowedMenuIds)
            // Add parent menu to the results if it has child menus in $allowedMenuIds.
            // With the child.child relationship, the menu that has a parent_id will be inserted into the 'child' based on its parent_id.
            ->orWhereIn('id', function ($query) use ($allowedMenuIds) {
                $query->select('parent_id')
                      ->from('menus')
                      ->whereIn('id', $allowedMenuIds)
                      ->whereNotNull('parent_id');
            })
            ->orWhereNull('parent_id')
            ->orderBy('ordering')
            ->get();

        if ($menus->isEmpty()) {
            return [];
        }

        // Send parent data based on null parent_id and send all data formats
        $menuTree = $menus->whereNull('parent_id')->map(function ($menu) use ($menus, $allowedMenuIds) {
            return self::buildMenuTreeWithFilter($menu, $menus, $allowedMenuIds);
        })->filter(); // Remove invalid (null) menus

        return $menuTree->values()->toArray();
    }

    /**
     * Format data for showing menu with child menu
     *
     * @param \App\Models\Menu $menu
     * @param \Illuminate\Support\Collection $allMenus
     * @return array<string, mixed>
     */
    private static function buildMenuTreeWithFilter($menu, $allMenus, $allowedMenuIds): ?array
    {
        if ($allowedMenuIds instanceof \Illuminate\Support\Collection) {
            $allowedMenuIds = $allowedMenuIds->toArray();
        }

        // Recursively build child menus
        $children = $allMenus->where('parent_id', $menu->id)->map(function ($child) use ($allMenus, $allowedMenuIds) {
            return self::buildMenuTreeWithFilter($child, $allMenus, $allowedMenuIds);
        })->filter()->values(); // Remove invalid (null) children

        // Check if this menu is directly allowed or has valid children
        $isDirectlyAllowed = in_array($menu->id, $allowedMenuIds);
        if ($children->isEmpty() && !$isDirectlyAllowed) {
            return null; // Exclude this menu
        }
        return [
            'id'        => $menu->id,
            'menu_name' => $menu->menu_name,
            'path'      => $menu->path,
            'key'       => $menu->key,
            'menu_icon' => $menu->menu_icon,
            'parent_id' => $menu->parent_id,
            'ordering'  => $menu->ordering,
            'children'  => $children,
        ];
    }

}
