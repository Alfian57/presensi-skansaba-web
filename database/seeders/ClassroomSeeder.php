<?php

namespace Database\Seeders;

use App\Models\Classroom;
use Illuminate\Database\Seeder;

class ClassroomSeeder extends Seeder
{
    public function run(): void
    {
        $classrooms = [
            // Grade 10 (X)
            ['name' => '10 RPL 1', 'grade_level' => '10', 'major' => 'RPL', 'class_number' => 1, 'capacity' => 36],
            ['name' => '10 RPL 2', 'grade_level' => '10', 'major' => 'RPL', 'class_number' => 2, 'capacity' => 36],
            ['name' => '10 TKJ 1', 'grade_level' => '10', 'major' => 'TKJ', 'class_number' => 1, 'capacity' => 36],
            ['name' => '10 TKJ 2', 'grade_level' => '10', 'major' => 'TKJ', 'class_number' => 2, 'capacity' => 36],
            ['name' => '10 AKL 1', 'grade_level' => '10', 'major' => 'AKL', 'class_number' => 1, 'capacity' => 36],
            ['name' => '10 AKL 2', 'grade_level' => '10', 'major' => 'AKL', 'class_number' => 2, 'capacity' => 36],
            ['name' => '10 AKL 3', 'grade_level' => '10', 'major' => 'AKL', 'class_number' => 3, 'capacity' => 36],
            ['name' => '10 AKL 4', 'grade_level' => '10', 'major' => 'AKL', 'class_number' => 4, 'capacity' => 36],
            ['name' => '10 BDP 1', 'grade_level' => '10', 'major' => 'BDP', 'class_number' => 1, 'capacity' => 36],
            ['name' => '10 BDP 2', 'grade_level' => '10', 'major' => 'BDP', 'class_number' => 2, 'capacity' => 36],
            ['name' => '10 OTKP 1', 'grade_level' => '10', 'major' => 'OTKP', 'class_number' => 1, 'capacity' => 36],
            ['name' => '10 OTKP 2', 'grade_level' => '10', 'major' => 'OTKP', 'class_number' => 2, 'capacity' => 36],
            ['name' => '10 MM 1', 'grade_level' => '10', 'major' => 'MM', 'class_number' => 1, 'capacity' => 36],
            ['name' => '10 MM 2', 'grade_level' => '10', 'major' => 'MM', 'class_number' => 2, 'capacity' => 36],
            ['name' => '10 PS 1', 'grade_level' => '10', 'major' => 'PS', 'class_number' => 1, 'capacity' => 36],
            ['name' => '10 PS 2', 'grade_level' => '10', 'major' => 'PS', 'class_number' => 2, 'capacity' => 36],

            // Grade 11 (XI)
            ['name' => '11 RPL 1', 'grade_level' => '11', 'major' => 'RPL', 'class_number' => 1, 'capacity' => 36],
            ['name' => '11 RPL 2', 'grade_level' => '11', 'major' => 'RPL', 'class_number' => 2, 'capacity' => 36],
            ['name' => '11 TKJ 1', 'grade_level' => '11', 'major' => 'TKJ', 'class_number' => 1, 'capacity' => 36],
            ['name' => '11 TKJ 2', 'grade_level' => '11', 'major' => 'TKJ', 'class_number' => 2, 'capacity' => 36],
            ['name' => '11 AKL 1', 'grade_level' => '11', 'major' => 'AKL', 'class_number' => 1, 'capacity' => 36],
            ['name' => '11 AKL 2', 'grade_level' => '11', 'major' => 'AKL', 'class_number' => 2, 'capacity' => 36],
            ['name' => '11 AKL 3', 'grade_level' => '11', 'major' => 'AKL', 'class_number' => 3, 'capacity' => 36],
            ['name' => '11 AKL 4', 'grade_level' => '11', 'major' => 'AKL', 'class_number' => 4, 'capacity' => 36],
            ['name' => '11 BDP 1', 'grade_level' => '11', 'major' => 'BDP', 'class_number' => 1, 'capacity' => 36],
            ['name' => '11 BDP 2', 'grade_level' => '11', 'major' => 'BDP', 'class_number' => 2, 'capacity' => 36],
            ['name' => '11 OTKP 1', 'grade_level' => '11', 'major' => 'OTKP', 'class_number' => 1, 'capacity' => 36],
            ['name' => '11 OTKP 2', 'grade_level' => '11', 'major' => 'OTKP', 'class_number' => 2, 'capacity' => 36],
            ['name' => '11 MM 1', 'grade_level' => '11', 'major' => 'MM', 'class_number' => 1, 'capacity' => 36],
            ['name' => '11 MM 2', 'grade_level' => '11', 'major' => 'MM', 'class_number' => 2, 'capacity' => 36],
            ['name' => '11 PS 1', 'grade_level' => '11', 'major' => 'PS', 'class_number' => 1, 'capacity' => 36],
            ['name' => '11 PS 2', 'grade_level' => '11', 'major' => 'PS', 'class_number' => 2, 'capacity' => 36],

            // Grade 12 (XII)
            ['name' => '12 RPL 1', 'grade_level' => '12', 'major' => 'RPL', 'class_number' => 1, 'capacity' => 36],
            ['name' => '12 RPL 2', 'grade_level' => '12', 'major' => 'RPL', 'class_number' => 2, 'capacity' => 36],
            ['name' => '12 TKJ 1', 'grade_level' => '12', 'major' => 'TKJ', 'class_number' => 1, 'capacity' => 36],
            ['name' => '12 TKJ 2', 'grade_level' => '12', 'major' => 'TKJ', 'class_number' => 2, 'capacity' => 36],
            ['name' => '12 AKL 1', 'grade_level' => '12', 'major' => 'AKL', 'class_number' => 1, 'capacity' => 36],
            ['name' => '12 AKL 2', 'grade_level' => '12', 'major' => 'AKL', 'class_number' => 2, 'capacity' => 36],
            ['name' => '12 AKL 3', 'grade_level' => '12', 'major' => 'AKL', 'class_number' => 3, 'capacity' => 36],
            ['name' => '12 AKL 4', 'grade_level' => '12', 'major' => 'AKL', 'class_number' => 4, 'capacity' => 36],
            ['name' => '12 BDP 1', 'grade_level' => '12', 'major' => 'BDP', 'class_number' => 1, 'capacity' => 36],
            ['name' => '12 BDP 2', 'grade_level' => '12', 'major' => 'BDP', 'class_number' => 2, 'capacity' => 36],
            ['name' => '12 OTKP 1', 'grade_level' => '12', 'major' => 'OTKP', 'class_number' => 1, 'capacity' => 36],
            ['name' => '12 OTKP 2', 'grade_level' => '12', 'major' => 'OTKP', 'class_number' => 2, 'capacity' => 36],
            ['name' => '12 MM 1', 'grade_level' => '12', 'major' => 'MM', 'class_number' => 1, 'capacity' => 36],
            ['name' => '12 MM 2', 'grade_level' => '12', 'major' => 'MM', 'class_number' => 2, 'capacity' => 36],
            ['name' => '12 PS 1', 'grade_level' => '12', 'major' => 'PS', 'class_number' => 1, 'capacity' => 36],
            ['name' => '12 PS 2', 'grade_level' => '12', 'major' => 'PS', 'class_number' => 2, 'capacity' => 36],
        ];

        foreach ($classrooms as $classroom) {
            Classroom::create($classroom);
        }
    }
}
