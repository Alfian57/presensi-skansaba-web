<?php

namespace App\Http\Controllers;

use App\Exports\SkippingClassExport;
use App\Helper;
use App\Http\Controllers\Traits\HandlesAlerts;
use App\Models\Attendance;
use App\Models\ClassAbsence;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SkippingClassController extends Controller
{
    use HandlesAlerts;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Helper::addHistory('/admin/skippingClass', 'Siswa Bolos');

        $attendances = Attendance::whereIn('desc', ['masuk', 'terlambat', 'masuk (bolos)'])
            ->where('present_date', date('Y-m-d'))
            ->pluck('student_id');

        $studentsId = $attendances->isEmpty() ? [0] : Student::whereIn('id', $attendances)->pluck('id');

        $data = [
            'title' => 'Siswa Bolos',
            'skippingClasses' => ClassAbsence::whereIn('student_id', $studentsId)->with('subject', 'student')->get(),
        ];

        return view('class-absences.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $attendances = Attendance::where('present_date', date('Y-m-d'))
            ->whereIn('desc', ['masuk', 'terlambat', 'masuk (bolos)'])
            ->pluck('student_id');

        $data = [
            'title' => 'Tambah Siswa Bolos',
            'students' => Student::whereIn('id', $attendances)->get(),
            'subjects' => Subject::latest()->get(),
        ];

        return view('class-absences.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'student_id' => 'required',
            'subject_id' => 'required',
        ]);

        ClassAbsence::create($validatedData);
        $this->alertSuccess('Berhasil Menambahkan Data');

        return redirect('/admin/skippingClass');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        ClassAbsence::destroy($id);
        $this->alertSuccess('Data Berhasil Dihapus');

        return redirect('/admin/skippingClass');
    }

    /**
     * Export to Excel.
     */
    public function exportExcel()
    {
        if (!request('grade') || !request('date')) {
            return redirect()->back();
        }

        $grade = Classroom::where('slug', request('grade'))->first();
        $fileName = "Rekap Siswa Bolos Kelas {$grade->name} | " . request('date') . '.xlsx';

        return Excel::download(new SkippingClassExport(request('grade'), request('date')), $fileName);
    }
}
