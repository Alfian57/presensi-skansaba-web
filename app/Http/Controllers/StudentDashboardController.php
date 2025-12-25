<?php

namespace App\Http\Controllers;

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use App\Models\Schedule;
use Carbon\Carbon;

class StudentDashboardController extends Controller
{
    /**
     * Display student dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        $student = $user->student;
        
        if (!$student) {
            abort(403, 'Data siswa tidak ditemukan.');
        }

        $today = Carbon::today();
        
        // Today's attendance
        $todayAttendance = Attendance::where('student_id', $student->id)
            ->where('date', $today->toDateString())
            ->first();

        // This month's summary
        $monthStart = $today->copy()->startOfMonth();
        $monthEnd = $today->copy()->endOfMonth();
        
        $monthlyAttendances = Attendance::where('student_id', $student->id)
            ->whereBetween('date', [$monthStart, $monthEnd])
            ->get();

        $attendanceSummary = [
            'present' => $monthlyAttendances->where('status', AttendanceStatus::PRESENT)->count(),
            'late' => $monthlyAttendances->where('status', AttendanceStatus::LATE)->count(),
            'sick' => $monthlyAttendances->where('status', AttendanceStatus::SICK)->count(),
            'permission' => $monthlyAttendances->where('status', AttendanceStatus::PERMISSION)->count(),
            'absent' => $monthlyAttendances->where('status', AttendanceStatus::ABSENT)->count(),
        ];

        // Today's schedules
        $todaySchedules = collect();
        if ($student->classroom_id) {
            $dayOfWeek = strtolower($today->format('l'));
            $todaySchedules = Schedule::where('classroom_id', $student->classroom_id)
                ->where('day', $dayOfWeek)
                ->with(['subject', 'teacher.user'])
                ->orderBy('start_time')
                ->get();
        }

        return view('student.dashboard', compact(
            'student',
            'todayAttendance',
            'attendanceSummary',
            'todaySchedules'
        ));
    }

    /**
     * Display student's attendance history.
     */
    public function myAttendance()
    {
        $user = auth()->user();
        $student = $user->student;
        
        if (!$student) {
            abort(403, 'Data siswa tidak ditemukan.');
        }

        $attendances = Attendance::where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('student.attendance', compact('student', 'attendances'));
    }

    /**
     * Display student's class schedule.
     */
    public function mySchedule()
    {
        $user = auth()->user();
        $student = $user->student;
        
        if (!$student) {
            abort(403, 'Data siswa tidak ditemukan.');
        }

        $schedules = collect();
        if ($student->classroom_id) {
            $schedules = Schedule::where('classroom_id', $student->classroom_id)
                ->with(['subject', 'teacher.user'])
                ->orderBy('day')
                ->orderBy('start_time')
                ->get()
                ->groupBy('day');
        }

        return view('student.schedule', compact('student', 'schedules'));
    }
}
