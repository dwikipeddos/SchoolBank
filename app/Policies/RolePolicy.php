<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    use HandlesAuthorization;

    public function assignRole(User $user, Role $role)
    {
        return $user->can('assign' . $this->getPermissionName($role));
    }

    public function revokeRole(User $user, Role $role)
    {
        return $user->can('revoke' . $this->getPermissionName($role));
    }

    protected function getPermissionName(Role $role): string
    {
        switch (strtolower($role->name)) {
            case 'school-employee':
                return '-school-employee-role';
                break;
            case 'school-admin':
                return '-school-admin-role';
                break;
            default:
                return '';
        }
    }
}
