<?php

namespace App\Traits\Testing;

use App\Models\User;
use Illuminate\Support\Facades\Artisan;

trait ActingAs
{
    public function reinsertRolesPermission()
    {
        Artisan::call('permission:reinsert');
    }

    public function createUserWithRole(string ...$roles): User
    {
        $user = User::factory()->create();
        $user->assignRole($roles);
        return $user;
    }

    public function createUserWithPermissions(string ...$permissions): User
    {
        $user = User::factory()->create();
        $user->givePermissionTo($permissions);
        return $user;
    }
}
