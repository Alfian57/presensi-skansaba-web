<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\HomeroomTeacher;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class HomeroomTeacherSeeder extends Seeder
{
    public function run(): void
    {
        $classrooms = Classroom::all();
        $teachers = Teacher::all();

        if ($teachers->isEmpty()) {
            $this->command->warn('No teachers found. Skipping homeroom seeder.');

            return;
        }

        if ($classrooms->isEmpty()) {
            $this->command->warn('No classrooms found. Nothing to seed.');

            return;
        }

        // Take 50% of all classrooms (random)
        $desiredTake = (int) ceil($classrooms->count() * 0.5);
        // A teacher can only be assigned to one classroom, so limit the number of classrooms based on the number of teachers
        $take = min($desiredTake, $teachers->count());

        $selectedClassrooms = $classrooms->count() === $take ? $classrooms : $classrooms->random($take);
        $selectedTeachers = $teachers->count() === $take ? $teachers : $teachers->random($take);

        // Reset indices so they can be paired one-to-one
        $selectedClassrooms = $selectedClassrooms->values();
        $selectedTeachers = $selectedTeachers->values();

        foreach ($selectedClassrooms as $index => $classroom) {
            $teacher = $selectedTeachers[$index];

            HomeroomTeacher::create([
                'teacher_id' => $teacher->id,
                'classroom_id' => $classroom->id,
            ]);
        }
    }
}
