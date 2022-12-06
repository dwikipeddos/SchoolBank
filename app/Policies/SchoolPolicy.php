<?php

namespace App\Policies;

use App\Models\School;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SchoolPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function create(User $user)
    {
        return $user->can('create-school');
    }

    public function edit(User $user, School $school)
    {
        return $user->can('edit-school'); // && $school->id == $user->employee?->school->id;
    }

    public function delete(User $user, School $school)
    {
        return $user->can('delete-school'); // && $school->id == $user->employee?->school->id;
    }
}
