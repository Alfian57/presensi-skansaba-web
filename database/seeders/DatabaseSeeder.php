<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Essential seeders - always run for both local and production
        $this->call([
            RoleAndPermissionSeeder::class,
            AttendanceConfigSeeder::class,
            ClassroomSeeder::class,
            SubjectSeeder::class,
            HolidaySeeder::class,
        ]);

        // Environment-specific seeders
        if (app()->environment('local', 'testing')) {
            $this->command->info('');
            $this->command->info('Running LOCAL development seeders...');
            $this->command->info('');
            
            $this->call([
                UserSeeder::class,              // Admin, teachers, students
                HomeroomTeacherSeeder::class,   // Assign homeroom teachers
                ScheduleSeeder::class,          // Generate schedules
                AttendanceSeeder::class,        // Generate attendance data
                ClassAbsenceSeeder::class,      // Generate class absences
            ]);

            $this->command->info('');
            $this->command->info('====================================');
            $this->command->info('LOCAL DATABASE SEEDED SUCCESSFULLY!');
            $this->command->info('====================================');
            $this->command->info('');
            $this->command->info('Default credentials:');
            $this->command->info('  Admin:   admin@skansaba.sch.id / password');
            $this->command->info('  Teacher: Check teachers table');
            $this->command->info('  Student: Check students table (NISN as username)');
            $this->command->info('');
        } else {
            // Production: only create admin user
            $this->command->info('');
            $this->command->info('Running PRODUCTION seeders...');
            $this->command->info('');
            
            $this->call([
                ProductionUserSeeder::class,
            ]);

            $this->command->info('');
            $this->command->info('=====================================');
            $this->command->info('PRODUCTION DATABASE SEEDED SUCCESSFULLY!');
            $this->command->info('=====================================');
            $this->command->info('');
            $this->command->warn('IMPORTANT: Change the default admin password immediately!');
            $this->command->info('');
        }
    }
}
