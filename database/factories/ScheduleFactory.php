<?php

namespace Database\Factories;

use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    private static array $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

    // Jam pelajaran SMK (45 menit per jam)
    private static array $timeSlots = [
        ['07:00', '07:45'],
        ['07:45', '08:30'],
        ['08:30', '09:15'],
        ['09:15', '10:00'],
        // Istirahat 1: 10:00 - 10:15
        ['10:15', '11:00'],
        ['11:00', '11:45'],
        // Istirahat 2 / Jumatan: 11:45 - 12:30
        ['12:30', '13:15'],
        ['13:15', '14:00'],
        ['14:00', '14:45'],
    ];

    private static array $rooms = [
        'Lab Komputer 1', 'Lab Komputer 2', 'Lab Komputer 3',
        'Lab Multimedia', 'Lab Jaringan', 'Lab Akuntansi',
        'Ruang Kelas', 'Perpustakaan', 'Lab Bahasa',
    ];

    public function definition(): array
    {
        $slot = $this->faker->randomElement(self::$timeSlots);

        return [
            'day' => $this->faker->randomElement(self::$days),
            'start_time' => $slot[0],
            'end_time' => $slot[1],
            'room' => $this->faker->randomElement(self::$rooms),
            'semester' => 1,
            'academic_year' => now()->year . '/' . (now()->year + 1),
        ];
    }

    public function forDay(string $day): static
    {
        return $this->state(fn (array $attributes) => [
            'day' => $day,
        ]);
    }

    public function atSlot(int $slotIndex): static
    {
        $slot = self::$timeSlots[$slotIndex] ?? self::$timeSlots[0];
        return $this->state(fn (array $attributes) => [
            'start_time' => $slot[0],
            'end_time' => $slot[1],
        ]);
    }

    public function forSemester(int $semester, string $academicYear): static
    {
        return $this->state(fn (array $attributes) => [
            'semester' => $semester,
            'academic_year' => $academicYear,
        ]);
    }
}
