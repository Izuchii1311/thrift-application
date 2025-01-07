<?php

namespace App\Models\ManagementSystem;

use App\Models\BaseModel;
use App\Models\ManagementSystem\Menu;
use App\Models\ManagementSystem\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends BaseModel
{
    protected $table = 'permissions';
    protected $guarded = ['id'];

    /**
     * Relation Permission with Roles - Many to Many
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions', 'role_id', 'permission_id')
                    ->withPivot('is_allowed');
    }

    /**
     * Relation Permission with Menus - Many to Many
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class, 'menu_permissions', 'menu_id', 'permission_id');
    }

    /**
     * Critical permission can't deleted
     *
     * @return
     */
    public function isCritical(): bool
    {
        $critical_permission = ['view', 'create', 'update', 'delete'];
        return in_array($this->permission_action, $critical_permission);
    }
}
