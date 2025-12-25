<?php

namespace App\Http\Controllers;

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use App\Models\ClassAbsence;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard home page.
     */
    public function index()
    {
        $user = auth()->user();
        
        // Redirect students to their own dashboard
        if ($user->hasRole('student')) {
            return redirect()->route('dashboard.student.home');
        }
        
        $title = 'Dashboard';
        $today = Carbon::today();

        // Basic Statistics
        $stats = [
            'total_students' => Student::count(),
            'total_teachers' => Teacher::count(),
            'total_classrooms' => Classroom::count(),
            'total_schedules' => Schedule::count(),
        ];

        // Today's Attendance Statistics
        $todayAttendances = Attendance::where('date', $today)->get();
        $attendanceStats = [
            'total' => $todayAttendances->count(),
            'present' => $todayAttendances->where('status', AttendanceStatus::PRESENT)->count(),
            'late' => $todayAttendances->where('status', AttendanceStatus::LATE)->count(),
            'sick' => $todayAttendances->where('status', AttendanceStatus::SICK)->count(),
            'permission' => $todayAttendances->where('status', AttendanceStatus::PERMISSION)->count(),
            'absent' => $todayAttendances->where('status', AttendanceStatus::ABSENT)->count(),
        ];

        // Calculate attendance percentage
        $attendanceStats['percentage'] = $stats['total_students'] > 0
            ? round(($attendanceStats['present'] + $attendanceStats['late']) / $stats['total_students'] * 100, 1)
            : 0;

        // Recent Attendance Activities (last 10)
        $recentAttendances = Attendance::where('date', $today)
            ->with(['student.user', 'student.classroom'])
            ->latest('check_in_time')
            ->limit(10)
            ->get();

        // Weekly Attendance Trend (last 7 days)
        $weeklyTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = Attendance::where('date', $date)
                ->whereIn('status', ['present', 'late'])
                ->count();

            $weeklyTrend[] = [
                'date' => $date->format('D'),
                'full_date' => $date->format('d M'),
                'count' => $count,
            ];
        }

        // Top 5 Classes by Attendance Today
        $topClasses = Attendance::where('date', $today)
            ->whereIn('status', ['present', 'late'])
            ->select('students.classroom_id', DB::raw('count(*) as total'))
            ->join('students', 'attendances.student_id', '=', 'students.id')
            ->groupBy('students.classroom_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $classroom = Classroom::find($item->classroom_id);

                return [
                    'name' => $classroom->name ?? 'N/A',
                    'total' => $item->total,
                    'total_students' => $classroom ? $classroom->students()->count() : 0,
                    'percentage' => $classroom && $classroom->students()->count() > 0
                        ? round($item->total / $classroom->students()->count() * 100, 1)
                        : 0,
                ];
            });

        // Students who haven't checked in yet today
        $notCheckedIn = Student::whereDoesntHave('attendances', function ($query) use ($today) {
            $query->where('date', $today);
        })->count();

        // Recent Skipping Classes (Bolos)
        $recentSkipping = ClassAbsence::with(['student.user', 'schedule.subject', 'schedule.classroom'])
            ->whereDate('created_at', '>=', $today->subDays(7))
            ->latest()
            ->limit(5)
            ->get();

        // Monthly Summary
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();
        $monthlyStats = [
            'total_attendance' => Attendance::whereBetween('date', [$monthStart, $monthEnd])->count(),
            'avg_daily_attendance' => round(
                Attendance::whereBetween('date', [$monthStart, $monthEnd])
                    ->whereIn('status', ['present', 'late'])
                    ->count() / max(Carbon::now()->day, 1),
                1
            ),
            'total_skipping' => ClassAbsence::whereBetween('created_at', [$monthStart, $monthEnd])->count(),
        ];

        return view('dashboard.index', compact(
            'title',
            'stats',
            'attendanceStats',
            'recentAttendances',
            'weeklyTrend',
            'topClasses',
            'notCheckedIn',
            'recentSkipping',
            'monthlyStats'
        ));
    }
}
