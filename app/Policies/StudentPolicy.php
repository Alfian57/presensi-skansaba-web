<?php

namespace App\Policies;

use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any students.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view students');
    }

    /**
     * Determine whether the user can view the student.
     */
    public function view(User $user, Student $student): bool
    {
        // Admin can view all
        if ($user->hasPermissionTo('view students')) {
            return true;
        }

        // Student can view their own profile
        if ($user->student && $user->student->id === $student->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create students.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create students');
    }

    /**
     * Determine whether the user can update the student.
     */
    public function update(User $user, Student $student): bool
    {
        return $user->hasPermissionTo('edit students');
    }

    /**
     * Determine whether the user can delete the student.
     */
    public function delete(User $user, Student $student): bool
    {
        return $user->hasPermissionTo('delete students');
    }

    /**
     * Determine whether the user can export students.
     */
    public function export(User $user): bool
    {
        return $user->hasPermissionTo('export students');
    }

    /**
     * Determine whether the user can reset student password.
     */
    public function resetPassword(User $user, Student $student): bool
    {
        return $user->hasPermissionTo('edit students');
    }

    /**
     * Determine whether the user can toggle student active status.
     */
    public function toggleActive(User $user, Student $student): bool
    {
        return $user->hasPermissionTo('edit students');
    }

    /**
     * Determine whether the user can unregister student device.
     */
    public function unregisterDevice(User $user, Student $student): bool
    {
        return $user->hasPermissionTo('edit students');
    }
}
