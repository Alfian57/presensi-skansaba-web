<?php

namespace App\Http\Controllers;

use App\Http\Requests\HomeroomTeacher\StoreHomeroomTeacherRequest;
use App\Http\Requests\HomeroomTeacher\UpdateHomeroomTeacherRequest;
use App\Models\Classroom;
use App\Models\HomeroomTeacher;
use App\Models\Teacher;
use RealRashid\SweetAlert\Facades\Alert;

class HomeroomTeacherController extends Controller
{
    /**
     * Display a listing of homeroom teachers.
     */
    public function index()
    {
        $homeroomTeachers = HomeroomTeacher::with(['teacher.user', 'classroom'])
            ->latest()
            ->get();

        return view('master-data.homeroom-teachers.index', compact('homeroomTeachers'));
    }

    /**
     * Show the form for creating a new homeroom teacher.
     */
    public function create()
    {
        // Get teachers that are not yet homeroom teachers
        $assignedTeacherIds = HomeroomTeacher::pluck('teacher_id');
        $teachers = Teacher::whereNotIn('id', $assignedTeacherIds)
            ->with('user')
            ->get();

        // Get classrooms that don't have homeroom teacher yet
        $assignedClassroomIds = HomeroomTeacher::pluck('classroom_id');
        $classrooms = Classroom::whereNotIn('id', $assignedClassroomIds)
            ->orderBy('grade_level')
            ->orderBy('major')
            ->orderBy('class_number')
            ->get();

        return view('master-data.homeroom-teachers.create', compact('teachers', 'classrooms'));
    }

    /**
     * Store a newly created homeroom teacher.
     */
    public function store(StoreHomeroomTeacherRequest $request)
    {
        try {
            HomeroomTeacher::create($request->validated());

            Alert::success('Berhasil', 'Wali kelas berhasil ditambahkan.');

            return redirect()->route('dashboard.homeroom-teachers.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());

            return back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified homeroom teacher.
     */
    public function edit(HomeroomTeacher $homeroomTeacher)
    {
        $homeroomTeacher->load(['teacher.user', 'classroom']);

        // Get available teachers (excluding current)
        $assignedTeacherIds = HomeroomTeacher::where('id', '!=', $homeroomTeacher->id)
            ->pluck('teacher_id');
        $teachers = Teacher::whereNotIn('id', $assignedTeacherIds)
            ->with('user')
            ->get();

        // Get available classrooms (excluding current)
        $assignedClassroomIds = HomeroomTeacher::where('id', '!=', $homeroomTeacher->id)
            ->pluck('classroom_id');
        $classrooms = Classroom::whereNotIn('id', $assignedClassroomIds)
            ->orderBy('grade_level')
            ->orderBy('major')
            ->orderBy('class_number')
            ->get();

        return view('master-data.homeroom-teachers.edit', compact('homeroomTeacher', 'teachers', 'classrooms'));
    }

    /**
     * Update the specified homeroom teacher.
     */
    public function update(UpdateHomeroomTeacherRequest $request, HomeroomTeacher $homeroomTeacher)
    {
        try {
            $homeroomTeacher->update($request->validated());

            Alert::success('Berhasil', 'Wali kelas berhasil diperbarui.');

            return redirect()->route('dashboard.homeroom-teachers.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());

            return back()->withInput();
        }
    }

    /**
     * Remove the specified homeroom teacher.
     */
    public function destroy(HomeroomTeacher $homeroomTeacher)
    {
        try {
            $teacherName = $homeroomTeacher->teacher->user->name;
            $classroomName = $homeroomTeacher->classroom->name;

            $homeroomTeacher->delete();

            Alert::success('Berhasil', "Wali kelas {$teacherName} untuk kelas {$classroomName} berhasil dihapus.");

            return redirect()->route('dashboard.homeroom-teachers.index');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());

            return back();
        }
    }
}
