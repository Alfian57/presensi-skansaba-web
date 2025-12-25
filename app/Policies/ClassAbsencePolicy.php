<?php

namespace App\Policies;

use App\Models\ClassAbsence;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClassAbsencePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any class absences.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view class absences');
    }

    /**
     * Determine whether the user can view the class absence.
     */
    public function view(User $user, ClassAbsence $classAbsence): bool
    {
        return $user->hasPermissionTo('view class absences');
    }

    /**
     * Determine whether the user can create class absences.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create class absences');
    }

    /**
     * Determine whether the user can update the class absence.
     */
    public function update(User $user, ClassAbsence $classAbsence): bool
    {
        // Admin can edit all
        if ($user->hasRole('admin')) {
            return true;
        }

        // Teacher can only edit their own records
        if ($user->hasPermissionTo('edit class absences') && $user->teacher) {
            return $classAbsence->schedule && $classAbsence->schedule->teacher_id === $user->teacher->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the class absence.
     */
    public function delete(User $user, ClassAbsence $classAbsence): bool
    {
        // Admin can delete all
        if ($user->hasRole('admin')) {
            return true;
        }

        // Teacher can only delete their own records
        if ($user->hasPermissionTo('delete class absences') && $user->teacher) {
            return $classAbsence->schedule && $classAbsence->schedule->teacher_id === $user->teacher->id;
        }

        return false;
    }

    /**
     * Determine whether the user can export class absences.
     */
    public function export(User $user): bool
    {
        return $user->hasPermissionTo('export class absences');
    }
}
