<?php

namespace App\Models\ManagementSystem;

use App\Models\BaseModel;
use App\Models\ManagementSystem\Menu;
use App\Models\ManagementSystem\Role;
use Illuminate\Database\Eloquent\Model;
use App\Models\ManagementSystem\Permission;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleMenuPermission extends BaseModel
{
    protected $table = 'role_menu_permissions';
    protected $guarded = ['id'];
    protected $fillable = ['role_id', 'menu_id', 'permission_id', 'is_allowed'];

    /**
     * Get the role associated with the current model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the menu associated with the current model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Get the permission associated with the current model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }
}
