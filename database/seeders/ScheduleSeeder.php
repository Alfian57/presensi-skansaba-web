<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    private array $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

    private array $timeSlots = [
        ['07:00', '07:45'],
        ['07:45', '08:30'],
        ['08:30', '09:15'],
        ['09:15', '10:00'],
        ['10:15', '11:00'],
        ['11:00', '11:45'],
        ['12:30', '13:15'],
        ['13:15', '14:00'],
    ];

    private array $rooms = [
        'Lab Komputer 1', 'Lab Komputer 2', 'Lab Multimedia',
        'Lab Jaringan', 'Ruang Teori', 'Perpustakaan',
    ];

    public function run(): void
    {
        $this->command->info('Creating schedules...');

        $classrooms = Classroom::all();
        $teachers = Teacher::all();
        $subjects = Subject::all();

        if ($teachers->isEmpty() || $subjects->isEmpty()) {
            $this->command->warn('Skipping schedules - no teachers or subjects found.');
            return;
        }

        $academicYear = now()->year . '/' . (now()->year + 1);
        $semester = now()->month >= 7 ? 1 : 2;
        $schedulesCreated = 0;

        foreach ($classrooms as $classroom) {
            $this->command->info("  Creating schedules for {$classroom->name}...");

            foreach ($this->days as $day) {
                // Each day has 4-6 subjects
                $slotsPerDay = rand(4, 6);
                $usedSlots = array_rand($this->timeSlots, min($slotsPerDay, count($this->timeSlots)));
                if (!is_array($usedSlots)) {
                    $usedSlots = [$usedSlots];
                }
                sort($usedSlots);

                foreach ($usedSlots as $slotIndex) {
                    $slot = $this->timeSlots[$slotIndex];
                    $teacher = $teachers->random();
                    $subject = $subjects->random();

                    // Check for conflicts (same teacher, same time, same day)
                    $hasConflict = Schedule::where('teacher_id', $teacher->id)
                        ->where('day', $day)
                        ->where('start_time', $slot[0])
                        ->where('semester', $semester)
                        ->where('academic_year', $academicYear)
                        ->exists();

                    if ($hasConflict) {
                        // Try another teacher
                        $teacher = $teachers->where('id', '!=', $teacher->id)->random();
                    }

                    Schedule::create([
                        'classroom_id' => $classroom->id,
                        'teacher_id' => $teacher->id,
                        'subject_id' => $subject->id,
                        'day' => $day,
                        'start_time' => $slot[0],
                        'end_time' => $slot[1],
                        'room' => $this->rooms[array_rand($this->rooms)],
                        'semester' => $semester,
                        'academic_year' => $academicYear,
                    ]);

                    $schedulesCreated++;
                }
            }
        }

        $this->command->info("✓ Created {$schedulesCreated} schedules.");
    }
}
