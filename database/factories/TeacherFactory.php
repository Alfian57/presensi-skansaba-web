<?php

namespace Database\Factories;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    public function definition(): array
    {
        // Generate NIP format: YYYYMMDD YYYYMM G NNN
        // Birth year, birth month day, appointment year month, gender, sequence
        $birthYear = $this->faker->numberBetween(1965, 1990);
        $birthMonth = str_pad($this->faker->numberBetween(1, 12), 2, '0', STR_PAD_LEFT);
        $birthDay = str_pad($this->faker->numberBetween(1, 28), 2, '0', STR_PAD_LEFT);
        $appointYear = $this->faker->numberBetween($birthYear + 22, 2020);
        $appointMonth = str_pad($this->faker->numberBetween(1, 12), 2, '0', STR_PAD_LEFT);
        $gender = $this->faker->numberBetween(1, 2);
        $sequence = str_pad($this->faker->numberBetween(1, 999), 3, '0', STR_PAD_LEFT);

        $nip = "{$birthYear}{$birthMonth}{$birthDay}{$appointYear}{$appointMonth}{$gender}{$sequence}";

        return [
            'nip' => $nip,
            'date_of_birth' => $this->faker->dateTimeBetween("{$birthYear}-01-01", "{$birthYear}-12-31"),
        ];
    }
}
