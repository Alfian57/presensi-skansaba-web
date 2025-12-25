<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    // Nama depan Indonesia
    private static array $firstNamesMale = [
        'Ahmad', 'Budi', 'Dedi', 'Eko', 'Fajar', 'Gilang', 'Hendra', 'Irwan',
        'Joko', 'Kurniawan', 'Lukman', 'Muhammad', 'Nur', 'Oki', 'Purnomo',
        'Rizki', 'Surya', 'Taufik', 'Umar', 'Vino', 'Wahyu', 'Yusuf', 'Zainal',
        'Adi', 'Bayu', 'Dimas', 'Feri', 'Gunawan', 'Hasan', 'Imam', 'Kevin',
    ];

    private static array $firstNamesFemale = [
        'Ani', 'Bunga', 'Citra', 'Dewi', 'Eka', 'Fitri', 'Gita', 'Hana',
        'Indah', 'Jasmine', 'Kartika', 'Lestari', 'Maya', 'Nurhaliza', 'Okti',
        'Putri', 'Ratna', 'Sari', 'Tika', 'Umi', 'Vina', 'Wulan', 'Yanti',
        'Zahra', 'Ayu', 'Bella', 'Diana', 'Elsa', 'Fika', 'Galuh', 'Hesti',
    ];

    private static array $lastNames = [
        'Saputra', 'Wijaya', 'Pratama', 'Santoso', 'Kusuma', 'Putra', 'Permana',
        'Ramadhan', 'Setiawan', 'Wati', 'Hidayat', 'Nugraha', 'Firmansyah',
        'Prasetyo', 'Suryadi', 'Hartono', 'Prabowo', 'Utomo', 'Suharto',
        'Sulistyo', 'Wibowo', 'Kurniadi', 'Budiman', 'Susanto', 'Mulyadi',
    ];

    public function definition(): array
    {
        $gender = $this->faker->randomElement([Gender::MALE, Gender::FEMALE]);
        $firstNames = $gender === Gender::MALE ? self::$firstNamesMale : self::$firstNamesFemale;
        $firstName = $this->faker->randomElement($firstNames);
        $lastName = $this->faker->randomElement(self::$lastNames);

        $entryYear = now()->year - $this->faker->numberBetween(0, 2);
        $birthYear = $entryYear - $this->faker->numberBetween(15, 17);

        return [
            'nisn' => $this->faker->unique()->numerify('##########'),
            'nis' => $this->faker->unique()->numerify('#####'),
            'gender' => $gender->value,
            'date_of_birth' => $this->faker->dateTimeBetween("$birthYear-01-01", "$birthYear-12-31"),
            'phone' => '08' . $this->faker->numerify('##########'),
            'address' => $this->generateIndonesianAddress(),
            'entry_year' => $entryYear,
            'parent_name' => $this->faker->randomElement(['Bapak', 'Ibu']) . ' ' . $firstName . ' ' . $lastName,
            'parent_phone' => '08' . $this->faker->numerify('##########'),
        ];
    }

    private function generateIndonesianAddress(): string
    {
        $streets = ['Jl. Merdeka', 'Jl. Sudirman', 'Jl. Gatot Subroto', 'Jl. Ahmad Yani', 'Jl. Diponegoro', 
                    'Jl. Imam Bonjol', 'Jl. Pahlawan', 'Jl. Veteran', 'Jl. Mawar', 'Jl. Melati'];
        $cities = ['Jakarta Selatan', 'Jakarta Timur', 'Jakarta Barat', 'Bekasi', 'Tangerang', 
                   'Depok', 'Bogor', 'Bandung', 'Surabaya', 'Semarang'];

        return $this->faker->randomElement($streets) . ' No. ' . $this->faker->numberBetween(1, 200) . 
               ', RT ' . str_pad($this->faker->numberBetween(1, 20), 2, '0', STR_PAD_LEFT) . 
               '/RW ' . str_pad($this->faker->numberBetween(1, 10), 2, '0', STR_PAD_LEFT) . 
               ', ' . $this->faker->randomElement($cities);
    }

    public function forClassroom(Classroom $classroom): static
    {
        return $this->state(fn (array $attributes) => [
            'classroom_id' => $classroom->id,
            'entry_year' => now()->year - ($classroom->grade_level - 10),
        ]);
    }
}
