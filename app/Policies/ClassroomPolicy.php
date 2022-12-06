<?php

namespace App\Policies;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClassroomPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function create(User $user, $school_id)
    {
        return $user->can('create-classroom') && $user->employee->school->id == $school_id;
    }

    public function createMany(User $user, $school_id)
    {
        return $user->can('create-many-classroom') && $user->employee->school->id == $school_id;
    }

    public function edit(User $user, Classroom $classroom)
    {
        return $user->can('edit-classroom') && $classroom->school_id == $user->employee?->school->id;
    }

    public function delete(User $user, Classroom $classroom)
    {
        return $user->can('delete-classroom') && $classroom->school_id == $user->employee?->school->id;
    }
}
