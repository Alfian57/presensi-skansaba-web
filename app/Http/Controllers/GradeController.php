<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Http\Requests\StoreGradeRequest;
use App\Http\Requests\UpdateGradeRequest;
use App\Models\Classroom;
use App\Models\HomeroomTeacher;
use App\Models\Schedule;
use App\Models\Student;
use Illuminate\Support\Str;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Helper::addHistory('/admin/grades', 'Kelas');

        $data = [
            'title' => 'Semua Kelas',
            'grades' => Classroom::latest()->get(),
        ];

        return view('master-data.classrooms.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Helper::addHistory('/admin/grades/create', 'Tambah Kelas');

        $data = [
            'title' => 'Semua Kelas',
        ];

        return view('master-data.classrooms.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGradeRequest $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255|unique:grades',
        ]);

        $validatedData['slug'] = Str::slug($request->name);

        Classroom::create($validatedData);

        return redirect('/admin/grades')->with('success', 'Data Kelas Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Classroom $grade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Classroom $grade)
    {
        Helper::addHistory('/admin/grades/'.$grade->slug.'/edit', 'Ubah Kelas');

        $data = [
            'title' => 'Edit Data Kelas',
            'grade' => $grade,
        ];

        return view('master-data.classrooms.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGradeRequest $request, Classroom $grade)
    {
        if ($request->oldName === $request->name) {
            return back()->with('nameError', 'Data Not Changed');
        } else {
            $validatedData = $request->validate([
                'name' => 'required|max:255|unique:grades',
            ]);

            $validatedData['slug'] = Str::slug($request->name);

            Classroom::where('id', $grade->id)->update($validatedData);
        }

        return redirect('/admin/grades')->with('success', 'Data Kelas Berhasil Diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Classroom $grade)
    {
        $students = Student::where('grade_id', $grade->id)->count();
        $schedules = Schedule::where('grade_id', $grade->id)->count();
        $homeroomTeacher = HomeroomTeacher::where('grade_id', $grade->id)->count();

        if ($students > 0) {
            return redirect('/admin/grades')->with('sAError', 'Kelas Ini Digunakan Oleh '.$students.' Siswa. Silahkan Edit Data Siswa Terlebih Dahulu');
        }

        if ($schedules > 0) {
            return redirect('/admin/grades')->with('sAError', 'Kelas Ini Digunakan Oleh '.$schedules.' Jadwal. Silahkan Edit Data Jadwal Terlebih Dahulu');
        }

        if ($homeroomTeacher > 0) {
            return redirect('/admin/grades')->with('sAError', 'Kelas Ini Digunakan Oleh '.$homeroomTeacher.' Wali Kelas. Silahkan Edit Data Wali Kelas Terlebih Dahulu');
        }

        Classroom::destroy($grade->id);

        return redirect('/admin/grades')->with('success', 'Data Kelas '.$grade->name.' Berhasil Dihapus');
    }
}
