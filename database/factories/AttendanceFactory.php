<?php

namespace Database\Factories;

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition(): array
    {
        // Realistic distribution: 85% present, 5% late, 3% sick, 2% permission, 5% absent
        $statusWeights = [
            AttendanceStatus::PRESENT->value => 85,
            AttendanceStatus::LATE->value => 5,
            AttendanceStatus::SICK->value => 3,
            AttendanceStatus::PERMISSION->value => 2,
            AttendanceStatus::ABSENT->value => 5,
        ];

        $status = $this->weightedRandom($statusWeights);

        // Generate check-in time based on status
        $checkInTime = null;
        $checkOutTime = null;

        if (in_array($status, [AttendanceStatus::PRESENT->value, AttendanceStatus::LATE->value])) {
            if ($status === AttendanceStatus::PRESENT->value) {
                // On time: 06:30 - 06:55
                $checkInHour = 6;
                $checkInMinute = $this->faker->numberBetween(30, 55);
            } else {
                // Late: 07:00 - 07:30
                $checkInHour = 7;
                $checkInMinute = $this->faker->numberBetween(0, 30);
            }
            $checkInTime = sprintf('%02d:%02d:00', $checkInHour, $checkInMinute);

            // Check out: 14:00 - 15:30
            $checkOutHour = $this->faker->numberBetween(14, 15);
            $checkOutMinute = $checkOutHour === 15 
                ? $this->faker->numberBetween(0, 30) 
                : $this->faker->numberBetween(0, 59);
            $checkOutTime = sprintf('%02d:%02d:00', $checkOutHour, $checkOutMinute);
        }

        return [
            'date' => Carbon::today(),
            'status' => $status,
            'check_in_time' => $checkInTime,
            'check_out_time' => $checkOutTime,
            'notes' => $this->generateNotes($status),
        ];
    }

    private function weightedRandom(array $weights): string
    {
        $total = array_sum($weights);
        $rand = $this->faker->numberBetween(1, $total);
        
        foreach ($weights as $key => $weight) {
            $rand -= $weight;
            if ($rand <= 0) {
                return $key;
            }
        }
        
        return array_key_first($weights);
    }

    private function generateNotes(string $status): ?string
    {
        return match ($status) {
            AttendanceStatus::SICK->value => $this->faker->randomElement([
                'Demam', 'Flu', 'Sakit perut', 'Sakit kepala', 'Batuk pilek', null
            ]),
            AttendanceStatus::PERMISSION->value => $this->faker->randomElement([
                'Acara keluarga', 'Keperluan mendadak', 'Mengurus dokumen', 'Kontrol kesehatan', null
            ]),
            AttendanceStatus::LATE->value => $this->faker->randomElement([
                'Macet', 'Ban bocor', 'Hujan deras', 'Kesiangan', null
            ]),
            default => null,
        };
    }

    public function forDate(Carbon $date): static
    {
        return $this->state(fn (array $attributes) => [
            'date' => $date->toDateString(),
        ]);
    }

    public function present(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => AttendanceStatus::PRESENT->value,
            'check_in_time' => sprintf('06:%02d:00', $this->faker->numberBetween(30, 55)),
            'check_out_time' => sprintf('%02d:%02d:00', $this->faker->numberBetween(14, 15), $this->faker->numberBetween(0, 30)),
        ]);
    }

    public function late(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => AttendanceStatus::LATE->value,
            'check_in_time' => sprintf('07:%02d:00', $this->faker->numberBetween(5, 30)),
            'check_out_time' => sprintf('%02d:%02d:00', $this->faker->numberBetween(14, 15), $this->faker->numberBetween(0, 30)),
            'notes' => $this->faker->randomElement(['Macet', 'Ban bocor', 'Hujan deras', 'Kesiangan']),
        ]);
    }

    public function absent(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => AttendanceStatus::ABSENT->value,
            'check_in_time' => null,
            'check_out_time' => null,
        ]);
    }
}
