<?php

namespace Database\Seeders;

use App\Enums\Gender;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Admin User
        $this->command->info('Creating admin user...');
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@skansaba.sch.id',
            'username' => 'admin',
            'password' => bcrypt('password'),
            'is_active' => true,
        ]);
        $admin->assignRole('admin');
        $this->command->info("✓ Admin created");

        // Create Teachers with Teacher records
        $this->command->info('Creating teachers...');
        $teachers = [
            ['name' => 'Dra. Siti Aminah, M.Pd', 'nip' => '196501011990032001'],
            ['name' => 'Dr. Ahmad Wijaya, S.Pd', 'nip' => '197003151995121001'],
            ['name' => 'Sri Rahayu, S.Pd, M.Si', 'nip' => '197506202000122001'],
            ['name' => 'Budi Santoso, S.Pd', 'nip' => '198008102005011003'],
            ['name' => 'Dewi Kartika, S.Pd', 'nip' => '198205152006042002'],
            ['name' => 'Eko Prasetyo, S.Kom', 'nip' => '198507222008121001'],
            ['name' => 'Fitri Handayani, S.Pd', 'nip' => '199001102012032001'],
            ['name' => 'Hendra Kusuma, S.Pd', 'nip' => '199203052015011002'],
        ];

        foreach ($teachers as $teacherData) {
            $username = Str::slug(explode(',', $teacherData['name'])[0]);

            $user = User::create([
                'name' => $teacherData['name'],
                'email' => strtolower($username) . '@skansaba.sch.id',
                'username' => $username,
                'password' => bcrypt('password'),
                'is_active' => true,
            ]);
            $user->assignRole('teacher');

            Teacher::create([
                'user_id' => $user->id,
                'nip' => $teacherData['nip'],
                'date_of_birth' => now()->subYears(rand(30, 55))->subDays(rand(1, 365)),
            ]);
        }
        $this->command->info("✓ Teacher created");

        // Create Students with Student records
        $this->command->info('Creating students...');
        $classrooms = Classroom::all();
        $studentNumber = 1;

        foreach ($classrooms as $classroom) {
            $this->command->info("  Creating students for {$classroom->name}...");

            for ($i = 1; $i <= 10; $i++) {
                $nisn = str_pad($studentNumber, 10, '0', STR_PAD_LEFT);
                $nis = str_pad($studentNumber, 5, '0', STR_PAD_LEFT);
                $firstName = ['Ahmad', 'Budi', 'Citra', 'Desi', 'Eko', 'Fitri', 'Gilang', 'Hana', 'Indra', 'Joko'][rand(0, 9)];
                $lastName = ['Saputra', 'Wijaya', 'Pratama', 'Santoso', 'Kusuma', 'Dewi', 'Putri', 'Ramadhan', 'Setiawan', 'Wati'][rand(0, 9)];
                $name = "$firstName $lastName";
                $username = strtolower($firstName . $lastName . $studentNumber);
                $gender = ($i % 2 == 0) ? Gender::MALE->value : Gender::FEMALE->value;

                $user = User::create([
                    'name' => $name,
                    'email' => $username . '@student.skansaba.sch.id',
                    'username' => $username,
                    'password' => bcrypt('password'),
                    'is_active' => true,
                ]);
                $user->assignRole('student');

                Student::create([
                    'user_id' => $user->id,
                    'classroom_id' => $classroom->id,
                    'nisn' => $nisn,
                    'nis' => $nis,
                    'gender' => $gender,
                    'date_of_birth' => now()->subYears(rand(16, 18))->subDays(rand(1, 365)),
                    'phone' => '08' . rand(1000000000, 9999999999),
                    'address' => 'Jl. Contoh No. ' . rand(1, 100) . ', Jakarta',
                    'entry_year' => now()->year - (($classroom->grade_level - 10)),
                    'parent_name' => 'Orang Tua ' . $name,
                    'parent_phone' => '08' . rand(1000000000, 9999999999),
                ]);

                $studentNumber++;
            }
        }

        $this->command->info('✓ Total students created: ' . ($studentNumber - 1));
    }
}
