<?php

namespace App\Http\Controllers;

use App\Enums\AttendanceStatus;
use App\Exports\AttendanceExport;
use App\Http\Controllers\Traits\HandlesAlerts;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Student;
use App\Services\AttendanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    use HandlesAlerts;

    public function __construct(
        private AttendanceService $attendanceService
    ) {}

    /**
     * Display a listing of attendances.
     */
    public function index(Request $request)
    {
        $query = Attendance::with(['student.user', 'student.classroom']);

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        if ($request->filled('classroom_id')) {
            $query->whereHas('student', fn($q) => $q->where('classroom_id', $request->classroom_id));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('nisn', 'like', "%{$search}%")
                    ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        $attendances = $query->latest('date')->latest('check_in_time')->get();

        $classrooms = Classroom::orderBy('grade_level')
            ->orderBy('major')
            ->orderBy('class_number')
            ->get();

        $statuses = AttendanceStatus::cases();

        return view('attendances.index', compact('attendances', 'classrooms', 'statuses'));
    }

    /**
     * Display attendance recap by date range.
     */
    public function recap(Request $request)
    {
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)
            : Carbon::now()->startOfMonth();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)
            : Carbon::now()->endOfMonth();

        $classroomId = $request->classroom_id;

        $students = Student::with(['user', 'classroom'])
            ->when($classroomId, fn($q) => $q->where('classroom_id', $classroomId))
            ->orderBy('classroom_id')
            ->get();

        $recap = [];
        foreach ($students as $student) {
            $attendances = Attendance::where('student_id', $student->id)
                ->whereBetween('date', [$startDate, $endDate])
                ->get();

            $recap[] = [
                'student' => $student,
                'present' => $attendances->where('status', AttendanceStatus::PRESENT)->count(),
                'late' => $attendances->where('status', AttendanceStatus::LATE)->count(),
                'sick' => $attendances->where('status', AttendanceStatus::SICK)->count(),
                'permission' => $attendances->where('status', AttendanceStatus::PERMISSION)->count(),
                'absent' => $attendances->where('status', AttendanceStatus::ABSENT)->count(),
                'total' => $attendances->count(),
            ];
        }

        $classrooms = Classroom::orderBy('grade_level')
            ->orderBy('major')
            ->orderBy('class_number')
            ->get();

        return view('attendances.recap', compact('recap', 'classrooms', 'startDate', 'endDate'));
    }

    /**
     * Display attendances by classroom.
     */
    public function byClassroom(Classroom $classroom)
    {
        $attendances = Attendance::with(['student.user'])
            ->whereHas('student', fn($q) => $q->where('classroom_id', $classroom->id))
            ->orderBy('date', 'desc')
            ->orderBy('check_in_time', 'desc')
            ->paginate(20);

        return view('attendances.by-classroom', compact('classroom', 'attendances'));
    }

    /**
     * Display attendances by student.
     */
    public function byStudent(Student $student)
    {
        $student->load(['user', 'classroom']);

        $attendances = Attendance::where('student_id', $student->id)
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('attendances.by-student', compact('student', 'attendances'));
    }

    /**
     * Display attendances by date.
     */
    public function byDate($date)
    {
        $attendances = Attendance::with(['student.user', 'student.classroom'])
            ->whereDate('date', $date)
            ->orderBy('check_in_time')
            ->get();

        $classrooms = Classroom::orderBy('grade_level')
            ->orderBy('major')
            ->orderBy('class_number')
            ->get();

        return view('attendances.by-date', compact('attendances', 'classrooms', 'date'));
    }

    /**
     * Display the specified attendance.
     */
    public function show(Attendance $attendance)
    {
        $attendance->load(['student.user', 'student.classroom']);

        return view('attendances.show', compact('attendance'));
    }

    /**
     * Show the form for editing the specified attendance.
     */
    public function edit(Attendance $attendance)
    {
        $attendance->load(['student.user', 'student.classroom']);
        $statuses = AttendanceStatus::cases();

        return view('attendances.edit', compact('attendance', 'statuses'));
    }

    /**
     * Update the specified attendance.
     */
    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', array_column(AttendanceStatus::cases(), 'value')),
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $this->attendanceService->updateStatus($attendance, $request->status, $request->notes);
            $this->alertSuccess('Data presensi berhasil diperbarui.');

            return redirect()->route('dashboard.attendances.index');
        } catch (\Exception $e) {
            $this->alertException($e);

            return back()->withInput();
        }
    }

    /**
     * Remove the specified attendance.
     */
    public function destroy(Attendance $attendance)
    {
        try {
            $attendance->delete();
            $this->alertSuccess('Data presensi berhasil dihapus.');

            return redirect()->route('dashboard.attendances.index');
        } catch (\Exception $e) {
            $this->alertException($e);

            return back();
        }
    }

    /**
     * Export attendances to Excel.
     */
    public function export(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $classroomId = $request->classroom_id;

        return Excel::download(
            new AttendanceExport($startDate, $endDate, $classroomId),
            'attendances-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Show recap by student.
     */
    public function recapStudent(Request $request)
    {
        $students = Student::with('classroom')->get();
        $startDate = $request->start_date ?? date('Y-m-01');
        $endDate = $request->end_date ?? date('Y-m-t');

        return view('attendances.recap-student', compact('students', 'startDate', 'endDate'));
    }

    /**
     * Show recap by classroom.
     */
    public function recapClassroom(Request $request)
    {
        $classrooms = Classroom::with('students')->get();
        $startDate = $request->start_date ?? date('Y-m-01');
        $endDate = $request->end_date ?? date('Y-m-t');

        return view('attendances.recap-classroom', compact('classrooms', 'startDate', 'endDate'));
    }

    /**
     * Show overall recap.
     */
    public function recapOverall(Request $request)
    {
        $startDate = $request->start_date ?? date('Y-m-01');
        $endDate = $request->end_date ?? date('Y-m-t');

        $stats = Attendance::whereBetween('date', [$startDate, $endDate])
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('attendances.recap-overall', compact('stats', 'startDate', 'endDate'));
    }
}
