<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttendancePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any attendances.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view attendances') || $user->hasPermissionTo('view own attendance');
    }

    /**
     * Determine whether the user can view the attendance.
     */
    public function view(User $user, Attendance $attendance): bool
    {
        // Admin and teachers can view all
        if ($user->hasPermissionTo('view attendances')) {
            return true;
        }

        // Students can only view their own
        if ($user->hasPermissionTo('view own attendance')) {
            return $user->student && $attendance->student_id === $user->student->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create attendances.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create attendances');
    }

    /**
     * Determine whether the user can update the attendance.
     */
    public function update(User $user, Attendance $attendance): bool
    {
        return $user->hasPermissionTo('edit attendances');
    }

    /**
     * Determine whether the user can delete the attendance.
     */
    public function delete(User $user, Attendance $attendance): bool
    {
        return $user->hasPermissionTo('delete attendances');
    }

    /**
     * Determine whether the user can export attendances.
     */
    public function export(User $user): bool
    {
        return $user->hasPermissionTo('export attendances');
    }

    /**
     * Determine whether the user can view attendance recap.
     */
    public function recap(User $user): bool
    {
        return $user->hasPermissionTo('recap attendances');
    }
}
