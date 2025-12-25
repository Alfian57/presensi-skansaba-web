<?php

namespace App\Services;

use App\Enums\ConfigKey;
use App\Models\AttendanceConfig;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class SemesterTransitionService
{
    /**
     * Get current academic year from config.
     */
    public function getCurrentAcademicYear(): string
    {
        return AttendanceConfig::getValue(ConfigKey::ACADEMIC_YEAR->value) ?? '2024/2025';
    }

    /**
     * Get current semester from config.
     */
    public function getCurrentSemester(): int
    {
        return (int) (AttendanceConfig::getValue(ConfigKey::CURRENT_SEMESTER->value) ?? 1);
    }

    /**
     * Transition to next semester.
     * If moving from semester 2 to 1, also updates academic year.
     */
    public function transitionToNextSemester(): array
    {
        $currentSemester = $this->getCurrentSemester();
        $currentYear = $this->getCurrentAcademicYear();

        $newSemester = $currentSemester === 1 ? 2 : 1;
        $newYear = $currentYear;

        // If transitioning to semester 1, we're starting a new academic year
        if ($newSemester === 1) {
            $years = explode('/', $currentYear);
            $newYear = $years[1] . '/' . ((int) $years[1] + 1);
        }

        // Update configs
        AttendanceConfig::setValue(ConfigKey::CURRENT_SEMESTER->value, (string) $newSemester);
        AttendanceConfig::setValue(ConfigKey::ACADEMIC_YEAR->value, $newYear);

        return [
            'old_semester' => $currentSemester,
            'new_semester' => $newSemester,
            'old_year' => $currentYear,
            'new_year' => $newYear,
        ];
    }

    /**
     * Copy schedules from previous semester to current semester.
     */
    public function copySchedulesToCurrentSemester(?string $sourceYear = null, ?int $sourceSemester = null): int
    {
        $currentYear = $this->getCurrentAcademicYear();
        $currentSemester = $this->getCurrentSemester();

        // Default source: previous semester
        if ($sourceYear === null || $sourceSemester === null) {
            if ($currentSemester === 1) {
                // Previous was semester 2 of previous year
                $years = explode('/', $currentYear);
                $sourceYear = ((int) $years[0] - 1) . '/' . $years[0];
                $sourceSemester = 2;
            } else {
                // Previous was semester 1 of current year
                $sourceYear = $currentYear;
                $sourceSemester = 1;
            }
        }

        // Get schedules from source
        $sourceSchedules = Schedule::where('academic_year', $sourceYear)
            ->where('semester', $sourceSemester)
            ->get();

        $copied = 0;
        foreach ($sourceSchedules as $schedule) {
            // Check if schedule already exists
            $exists = Schedule::where('classroom_id', $schedule->classroom_id)
                ->where('subject_id', $schedule->subject_id)
                ->where('teacher_id', $schedule->teacher_id)
                ->where('day', $schedule->day)
                ->where('start_time', $schedule->start_time)
                ->where('academic_year', $currentYear)
                ->where('semester', $currentSemester)
                ->exists();

            if (!$exists) {
                Schedule::create([
                    'classroom_id' => $schedule->classroom_id,
                    'subject_id' => $schedule->subject_id,
                    'teacher_id' => $schedule->teacher_id,
                    'day' => $schedule->day,
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                    'room' => $schedule->room,
                    'academic_year' => $currentYear,
                    'semester' => $currentSemester,
                ]);
                $copied++;
            }
        }

        return $copied;
    }

    /**
     * Promote students to next grade level.
     * Grade 10 -> 11, Grade 11 -> 12, Grade 12 -> graduated (deactivated)
     */
    public function promoteStudents(): array
    {
        $stats = [
            'promoted_10_to_11' => 0,
            'promoted_11_to_12' => 0,
            'graduated' => 0,
            'errors' => [],
        ];

        return DB::transaction(function () use (&$stats) {
            // Get classrooms grouped by grade level
            $classrooms = Classroom::where('is_active', true)->get()->groupBy('grade_level');

            // Process grade 12 first (graduate them)
            if (isset($classrooms[12])) {
                foreach ($classrooms[12] as $classroom) {
                    $studentCount = Student::where('classroom_id', $classroom->id)
                        ->whereHas('user', fn($q) => $q->where('is_active', true))
                        ->update(['classroom_id' => null]);
                    
                    // Deactivate users
                    $students = Student::whereNull('classroom_id')->get();
                    foreach ($students as $student) {
                        if ($student->user) {
                            $student->user->update(['is_active' => false]);
                            $stats['graduated']++;
                        }
                    }
                }
            }

            // Promote grade 11 to 12
            if (isset($classrooms[11]) && isset($classrooms[12])) {
                foreach ($classrooms[11] as $sourceClass) {
                    // Find corresponding class in grade 12 (same major and class number)
                    $targetClass = $classrooms[12]->first(function ($c) use ($sourceClass) {
                        return $c->major === $sourceClass->major && $c->class_number === $sourceClass->class_number;
                    });

                    if ($targetClass) {
                        $count = Student::where('classroom_id', $sourceClass->id)
                            ->whereHas('user', fn($q) => $q->where('is_active', true))
                            ->update(['classroom_id' => $targetClass->id]);
                        $stats['promoted_11_to_12'] += $count;
                    }
                }
            }

            // Promote grade 10 to 11
            if (isset($classrooms[10]) && isset($classrooms[11])) {
                foreach ($classrooms[10] as $sourceClass) {
                    // Find corresponding class in grade 11 (same major and class number)
                    $targetClass = $classrooms[11]->first(function ($c) use ($sourceClass) {
                        return $c->major === $sourceClass->major && $c->class_number === $sourceClass->class_number;
                    });

                    if ($targetClass) {
                        $count = Student::where('classroom_id', $sourceClass->id)
                            ->whereHas('user', fn($q) => $q->where('is_active', true))
                            ->update(['classroom_id' => $targetClass->id]);
                        $stats['promoted_10_to_11'] += $count;
                    }
                }
            }

            return $stats;
        });
    }

    /**
     * Get preview of what will happen during student promotion.
     */
    public function getPromotionPreview(): array
    {
        $preview = [
            'grade_10' => [],
            'grade_11' => [],
            'grade_12' => [],
        ];

        $classrooms = Classroom::where('is_active', true)
            ->withCount(['students' => fn($q) => $q->whereHas('user', fn($u) => $u->where('is_active', true))])
            ->get()
            ->groupBy('grade_level');

        foreach ([10, 11, 12] as $grade) {
            if (isset($classrooms[$grade])) {
                foreach ($classrooms[$grade] as $classroom) {
                    $preview["grade_{$grade}"][] = [
                        'classroom' => $classroom->name,
                        'student_count' => $classroom->students_count,
                        'action' => $grade === 12 ? 'Lulus' : 'Naik ke kelas ' . ($grade + 1),
                    ];
                }
            }
        }

        return $preview;
    }

    /**
     * Get summary of current semester status.
     */
    public function getSemesterSummary(): array
    {
        $currentYear = $this->getCurrentAcademicYear();
        $currentSemester = $this->getCurrentSemester();

        return [
            'academic_year' => $currentYear,
            'semester' => $currentSemester,
            'semester_label' => $currentSemester === 1 ? 'Ganjil' : 'Genap',
            'schedule_count' => Schedule::where('academic_year', $currentYear)
                ->where('semester', $currentSemester)
                ->count(),
            'total_students' => Student::whereHas('user', fn($q) => $q->where('is_active', true))->count(),
            'classrooms' => Classroom::where('is_active', true)->count(),
        ];
    }
}
