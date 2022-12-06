<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function create(User $user, $school_id)
    {
        return $user->can('create-student') && $user->employee?->school->id == $school_id;
    }

    public function createMany(User $user, $school_id)
    {
        return $user->can('create-many-student') && $user->employee?->school->id == $school_id;
    }

    public function edit(User $user, Student $student)
    {
        return $user->can('edit-student') && $student->classroom->school_id == $user->employee?->school->id;
    }

    public function delete(User $user, Student $student)
    {
        return $user->can('delete-student') && $student->classroom->school_id == $user->employee?->school->id;
    }
}
