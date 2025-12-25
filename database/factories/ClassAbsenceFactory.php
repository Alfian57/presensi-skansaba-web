<?php

namespace Database\Factories;

use App\Models\ClassAbsence;
use App\Models\Schedule;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassAbsenceFactory extends Factory
{
    protected $model = ClassAbsence::class;

    private static array $reasons = [
        'Sakit di tengah pelajaran',
        'Izin ke UKS',
        'Dipanggil BK',
        'Urusan OSIS',
        'Mengikuti lomba',
        'Latihan ekstrakurikuler',
        'Dispensasi kegiatan sekolah',
        'Izin keperluan keluarga',
        'Tidak hadir tanpa keterangan',
        'Terlambat masuk kelas',
        'Mengerjakan tugas piket',
        'Konsultasi dengan guru',
    ];

    public function definition(): array
    {
        return [
            'reason' => $this->faker->randomElement(self::$reasons),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function withReason(string $reason): static
    {
        return $this->state(fn (array $attributes) => [
            'reason' => $reason,
        ]);
    }
}
