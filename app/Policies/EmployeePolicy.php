<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function create(User $user, $school_id)
    {
        return $user->can('create-employee') && $user->employee?->school->id == $school_id;
    }

    public function createMany(User $user, $school_id)
    {
        return $user->can('create-many-employee') && $user->employee?->school->id == $school_id;
    }

    public function edit(User $user, Employee $employee)
    {
        return $user->can('edit-employee') && $employee->school_id == $user->employee?->school->id;
    }

    public function delete(User $user, Employee $employee)
    {
        return $user->can('delete-employee') && $employee->school_id == $user->employee?->school->id;
    }
}
