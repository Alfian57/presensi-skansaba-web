<?php

namespace App\Http\Controllers;

use App\Exports\TeacherExport;
use App\Http\Requests\Teacher\StoreTeacherRequest;
use App\Http\Requests\Teacher\UpdateTeacherRequest;
use App\Models\Teacher;
use App\Services\TeacherService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class TeacherController extends Controller
{
    public function __construct(
        private TeacherService $teacherService
    ) {
    }

    /**
     * Display a listing of teachers.
     */
    public function index(Request $request)
    {
        $query = Teacher::with(['user']);

        // Search by employee number or name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('employee_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $teachers = $query->latest()->paginate(20);

        return view('users.teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new teacher.
     */
    public function create()
    {
        return view('users.teachers.create');
    }

    /**
     * Store a newly created teacher.
     */
    public function store(StoreTeacherRequest $request)
    {
        try {
            $teacher = $this->teacherService->create($request->validated());

            Alert::success('Berhasil', "Guru {$teacher->user->name} berhasil ditambahkan.");

            return redirect()->route('dashboard.teachers.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());

            return back()->withInput();
        }
    }

    /**
     * Display the specified teacher.
     */
    public function show(Teacher $teacher)
    {
        $teacher->load(['user', 'schedules.classroom', 'schedules.subject']);

        return view('users.teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified teacher.
     */
    public function edit(Teacher $teacher)
    {
        $teacher->load(['user']);

        return view('users.teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified teacher.
     */
    public function update(UpdateTeacherRequest $request, Teacher $teacher)
    {
        try {
            $teacher = $this->teacherService->update($teacher, $request->validated());

            Alert::success('Berhasil', "Data guru {$teacher->user->name} berhasil diperbarui.");

            return redirect()->route('dashboard.teachers.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());

            return back()->withInput();
        }
    }

    /**
     * Remove the specified teacher.
     */
    public function destroy(Teacher $teacher)
    {
        try {
            // Check if teacher has schedules
            if ($teacher->schedules()->count() > 0) {
                Alert::warning('Gagal', 'Guru tidak dapat dihapus karena masih memiliki jadwal mengajar.');

                return back();
            }

            $name = $teacher->user->name;
            $this->teacherService->delete($teacher);

            Alert::success('Berhasil', "Guru {$name} berhasil dihapus.");

            return redirect()->route('dashboard.teachers.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());

            return back();
        }
    }

    /**
     * Reset teacher password.
     */
    public function resetPassword(Teacher $teacher)
    {
        try {
            $newPassword = 'password'; // Default password
            $this->teacherService->resetPassword($teacher, $newPassword);

            Alert::success('Berhasil', "Password guru {$teacher->user->name} berhasil direset ke: {$newPassword}");

            return back();
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());

            return back();
        }
    }

    /**
     * Export teachers to Excel.
     */
    public function export()
    {
        return Excel::download(new TeacherExport(), 'teachers-' . date('Y-m-d') . '.xlsx');
    }
}
