<?php

namespace Database\Seeders;

use App\Models\ClassAbsence;
use App\Models\Schedule;
use App\Models\Student;
use Illuminate\Database\Seeder;

class ClassAbsenceSeeder extends Seeder
{
    private array $reasons = [
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
    ];

    public function run(): void
    {
        $this->command->info('Creating class absence records...');

        $students = Student::all();
        $schedules = Schedule::all();

        if ($students->isEmpty() || $schedules->isEmpty()) {
            $this->command->warn('Skipping class absences - no students or schedules found.');
            return;
        }

        // Generate 50 random class absences
        $absenceCount = 50;
        $created = 0;

        for ($i = 0; $i < $absenceCount; $i++) {
            $student = $students->random();
            
            // Get a schedule from student's classroom
            $studentSchedules = $schedules->where('classroom_id', $student->classroom_id);
            
            if ($studentSchedules->isEmpty()) {
                continue;
            }

            $schedule = $studentSchedules->random();
            $date = now()->subDays(rand(0, 30))->toDateString();

            ClassAbsence::create([
                'student_id' => $student->id,
                'subject_id' => $schedule->subject_id,
                'schedule_id' => $schedule->id,
                'date' => $date,
                'reason' => $this->reasons[array_rand($this->reasons)],
            ]);

            $created++;
        }

        $this->command->info("✓ Created {$created} class absence records.");
    }
}
