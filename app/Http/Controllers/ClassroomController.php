<?php

namespace App\Http\Controllers;

use App\Http\Requests\Classroom\StoreClassroomRequest;
use App\Http\Requests\Classroom\UpdateClassroomRequest;
use App\Models\Classroom;
use App\Services\ClassroomService;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ClassroomController extends Controller
{
    public function __construct(
        private ClassroomService $classroomService
    ) {}

    /**
     * Display a listing of classrooms.
     */
    public function index(Request $request)
    {
        $query = Classroom::withCount('students');

        // Filter by grade level
        if ($request->filled('grade_level')) {
            $query->where('grade_level', $request->grade_level);
        }

        // Filter by major
        if ($request->filled('major')) {
            $query->where('major', $request->major);
        }

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $classrooms = $query->orderBy('grade_level')
            ->orderBy('major')
            ->orderBy('class_number')
            ->paginate(20);

        return view('master-data.classrooms.index', compact('classrooms'));
    }

    /**
     * Show the form for creating a new classroom.
     */
    public function create()
    {
        return view('master-data.classrooms.create');
    }

    /**
     * Store a newly created classroom.
     */
    public function store(StoreClassroomRequest $request)
    {
        try {
            $classroom = $this->classroomService->create($request->validated());

            Alert::success('Berhasil', "Kelas {$classroom->name} berhasil ditambahkan.");

            return redirect()->route('dashboard.classrooms.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());

            return back()->withInput();
        }
    }

    /**
     * Display the specified classroom.
     */
    public function show(Classroom $classroom)
    {
        $classroom->load(['students.user', 'schedules.subject', 'schedules.teacher.user']);
        $stats = $this->classroomService->getStatistics($classroom);

        return view('master-data.classrooms.show', compact('classroom', 'stats'));
    }

    /**
     * Show the form for editing the specified classroom.
     */
    public function edit(Classroom $classroom)
    {
        return view('master-data.classrooms.edit', compact('classroom'));
    }

    /**
     * Update the specified classroom.
     */
    public function update(UpdateClassroomRequest $request, Classroom $classroom)
    {
        try {
            $classroom = $this->classroomService->update($classroom, $request->validated());

            Alert::success('Berhasil', "Kelas {$classroom->name} berhasil diperbarui.");

            return redirect()->route('dashboard.classrooms.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());

            return back()->withInput();
        }
    }

    /**
     * Remove the specified classroom.
     */
    public function destroy(Classroom $classroom)
    {
        try {
            // Check if classroom has students
            if ($classroom->students()->count() > 0) {
                Alert::warning('Gagal', 'Kelas tidak dapat dihapus karena masih memiliki siswa.');

                return back();
            }

            $name = $classroom->name;
            $this->classroomService->delete($classroom);

            Alert::success('Berhasil', "Kelas {$name} berhasil dihapus.");

            return redirect()->route('dashboard.classrooms.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());

            return back();
        }
    }
}
