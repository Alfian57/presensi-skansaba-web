<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Classroom;
use Carbon\Carbon;

class PublicAttendanceController extends Controller
{
    /**
     * Display today's attendance for all classrooms.
     */
    public function displayToday()
    {
        $today = Carbon::today();
        $title = 'Presensi Hari Ini';

        $classrooms = Classroom::with([
            'students' => function ($query) use ($today) {
                $query->with([
                    'attendances' => function ($q) use ($today) {
                        $q->whereDate('date', $today);
                    },
                ]);
            },
        ])
            ->orderBy('grade_level')
            ->orderBy('major')
            ->orderBy('class_number')
            ->get();

        return view('public.attendance.today', compact('title', 'classrooms', 'today'));
    }

    /**
     * Display attendance for a specific classroom.
     */
    public function displayClassroom(Classroom $classroom)
    {
        $today = Carbon::today();
        $title = 'Presensi '.$classroom->name;

        $students = $classroom->students()
            ->with([
                'user',
                'attendances' => function ($query) use ($today) {
                    $query->whereDate('date', $today);
                },
            ])
            ->get()
            ->sortBy(fn($student) => $student->user->name ?? '');

        return view('public.attendance.classroom', compact('title', 'classroom', 'students', 'today'));
    }
}
