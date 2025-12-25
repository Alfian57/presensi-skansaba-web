<?php

namespace App\Services;

use App\Models\Classroom;
use Illuminate\Support\Str;

class ClassroomService
{
    /**
     * Create a new classroom.
     */
    public function create(array $data): Classroom
    {
        $attributes = [
            'name' => $data['grade_level'] . ' ' . ($data['major'] ?? '') . ' ' . $data['class_number'],
            'grade_level' => $data['grade_level'],
            'major' => $data['major'] ?? null,
            'class_number' => $data['class_number'],
            'capacity' => $data['capacity'] ?? 40,
        ];

        if (array_key_exists('is_active', $data)) {
            $attributes['is_active'] = $data['is_active'];
        }

        return Classroom::create($attributes);
    }

    /**
     * Update classroom data.
     */
    public function update(Classroom $classroom, array $data): Classroom
    {
        $attributes = [
            'grade_level' => $data['grade_level'] ?? $classroom->grade_level,
            'major' => $data['major'] ?? $classroom->major,
            'class_number' => $data['class_number'] ?? $classroom->class_number,
            'capacity' => $data['capacity'] ?? $classroom->capacity,
            'is_active' => $data['is_active'] ?? $classroom->is_active,
        ];
        if (!array_key_exists('is_active', $data)) {
            $attributes['is_active'] = false;
        }
        $attributes['name'] = $attributes['grade_level'] . ' ' . $attributes['major'] . ' ' . $attributes['class_number'];

        $classroom->update($attributes);

        return $classroom->fresh();
    }

    /**
     * Delete classroom.
     */
    public function delete(Classroom $classroom): bool
    {
        return $classroom->delete();
    }

    /**
     * Get classrooms by grade level.
     */
    public function getByGradeLevel(int $gradeLevel)
    {
        return Classroom::where('grade_level', $gradeLevel)
            ->withCount('students')
            ->orderBy('major')
            ->orderBy('class_number')
            ->get();
    }

    /**
     * Get classrooms by major.
     */
    public function getByMajor(string $major)
    {
        return Classroom::where('major', $major)
            ->withCount('students')
            ->orderBy('grade_level')
            ->orderBy('class_number')
            ->get();
    }

    /**
     * Get current academic year.
     */
    public function getCurrentAcademicYear(): string
    {
        $currentYear = now()->year;
        $currentMonth = now()->month;

        // Academic year starts in July (month 7)
        if ($currentMonth >= 7) {
            return $currentYear . '/' . ($currentYear + 1);
        } else {
            return ($currentYear - 1) . '/' . $currentYear;
        }
    }

    /**
     * Get classroom statistics.
     */
    public function getStatistics(Classroom $classroom): array
    {
        // Get all attendance records for students of this classroom for today
        $studentIds = $classroom->students()->pluck('id')->toArray();
        if (empty($studentIds)) {
            return [
                'present' => 0,
                'late' => 0,
                'absent' => 0,
                'sick' => 0,
            ];
        }

        $today = now()->toDateString();

        // Collect statuses and count occurrences (case-insensitive)
        $statusCounts = \App\Models\Attendance::whereIn('student_id', $studentIds)
            ->whereDate('created_at', $today)
            ->get()
            ->pluck('status')
            ->map(fn($s) => strtolower((string) $s))
            ->countBy()
            ->toArray();

        $result = [
            'present' => 0,
            'late' => 0,
            'absent' => 0,
            'sick' => 0,
        ];

        foreach ($statusCounts as $status => $count) {
            if (array_key_exists($status, $result)) {
                $result[$status] = $count;
            }
        }

        return [
            'present' => $result['present'],
            'late' => $result['late'],
            'absent' => $result['absent'],
            'sick' => $result['sick'],
        ];
    }
}
