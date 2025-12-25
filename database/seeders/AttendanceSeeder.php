<?php

namespace Database\Seeders;

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating attendance records...');

        $students = Student::all();

        if ($students->isEmpty()) {
            $this->command->warn('Skipping attendances - no students found.');
            return;
        }

        // Generate attendance for the last 30 school days
        $daysToGenerate = 30;
        $currentDate = Carbon::today();
        $attendanceCount = 0;

        $dates = collect();
        $tempDate = $currentDate->copy();
        
        while ($dates->count() < $daysToGenerate) {
            $tempDate->subDay();
            // Skip weekends
            if (!$tempDate->isWeekend()) {
                $dates->push($tempDate->copy());
            }
        }

        // Generate for first 10 days only to keep it manageable
        $dates = $dates->take(10);

        foreach ($dates as $date) {
            $this->command->info("  Generating attendance for {$date->format('Y-m-d')}...");

            foreach ($students as $student) {
                // Generate weighted random status
                $status = $this->getWeightedRandomStatus();
                
                $checkInTime = null;
                $checkOutTime = null;
                $notes = null;

                if (in_array($status, [AttendanceStatus::PRESENT, AttendanceStatus::LATE])) {
                    if ($status === AttendanceStatus::PRESENT) {
                        $checkInTime = sprintf('06:%02d:00', rand(30, 55));
                    } else {
                        $checkInTime = sprintf('07:%02d:00', rand(5, 30));
                        $notes = $this->getLateReason();
                    }
                    $checkOutTime = sprintf('%02d:%02d:00', rand(14, 15), rand(0, 30));
                } elseif ($status === AttendanceStatus::SICK) {
                    $notes = $this->getSickReason();
                } elseif ($status === AttendanceStatus::PERMISSION) {
                    $notes = $this->getPermissionReason();
                }

                Attendance::create([
                    'student_id' => $student->id,
                    'date' => $date->toDateString(),
                    'status' => $status->value,
                    'check_in_time' => $checkInTime,
                    'check_out_time' => $checkOutTime,
                    'notes' => $notes,
                ]);

                $attendanceCount++;
            }
        }

        $this->command->info("✓ Created {$attendanceCount} attendance records.");
    }

    private function getWeightedRandomStatus(): AttendanceStatus
    {
        $rand = rand(1, 100);

        if ($rand <= 85) {
            return AttendanceStatus::PRESENT;
        } elseif ($rand <= 90) {
            return AttendanceStatus::LATE;
        } elseif ($rand <= 93) {
            return AttendanceStatus::SICK;
        } elseif ($rand <= 95) {
            return AttendanceStatus::PERMISSION;
        } else {
            return AttendanceStatus::ABSENT;
        }
    }

    private function getLateReason(): string
    {
        $reasons = ['Macet', 'Ban bocor', 'Hujan deras', 'Kesiangan', 'Kendala transportasi'];
        return $reasons[array_rand($reasons)];
    }

    private function getSickReason(): string
    {
        $reasons = ['Demam', 'Flu', 'Sakit perut', 'Sakit kepala', 'Batuk pilek', 'Pusing'];
        return $reasons[array_rand($reasons)];
    }

    private function getPermissionReason(): string
    {
        $reasons = ['Acara keluarga', 'Keperluan mendadak', 'Mengurus dokumen', 'Kontrol kesehatan', 'Urusan penting'];
        return $reasons[array_rand($reasons)];
    }
}
