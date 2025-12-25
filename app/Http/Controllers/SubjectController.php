<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HandlesAlerts;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    use HandlesAlerts;

    /**
     * Display a listing of subjects.
     */
    public function index(Request $request)
    {
        $query = Subject::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $subjects = $query->orderBy('name')->paginate(20);

        return view('master-data.subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new subject.
     */
    public function create()
    {
        return view('master-data.subjects.create');
    }

    /**
     * Store a newly created subject.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:subjects,code',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
        ], [
            'code.required' => 'Kode mata pelajaran wajib diisi.',
            'code.unique' => 'Kode mata pelajaran sudah digunakan.',
            'name.required' => 'Nama mata pelajaran wajib diisi.',
        ]);

        try {
            Subject::create($request->only(['code', 'name', 'description']));
            $this->alertSuccess('Mata pelajaran berhasil ditambahkan.');

            return redirect()->route('dashboard.subjects.index');
        } catch (\Exception $e) {
            $this->alertException($e);

            return back()->withInput();
        }
    }

    /**
     * Display the specified subject.
     */
    public function show(Subject $subject)
    {
        $subject->load(['schedules.classroom', 'schedules.teacher.user']);

        return view('master-data.subjects.show', compact('subject'));
    }

    /**
     * Show the form for editing the specified subject.
     */
    public function edit(Subject $subject)
    {
        return view('master-data.subjects.edit', compact('subject'));
    }

    /**
     * Update the specified subject.
     */
    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'code' => 'required|string|max:20|unique:subjects,code,' . $subject->id,
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
        ], [
            'code.required' => 'Kode mata pelajaran wajib diisi.',
            'code.unique' => 'Kode mata pelajaran sudah digunakan.',
            'name.required' => 'Nama mata pelajaran wajib diisi.',
        ]);

        try {
            $subject->update($request->only(['code', 'name', 'description']));
            $this->alertSuccess('Mata pelajaran berhasil diperbarui.');

            return redirect()->route('dashboard.subjects.index');
        } catch (\Exception $e) {
            $this->alertException($e);

            return back()->withInput();
        }
    }

    /**
     * Remove the specified subject.
     */
    public function destroy(Subject $subject)
    {
        try {
            if ($subject->schedules()->count() > 0) {
                $this->alertWarning('Mata pelajaran tidak dapat dihapus karena masih digunakan dalam jadwal.');

                return back();
            }

            $subject->delete();
            $this->alertSuccess('Mata pelajaran berhasil dihapus.');

            return redirect()->route('dashboard.subjects.index');
        } catch (\Exception $e) {
            $this->alertException($e);

            return back();
        }
    }
}
