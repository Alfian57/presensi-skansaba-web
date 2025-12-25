<?php

namespace App\Http\Controllers;

use App\Exports\StudentExport;
use App\Http\Controllers\Traits\HandlesAlerts;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Models\Classroom;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    use HandlesAlerts;

    public function __construct(
        private StudentService $studentService
    ) {}

    /**
     * Display a listing of students.
     */
    public function index(Request $request)
    {
        $query = Student::with(['user', 'classroom']);

        if ($request->filled('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }

        if ($request->filled('nisn')) {
            $query->where('nisn', 'like', '%' . $request->nisn . '%');
        }

        $students = $query->orderBy('nisn')->get();
        $classrooms = Classroom::orderBy('grade_level')->orderBy('major')->orderBy('class_number')->get();

        return view('users.students.index', compact('students', 'classrooms'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        $classrooms = Classroom::orderBy('grade_level')->orderBy('major')->orderBy('class_number')->get();

        return view('users.students.create', compact('classrooms'));
    }

    /**
     * Store a newly created student.
     */
    public function store(StoreStudentRequest $request)
    {
        try {
            $student = $this->studentService->create($request->validated());
            $this->alertSuccess("Siswa {$student->user->name} berhasil ditambahkan.");

            return redirect()->route('dashboard.students.index');
        } catch (\Exception $e) {
            $this->alertException($e);

            return back()->withInput();
        }
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student)
    {
        $student->load([
            'user',
            'classroom',
            'attendances' => fn($q) => $q->latest('date')->limit(30),
        ]);

        return view('users.students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student)
    {
        $student->load(['user', 'classroom']);
        $classrooms = Classroom::orderBy('grade_level')->orderBy('major')->orderBy('class_number')->get();

        return view('users.students.edit', compact('student', 'classrooms'));
    }

    /**
     * Update the specified student.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        try {
            $student = $this->studentService->update($student, $request->validated());
            $this->alertSuccess("Data siswa {$student->user->name} berhasil diperbarui.");

            return redirect()->route('dashboard.students.index');
        } catch (\Exception $e) {
            $this->alertException($e);

            return back()->withInput();
        }
    }

    /**
     * Remove the specified student.
     */
    public function destroy(Student $student)
    {
        try {
            $name = $student->user->name;
            $this->studentService->delete($student);
            $this->alertSuccess("Siswa {$name} berhasil dihapus.");

            return redirect()->route('dashboard.students.index');
        } catch (\Exception $e) {
            $this->alertException($e);

            return back();
        }
    }

    /**
     * Reset student password.
     */
    public function resetPassword(Student $student)
    {
        try {
            $newPassword = 'password';
            $this->studentService->resetPassword($student, $newPassword);
            $this->alertSuccess("Password siswa {$student->user->name} berhasil direset ke: {$newPassword}");

            return back();
        } catch (\Exception $e) {
            $this->alertException($e);

            return back();
        }
    }

    /**
     * Toggle student active status.
     */
    public function toggleActive(Student $student)
    {
        try {
            if ($student->user->is_active) {
                $this->studentService->deactivate($student);
                $status = 'dinonaktifkan';
            } else {
                $this->studentService->activate($student);
                $status = 'diaktifkan';
            }

            $this->alertSuccess("Akun siswa {$student->user->name} berhasil {$status}.");

            return back();
        } catch (\Exception $e) {
            $this->alertException($e);

            return back();
        }
    }

    /**
     * Unregister student device.
     */
    public function unregisterDevice(Student $student)
    {
        try {
            $this->studentService->unregisterDevice($student);
            $this->alertSuccess("Perangkat siswa {$student->user->name} berhasil dihapus.");

            return back();
        } catch (\Exception $e) {
            $this->alertException($e);

            return back();
        }
    }

    /**
     * Show active devices page.
     */
    public function activeDevices()
    {
        $students = Student::with(['user', 'classroom'])
            ->whereNotNull('active_device_id')
            ->orderBy('device_registered_at', 'desc')
            ->get();

        return view('system.active-devices.index', compact('students'));
    }

    /**
     * Export students to Excel.
     */
    public function export(Request $request)
    {
        $classroomId = $request->input('classroom_id');

        return Excel::download(new StudentExport($classroomId), 'students-' . date('Y-m-d') . '.xlsx');
    }
}
