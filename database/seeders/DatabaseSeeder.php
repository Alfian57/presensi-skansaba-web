<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            AttendanceConfigSeeder::class,
            ClassroomSeeder::class,
            SubjectSeeder::class,
            UserSeeder::class,
            HomeroomTeacherSeeder::class,
            HolidaySeeder::class,
        ]);

        $this->command->info('Database seeded successfully!');
        $this->command->info('Default admin credentials:');
    }
}
