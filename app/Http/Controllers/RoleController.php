<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignRoleRequest;
use App\Http\Requests\RevokeRoleRequest;
use App\Models\User;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function assign(AssignRoleRequest $request)
    {
        User::find($request->user_id)->assignRole(Role::find($request->role_id));
        return response(['message' => 'ok']);
    }

    public function revoke(RevokeRoleRequest $request)
    {
        User::find($request->user_id)->removeRole(Role::find($request->role_id));
        return response(['message' => 'ok']);
    }
}
