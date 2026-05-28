<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Permission;
class User extends Authenticatable
{
    use HasRoles, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'unit_name',
        'question',
        'role'
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

    public function hasPermission($permission)
    {
        $permissions = Permission::get()->toArray();
        return in_array($permission, $permissions);
    }

    public function isAdmin(): bool
    {
        if ($this->hasRole('Admin') || $this->hasRole('admin')) {
            return true;
        }

        return (string) $this->role === '1' || $this->role === 1;
    }

    public function hasPermissions($permission)
    {
        if ($this->isAdmin()) {
            return true; 
        }

        if (!Permission::where('name', $permission)->where('guard_name', 'web')->exists()) {
            return false;
        }

        try {
            return $this->hasPermissionTo($permission);
        } catch (\Throwable) {
            return false;
        }
    }
}
