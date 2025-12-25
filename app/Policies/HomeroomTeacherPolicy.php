<?php

namespace App\Policies;

use App\Models\HomeroomTeacher;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HomeroomTeacherPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any homeroom teachers.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view homerooms');
    }

    /**
     * Determine whether the user can view the homeroom teacher.
     */
    public function view(User $user, HomeroomTeacher $homeroomTeacher): bool
    {
        return $user->hasPermissionTo('view homerooms');
    }

    /**
     * Determine whether the user can create homeroom teachers.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create homerooms');
    }

    /**
     * Determine whether the user can update the homeroom teacher.
     */
    public function update(User $user, HomeroomTeacher $homeroomTeacher): bool
    {
        return $user->hasPermissionTo('edit homerooms');
    }

    /**
     * Determine whether the user can delete the homeroom teacher.
     */
    public function delete(User $user, HomeroomTeacher $homeroomTeacher): bool
    {
        return $user->hasPermissionTo('delete homerooms');
    }
}
