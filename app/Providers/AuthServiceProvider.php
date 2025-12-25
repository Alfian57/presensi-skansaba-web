<?php

namespace App\Providers;

use App\Models\Attendance;
use App\Models\ClassAbsence;
use App\Models\Classroom;
use App\Models\HomeroomTeacher;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use App\Policies\AttendancePolicy;
use App\Policies\ClassAbsencePolicy;
use App\Policies\ClassroomPolicy;
use App\Policies\HomeroomTeacherPolicy;
use App\Policies\SchedulePolicy;
use App\Policies\StudentPolicy;
use App\Policies\SubjectPolicy;
use App\Policies\TeacherPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Attendance::class => AttendancePolicy::class,
        ClassAbsence::class => ClassAbsencePolicy::class,
        Classroom::class => ClassroomPolicy::class,
        HomeroomTeacher::class => HomeroomTeacherPolicy::class,
        Schedule::class => SchedulePolicy::class,
        Student::class => StudentPolicy::class,
        Subject::class => SubjectPolicy::class,
        Teacher::class => TeacherPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Gate for accessing dashboard
        Gate::define('access-dashboard', function (User $user) {
            return $user->hasAnyRole(['admin', 'teacher', 'student']);
        });

        // Gate for admin-only features
        Gate::define('admin-only', function (User $user) {
            return $user->hasRole('admin');
        });

        // Gate for teacher features
        Gate::define('teacher-access', function (User $user) {
            return $user->hasAnyRole(['admin', 'teacher']);
        });

        // Gate for semester transition
        Gate::define('manage-semester', function (User $user) {
            return $user->hasRole('admin');
        });

        // Gate for system configuration
        Gate::define('manage-config', function (User $user) {
            return $user->hasPermissionTo('edit configs');
        });

        // Gate for viewing reports
        Gate::define('view-reports', function (User $user) {
            return $user->hasPermissionTo('view reports');
        });
    }
}
