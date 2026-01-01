<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ScheduleResource;
use App\Models\Schedule;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/schedules/today",
     *     tags={"Schedules"},
     *     summary="Get today's schedule for authenticated student",
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
     *     @OA\Response(response=401, description="Unauthenticated")
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

        $dayName = $this->getDayName(Carbon::now()->setTimezone('Asia/Jakarta')->dayOfWeek);

        $schedules = Schedule::where('classroom_id', $student->classroom_id)
            ->where('day', $dayName)
            ->with(['subject', 'teacher'])
            ->orderBy('start_time', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'day' => $dayName,
                'day_label' => $this->getDayLabel($dayName),
                'date' => Carbon::now()->setTimezone('Asia/Jakarta')->format('d F Y'),
                'schedules' => ScheduleResource::collection($schedules),
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/schedules/weekly",
     *     tags={"Schedules"},
     *     summary="Get weekly schedule for authenticated student",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function weekly(Request $request)
    {
        $user = $request->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan.',
            ], 404);
        }

        $days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
        $weeklySchedule = [];

        foreach ($days as $day) {
            $schedules = Schedule::where('classroom_id', $student->classroom_id)
                ->where('day', $day)
                ->with(['subject', 'teacher'])
                ->orderBy('start_time', 'asc')
                ->get();

            $weeklySchedule[] = [
                'day' => $day,
                'day_label' => $this->getDayLabel($day),
                'schedules' => ScheduleResource::collection($schedules),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $weeklySchedule,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/schedules/day/{day}",
     *     tags={"Schedules"},
     *     summary="Get schedule for a specific day",
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(name="day", in="path", required=true, @OA\Schema(type="string")),
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
    public function byDay(Request $request, string $day)
    {
        $user = $request->user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan.',
            ], 404);
        }

        $dayLower = strtolower($day);
        $validDays = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];

        if (!in_array($dayLower, $validDays)) {
            return response()->json([
                'success' => false,
                'message' => 'Hari tidak valid.',
            ], 400);
        }

        $schedules = Schedule::where('classroom_id', $student->classroom_id)
            ->where('day', $dayLower)
            ->with(['subject', 'teacher'])
            ->orderBy('start_time', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'day' => $dayLower,
                'day_label' => $this->getDayLabel($dayLower),
                'schedules' => ScheduleResource::collection($schedules),
            ],
        ]);
    }

    /**
     * Convert day of week number to Indonesian day name
     */
    private function getDayName(int $dayOfWeek): string
    {
        return match ($dayOfWeek) {
            0 => 'minggu',
            1 => 'senin',
            2 => 'selasa',
            3 => 'rabu',
            4 => 'kamis',
            5 => 'jumat',
            6 => 'sabtu',
            default => 'senin',
        };
    }

    /**
     * Get day label in Indonesian
     */
    private function getDayLabel(string $day): string
    {
        return match ($day) {
            'senin' => 'Senin',
            'selasa' => 'Selasa',
            'rabu' => 'Rabu',
            'kamis' => 'Kamis',
            'jumat' => 'Jumat',
            'sabtu' => 'Sabtu',
            'minggu' => 'Minggu',
            default => ucfirst($day),
        };
    }
}
