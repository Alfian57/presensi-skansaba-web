<?php

namespace App\Policies;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClassroomPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any classrooms.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view classrooms');
    }

    /**
     * Determine whether the user can view the classroom.
     */
    public function view(User $user, Classroom $classroom): bool
    {
        // Admin and teachers can view all
        if ($user->hasPermissionTo('view classrooms')) {
            return true;
        }

        // Students can view their own classroom
        if ($user->student && $user->student->classroom_id === $classroom->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create classrooms.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create classrooms');
    }

    /**
     * Determine whether the user can update the classroom.
     */
    public function update(User $user, Classroom $classroom): bool
    {
        return $user->hasPermissionTo('edit classrooms');
    }

    /**
     * Determine whether the user can delete the classroom.
     */
    public function delete(User $user, Classroom $classroom): bool
    {
        return $user->hasPermissionTo('delete classrooms');
    }
}
