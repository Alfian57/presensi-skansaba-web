<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttendanceResource;
use App\Models\Attendance;
use App\Models\AttendanceConfig;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/attendances/today",
     *     tags={"Attendance"},
     *     summary="Get today's attendance for authenticated student",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Student not found")
     * )
     */
    public function today(Request $request)
    {
        $user = $request->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan.',
            ], 404);
        }

        $attendance = Attendance::where('student_id', $student->id)
            ->whereDate('date', today())
            ->first();

        // Get attendance config for display
        $config = $this->getAttendanceConfig();

        return response()->json([
            'success' => true,
            'data' => [
                'attendance' => $attendance ? new AttendanceResource($attendance) : null,
                'config' => $config,
                'can_check_in' => $this->canCheckIn($attendance, $config),
                'can_check_out' => $this->canCheckOut($attendance, $config),
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/attendances/history",
     *     tags={"Attendance"},
     *     summary="Get attendance history for authenticated student",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(name="month", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="year", in="query", required=false, @OA\Schema(type="integer")),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function history(Request $request)
    {
        $user = $request->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan.',
            ], 404);
        }

        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $attendances = Attendance::where('student_id', $student->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'month' => (int) $month,
                'year' => (int) $year,
                'attendances' => AttendanceResource::collection($attendances),
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/attendances/statistics",
     *     tags={"Attendance"},
     *     summary="Get attendance statistics for authenticated student",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(name="month", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="year", in="query", required=false, @OA\Schema(type="integer")),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function statistics(Request $request)
    {
        $user = $request->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan.',
            ], 404);
        }

        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        $attendances = Attendance::where('student_id', $student->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        $stats = [
            'total_days' => $attendances->count(),
            'present' => $attendances->where('status', 'present')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'sick' => $attendances->where('status', 'sick')->count(),
            'permission' => $attendances->where('status', 'permission')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'month' => (int) $month,
                'year' => (int) $year,
                'statistics' => $stats,
            ],
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/attendances/check-in",
     *     tags={"Attendance"},
     *     summary="Check in with QR code",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"qr_code"},
     *             @OA\Property(property="qr_code", type="string", example="abc123")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Check-in successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Check-in berhasil."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Invalid QR or already checked in"),

     * )
     */
    public function checkIn(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
        ]);

        $user = $request->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan.',
            ], 404);
        }

        // Validate QR code
        $validQR = AttendanceConfig::getValue('qr_check_in');
        if ($request->qr_code !== $validQR) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak valid.',
            ], 400);
        }

        $config = $this->getAttendanceConfig();
        $now = Carbon::now()->setTimezone('Asia/Jakarta');

        // Check time window
        $startTime = Carbon::parse($config['check_in_start']);
        $endTime = Carbon::parse($config['check_in_end']);

        if ($now->lt($startTime) || $now->gt($endTime)) {
            return response()->json([
                'success' => false,
                'message' => 'Waktu absensi belum dimulai atau sudah berakhir.',
            ], 400);
        }

        // Check if already checked in today
        $attendance = Attendance::where('student_id', $student->id)
            ->whereDate('date', today())
            ->first();

        if ($attendance && $attendance->check_in_time) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absensi masuk hari ini.',
            ], 400);
        }

        // Determine if late
        $lateTime = Carbon::parse($config['late_time']);
        $status = $now->lte($lateTime) ? 'present' : 'late';

        // Create or update attendance
        $attendance = Attendance::updateOrCreate(
            [
                'student_id' => $student->id,
                'date' => today(),
            ],
            [
                'status' => $status,
                'check_in_time' => $now,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => $status === 'late' ? 'Anda terlambat masuk.' : 'Absensi masuk berhasil.',
            'data' => new AttendanceResource($attendance),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/attendances/check-out",
     *     tags={"Attendance"},
     *     summary="Check out with QR code",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"qr_code"},
     *             @OA\Property(property="qr_code", type="string", example="xyz789")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Check-out successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Check-out berhasil.")
     *         )
     *     ),

     *     @OA\Response(response=400, description="Invalid QR or not checked in"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function checkOut(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
        ]);

        $user = $request->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan.',
            ], 404);
        }

        // Validate QR code
        $validQR = AttendanceConfig::getValue('qr_check_out');
        if ($request->qr_code !== $validQR) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak valid.',
            ], 400);
        }

        $config = $this->getAttendanceConfig();
        $now = Carbon::now()->setTimezone('Asia/Jakarta');

        // Check time window for checkout
        $checkOutStart = Carbon::parse($config['check_out_start']);

        if ($now->lt($checkOutStart)) {
            return response()->json([
                'success' => false,
                'message' => 'Waktu absensi pulang belum dimulai.',
            ], 400);
        }

        // Check if checked in and not checked out yet
        $attendance = Attendance::where('student_id', $student->id)
            ->whereDate('date', today())
            ->first();

        if (!$attendance || !$attendance->check_in_time) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum melakukan absensi masuk.',
            ], 400);
        }

        if ($attendance->check_out_time) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absensi pulang.',
            ], 400);
        }

        // Update with check out time
        $attendance->update([
            'check_out_time' => $now,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absensi pulang berhasil.',
            'data' => new AttendanceResource($attendance->fresh()),
        ]);
    }

    /**
     * Get attendance configuration
     */
    private function getAttendanceConfig(): array
    {
        return [
            'check_in_start' => AttendanceConfig::getValue('check_in_start', '06:00'),
            'check_in_end' => AttendanceConfig::getValue('check_in_end', '09:00'),
            'late_time' => AttendanceConfig::getValue('late_time', '07:15'),
            'check_out_start' => AttendanceConfig::getValue('check_out_start', '14:00'),
        ];
    }

    /**
     * Check if student can check in
     */
    private function canCheckIn(?Attendance $attendance, array $config): bool
    {
        if ($attendance && $attendance->check_in_time) {
            return false;
        }

        $now = Carbon::now()->setTimezone('Asia/Jakarta');
        $startTime = Carbon::parse($config['check_in_start']);
        $endTime = Carbon::parse($config['check_in_end']);

        return $now->between($startTime, $endTime);
    }

    /**
     * Check if student can check out
     */
    private function canCheckOut(?Attendance $attendance, array $config): bool
    {
        if (!$attendance || !$attendance->check_in_time) {
            return false;
        }

        if ($attendance->check_out_time) {
            return false;
        }

        $now = Carbon::now()->setTimezone('Asia/Jakarta');
        $checkOutStart = Carbon::parse($config['check_out_start']);

        return $now->gte($checkOutStart);
    }
}
