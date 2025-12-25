<?php

namespace App\Policies;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SchedulePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any schedules.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view schedules') || $user->hasPermissionTo('view own schedules');
    }

    /**
     * Determine whether the user can view the schedule.
     */
    public function view(User $user, Schedule $schedule): bool
    {
        // Admin can view all
        if ($user->hasPermissionTo('view schedules')) {
            return true;
        }

        // Teacher can view their own schedules
        if ($user->hasPermissionTo('view own schedules') && $user->teacher) {
            return $schedule->teacher_id === $user->teacher->id;
        }

        // Student can view their classroom schedules
        if ($user->hasPermissionTo('view own schedules') && $user->student) {
            return $schedule->classroom_id === $user->student->classroom_id;
        }

        return false;
    }

    /**
     * Determine whether the user can view own schedules.
     */
    public function viewOwn(User $user): bool
    {
        return $user->hasPermissionTo('view own schedules');
    }

    /**
     * Determine whether the user can create schedules.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create schedules');
    }

    /**
     * Determine whether the user can update the schedule.
     */
    public function update(User $user, Schedule $schedule): bool
    {
        return $user->hasPermissionTo('edit schedules');
    }

    /**
     * Determine whether the user can delete the schedule.
     */
    public function delete(User $user, Schedule $schedule): bool
    {
        return $user->hasPermissionTo('delete schedules');
    }
}
