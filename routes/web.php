<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassAbsenceController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeroomTeacherController;
use App\Http\Controllers\OtherDataController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicAttendanceController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Root redirect
Route::get('/', fn() => redirect()->route('auth.login'))->name('home.redirect');

// Authentication routes
Route::middleware('guest')->prefix('auth')->name('auth.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('attempt');
});

// Admin panel routes (for admin and teacher)
Route::prefix('dashboard')->middleware(['auth', 'role:admin,teacher'])->name('dashboard.')->group(function () {

    // Authentication
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard home
    Route::get('/', [DashboardController::class, 'index'])->name('home');

    // Profile management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
        Route::post('/photo', [ProfileController::class, 'uploadPhoto'])->name('photo.upload');
        Route::delete('/photo', [ProfileController::class, 'deletePhoto'])->name('photo.delete');
    });

    // Public Display - Protected for authenticated users
    Route::prefix('display')->name('display.')->group(function () {
        Route::get('/attendance', [PublicAttendanceController::class, 'displayToday'])->name('attendance.today');
        Route::get('/attendance/classroom/{classroom}', [PublicAttendanceController::class, 'displayClassroom'])->name('attendance.classroom');
    });

    // Attendance management (admin & teacher can view/edit)
    Route::prefix('attendances')->name('attendances.')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::get('/date/{date}', [AttendanceController::class, 'byDate'])->name('by-date');
        Route::get('/student/{student}', [AttendanceController::class, 'byStudent'])->name('by-student');
        Route::get('/classroom/{classroom}', [AttendanceController::class, 'byClassroom'])->name('by-classroom');
        Route::get('/{attendance}/edit', [AttendanceController::class, 'edit'])->name('edit');
        Route::put('/{attendance}', [AttendanceController::class, 'update'])->name('update');

        // Recap/Reports
        Route::get('/recap/student', [AttendanceController::class, 'recapStudent'])->name('recap.student');
        Route::get('/recap/classroom', [AttendanceController::class, 'recapClassroom'])->name('recap.classroom');
        Route::get('/recap/overall', [AttendanceController::class, 'recapOverall'])->name('recap.overall');

        // Export
        Route::get('/export', [AttendanceController::class, 'export'])->name('export');
    });

    // Class absences (skipping class / sakit / izin)
    Route::resource('class-absences', ClassAbsenceController::class)
        ->except(['show'])
        ->names('class-absences');
    Route::get('class-absences/export', [ClassAbsenceController::class, 'export'])->name('class-absences.export');

    // Teacher's own schedules
    Route::middleware('role:teacher')->group(function () {
        Route::get('/my-schedules', [ScheduleController::class, 'mySchedules'])->name('schedules.mine');
    });

    // Admin-only routes
    Route::middleware('role:admin')->group(function () {

        // Classrooms management
        Route::resource('classrooms', ClassroomController::class)->names('classrooms');

        // Students management
        Route::get('students/export', [StudentController::class, 'export'])->name('students.export');
        Route::resource('students', StudentController::class)->names('students');
        Route::post('students/{student}/reset-password', [StudentController::class, 'resetPassword'])->name('students.reset-password');
        Route::post('students/{student}/toggle-active', [StudentController::class, 'toggleActive'])->name('students.toggle-active');
        Route::delete('students/{student}/unregister-device', [StudentController::class, 'unregisterDevice'])->name('students.unregister-device');

        // Teachers management
        Route::get('teachers/export', [TeacherController::class, 'export'])->name('teachers.export');
        Route::resource('teachers', TeacherController::class)->names('teachers');
        Route::post('teachers/{teacher}/reset-password', [TeacherController::class, 'resetPassword'])->name('teachers.reset-password');

        // Subjects management
        Route::resource('subjects', SubjectController::class)->names('subjects');

        // Homeroom teachers management
        Route::resource('homeroom-teachers', HomeroomTeacherController::class)->except(['show'])->names('homeroom-teachers');

        // Schedules management
        Route::get('schedules/classroom/{classroom}', [ScheduleController::class, 'byClassroom'])->name('schedules.classroom');
        Route::get('schedules/teacher/{teacher}', [ScheduleController::class, 'byTeacher'])->name('schedules.teacher');
        Route::resource('schedules', ScheduleController::class)->names('schedules');

        // Admin users management
        Route::post('admins/{admin}/reset-password', [UserController::class, 'resetPassword'])->name('admins.reset-password');
        Route::resource('admins', UserController::class)->except(['show'])->names('admins');

        // System configuration
        Route::prefix('config')->name('config.')->group(function () {
            Route::get('/', [ConfigController::class, 'index'])->name('index');
            Route::put('/', [ConfigController::class, 'update'])->name('update');
            Route::post('/qr-refresh', [ConfigController::class, 'refreshQR'])->name('qr.refresh');
            Route::get('/qr-display', [ConfigController::class, 'displayQR'])->name('qr.display');
        });

        // Active device management
        Route::prefix('devices')->name('devices.')->group(function () {
            Route::get('/', [StudentController::class, 'activeDevices'])->name('index');
            Route::delete('/{student}', [StudentController::class, 'unregisterDevice'])->name('unregister');
        });

        // Other data management (system settings)
        Route::prefix('other-data')->name('other-data.')->group(function () {
            Route::get('/', [OtherDataController::class, 'index'])->name('index');
            Route::get('/{id}/edit', [OtherDataController::class, 'edit'])->name('edit');
            Route::put('/{id}', [OtherDataController::class, 'update'])->name('update');
        });
    });
});
