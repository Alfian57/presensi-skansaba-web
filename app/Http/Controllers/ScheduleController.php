<?php

namespace App\Http\Controllers;

use App\Enums\Day;
use App\Http\Controllers\Traits\HandlesAlerts;
use App\Http\Requests\Schedule\StoreScheduleRequest;
use App\Http\Requests\Schedule\UpdateScheduleRequest;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Teacher;
use App\Services\ScheduleService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    use HandlesAlerts;

    public function __construct(
        private ScheduleService $scheduleService
    ) {}

    /**
     * Display a listing of schedules.
     */
    public function index(Request $request)
    {
        $query = Schedule::with(['classroom', 'subject', 'teacher.user']);

        if ($request->filled('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }

        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        if ($request->filled('day')) {
            $query->where('day', $request->day);
        }

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        $schedules = $query->orderBy('day')->orderBy('start_time')->get();

        return view('master-data.schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new schedule.
     */
    public function create()
    {
        $classrooms = Classroom::orderBy('grade_level')
            ->orderBy('major')
            ->orderBy('class_number')
            ->get();

        $subjects = Subject::orderBy('name')->get();

        $teachers = Teacher::with(['user'])->whereHas('user')->get();

        $days = collect(Day::schoolDays())->mapWithKeys(fn($day) => [$day->value => $day->label()]);

        return view('master-data.schedules.create', compact('classrooms', 'subjects', 'teachers', 'days'));
    }

    /**
     * Store a newly created schedule.
     */
    public function store(StoreScheduleRequest $request)
    {
        try {
            $this->scheduleService->create($request->validated());
            $this->alertSuccess('Jadwal berhasil ditambahkan.');

            return redirect()->route('dashboard.schedules.index');
        } catch (\Exception $e) {
            $this->alertException($e);

            return back()->withInput();
        }
    }

    /**
     * Display the specified schedule.
     */
    public function show(Schedule $schedule)
    {
        $schedule->load(['classroom', 'subject', 'teacher.user']);

        return view('master-data.schedules.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified schedule.
     */
    public function edit(Schedule $schedule)
    {
        $schedule->load(['classroom', 'subject', 'teacher.user']);

        $classrooms = Classroom::orderBy('grade_level')
            ->orderBy('major')
            ->orderBy('class_number')
            ->get();

        $subjects = Subject::orderBy('name')->get();

        $teachers = Teacher::with(['user'])->whereHas('user')->get();

        $days = collect(Day::schoolDays())->mapWithKeys(fn($day) => [$day->value => $day->label()]);

        $start_time = $schedule->start_time ? Carbon::parse($schedule->start_time)->format('H:i') : null;
        $end_time = $schedule->end_time ? Carbon::parse($schedule->end_time)->format('H:i') : null;

        return view('master-data.schedules.edit', compact('schedule', 'classrooms', 'subjects', 'teachers', 'days', 'start_time', 'end_time'));
    }

    /**
     * Update the specified schedule.
     */
    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        try {
            $schedule = $this->scheduleService->update($schedule, $request->validated());
            $this->alertSuccess('Jadwal berhasil diperbarui.');

            return redirect()->route('dashboard.schedules.index');
        } catch (\Exception $e) {
            $this->alertException($e);

            return back()->withInput();
        }
    }

    /**
     * Remove the specified schedule.
     */
    public function destroy(Schedule $schedule)
    {
        try {
            $this->scheduleService->delete($schedule);
            $this->alertSuccess('Jadwal berhasil dihapus.');

            return redirect()->route('dashboard.schedules.index');
        } catch (\Exception $e) {
            $this->alertException($e);

            return back();
        }
    }

    /**
     * Display teacher's schedules.
     */
    public function mySchedules()
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            $this->alertError('Data guru tidak ditemukan.');

            return redirect()->route('dashboard.home');
        }

        $schedules = Schedule::where('teacher_id', $teacher->id)
            ->with(['classroom', 'subject'])
            ->orderBy('day')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day');

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('master-data.schedules.my-schedule', compact('schedules', 'days'));
    }
}
