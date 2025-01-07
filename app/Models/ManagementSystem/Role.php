<?php

namespace App\Models\ManagementSystem;

use App\Models\User;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends BaseModel
{
    protected $table = 'roles';
    protected $guarded = ['id'];

    /**
     * Relation Role with User - Many to Many
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_users', 'role_id', 'user_id')
                    ->withPivot('is_active');
    }

    /**
     * Relation Role with Menu - Many to Many
     * # untuk menghindari duplikasi data, dapat dengan lakukan grouping berdasarkan menu_name
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class, 'role_menu_permissions')
                    ->withPivot('permission_id', 'is_allowed');
    }

    /**
     * Relation Role with Permission - Many to Many
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_menu_permissions')
                    ->withPivot('menu_id', 'is_allowed');
    }

    /**
     * Show Role and Menus Information
     *
     * @param int $roleId
     * @return array<string, mixed>
     */
    public static function roleMenusInfo(int $roleId): array
    {
        $role = Role::with(['menus' => function ($query) {
            $query->select('menus.*');
        }])->find($roleId);

        if (!$role) {
            return collect([]);
        }

        $role_info = [
            'id'            => $role->id,
            'role_name'     => $role->role_name,
            'display_name'  => $role->display_name,
            'description'   => $role->description,
            'is_active'     => $role->is_active,
            'type_role'     => $role->type_role,
        ];

        $role_menus = $role->menus->unique('id')->map(function ($menu) {
            return [
                'id'            => $menu->id,
                'menu_name'     => $menu->menu_name,
                'path'          => $menu->path,
                'key'           => $menu->key,
                'menu_icon'     => $menu->menu_icon,
                'parent_id'     => $menu->parent_id,
                'ordering'      => $menu->ordering,
            ];
        })->values()->toArray();

        return array_merge([
            'role_info'         => $role_info,
            'role_menus'        => $role_menus
        ]);
    }
    
    /**
     * Show Role and Permissions Information
     * 
     * @param int $roleId
     * @return array<string, mixed>
     */
    public static function rolePermissionsInfo(int $roleId): array
    {
        $role  = Role::with('permissions')
            ->where('id', $roleId)
            ->first();

        if (!$role) {
            throw new \Exception('Role tidak ditemukan.');
        }

        $role_info = [
            'id'            => $role->id,
            'role_name'     => $role->role_name,
            'display_name'  => $role->display_name,
            'description'   => $role->description,
            'is_active'     => $role->is_active,
            'type_role'     => $role->type_role,
        ];

        $role_permissions = $role->permissions->map(function ($permission) {
            return [
                'id'                => $permission->id,
                'permission_name'   => $permission->permission_name,
                'permission_action' => $permission->permission_action,
                'description'       => $permission->description,
            ];
        });

        return array_merge([
            'role_info'         => $role_info,
            'role_permissions'  => $role_permissions
        ]);
    }
}
