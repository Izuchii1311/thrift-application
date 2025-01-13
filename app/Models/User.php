<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;
use App\Models\ManagementSystem\Role;
use Illuminate\Support\Facades\Cache;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'profile_picture',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relation User with Role - Many to Many
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_users', 'user_id', 'role_id')
                    ->withPivot('is_active');
    }

    /**
     * Show User, Roles and Active Role Information
     *
     * @param \App\Models\User $user
     * @return array<string, mixed>
     */
    public static function userRoleActiveInfo(): array
    {
        $user = Auth::user();

        $user_info = [
            'id'                => $user->id,
            'username'          => $user->username,
            'name'              => $user->name,
            'email'             => $user->email,
            'profile_picture'   => $user->profile_picture,
        ];

        $roles = $user->roles->map(function ($role) {
            return [
                'id'            => $role->id,
                'role_name'     => $role->role_name,
                'display_name'  => $role->display_name,
                'description'   => $role->description,
                'is_active'     => $role->is_active,
                'type_role'     => $role->type_role,
            ];
        });

        $default_role = $user->roles->firstWhere('pivot.is_active', true);

        return [
            'user_info'             => $user_info,
            'role_active_as'        => $default_role->role_name,
            'role_active_id'        => $default_role->pivot->role_id,
            'role_is_active'        => $default_role->pivot->is_active,
            'roles'                 => $roles,
        ];
    }

    public function address(): HasOne
    {
        return $this->hasOne(UserAddress::class, 'user_id', 'id');
    }
}
