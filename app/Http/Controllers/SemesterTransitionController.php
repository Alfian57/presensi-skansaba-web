<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HandlesAlerts;
use App\Services\SemesterTransitionService;
use Illuminate\Http\Request;

class SemesterTransitionController extends Controller
{
    use HandlesAlerts;

    public function __construct(
        private SemesterTransitionService $semesterService
    ) {}

    /**
     * Display semester transition page.
     */
    public function index()
    {
        $summary = $this->semesterService->getSemesterSummary();
        $promotionPreview = $this->semesterService->getPromotionPreview();

        return view('system.semester-transition.index', compact('summary', 'promotionPreview'));
    }

    /**
     * Transition to next semester.
     */
    public function transitionSemester(Request $request)
    {
        try {
            $result = $this->semesterService->transitionToNextSemester();

            $message = sprintf(
                'Berhasil pindah dari Semester %d (%s) ke Semester %d (%s)',
                $result['old_semester'],
                $result['old_year'],
                $result['new_semester'],
                $result['new_year']
            );

            $this->alertSuccess($message);

            return redirect()->route('dashboard.semester-transition.index');
        } catch (\Exception $e) {
            $this->alertException($e);

            return back();
        }
    }

    /**
     * Promote students to next grade level.
     */
    public function promoteStudents(Request $request)
    {
        try {
            $stats = $this->semesterService->promoteStudents();

            $message = sprintf(
                'Kenaikan kelas berhasil! Kelas 10→11: %d siswa, Kelas 11→12: %d siswa, Lulus: %d siswa',
                $stats['promoted_10_to_11'],
                $stats['promoted_11_to_12'],
                $stats['graduated']
            );

            $this->alertSuccess($message);

            return redirect()->route('dashboard.semester-transition.index');
        } catch (\Exception $e) {
            $this->alertException($e);

            return back();
        }
    }

    /**
     * Copy schedules from previous semester.
     */
    public function copySchedules(Request $request)
    {
        try {
            $copied = $this->semesterService->copySchedulesToCurrentSemester();

            if ($copied > 0) {
                $this->alertSuccess("Berhasil menyalin {$copied} jadwal ke semester saat ini.");
            } else {
                $this->alertInfo('Tidak ada jadwal baru yang disalin. Jadwal sudah ada atau semester sebelumnya kosong.');
            }

            return redirect()->route('dashboard.semester-transition.index');
        } catch (\Exception $e) {
            $this->alertException($e);

            return back();
        }
    }
}
