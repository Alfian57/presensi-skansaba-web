<?php

namespace Database\Seeders;

use App\Enums\ConfigKey;
use App\Models\AttendanceConfig;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AttendanceConfigSeeder extends Seeder
{
    public function run(): void
    {
        $configs = [
            [
                'key' => ConfigKey::CHECK_IN_START->value,
                'value' => '06:00:00',
                'type' => 'time',
                'description' => ConfigKey::CHECK_IN_START->description(),
                'is_public' => true,
            ],
            [
                'key' => ConfigKey::CHECK_IN_END->value,
                'value' => '08:00:00',
                'type' => 'time',
                'description' => ConfigKey::CHECK_IN_END->description(),
                'is_public' => true,
            ],
            [
                'key' => ConfigKey::LATE_THRESHOLD->value,
                'value' => '07:00:00',
                'type' => 'time',
                'description' => ConfigKey::LATE_THRESHOLD->description(),
                'is_public' => true,
            ],
            [
                'key' => ConfigKey::CHECK_OUT_START->value,
                'value' => '14:00:00',
                'type' => 'time',
                'description' => ConfigKey::CHECK_OUT_START->description(),
                'is_public' => true,
            ],
            [
                'key' => ConfigKey::QR_CHECK_IN->value,
                'value' => Str::random(32),
                'type' => 'string',
                'description' => ConfigKey::QR_CHECK_IN->description(),
                'is_public' => false,
            ],
            [
                'key' => ConfigKey::QR_CHECK_OUT->value,
                'value' => Str::random(32),
                'type' => 'string',
                'description' => ConfigKey::QR_CHECK_OUT->description(),
                'is_public' => false,
            ],
            [
                'key' => ConfigKey::SCHOOL_DAYS->value,
                'value' => json_encode(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']),
                'type' => 'json',
                'description' => ConfigKey::SCHOOL_DAYS->description(),
                'is_public' => true,
            ],
            [
                'key' => ConfigKey::SCHOOL_NAME->value,
                'value' => 'SMA Skansaba',
                'type' => 'string',
                'description' => ConfigKey::SCHOOL_NAME->description(),
                'is_public' => true,
            ],
            [
                'key' => ConfigKey::SCHOOL_ADDRESS->value,
                'value' => 'Jl. Pendidikan No. 123, Jakarta',
                'type' => 'string',
                'description' => ConfigKey::SCHOOL_ADDRESS->description(),
                'is_public' => true,
            ],
            [
                'key' => ConfigKey::SCHOOL_PHONE->value,
                'value' => '021-12345678',
                'type' => 'string',
                'description' => ConfigKey::SCHOOL_PHONE->description(),
                'is_public' => true,
            ],
            [
                'key' => ConfigKey::ACADEMIC_YEAR->value,
                'value' => '2024/2025',
                'type' => 'string',
                'description' => ConfigKey::ACADEMIC_YEAR->description(),
                'is_public' => true,
            ],
            [
                'key' => ConfigKey::CURRENT_SEMESTER->value,
                'value' => '1',
                'type' => 'integer',
                'description' => ConfigKey::CURRENT_SEMESTER->description(),
                'is_public' => true,
            ],
        ];

        foreach ($configs as $config) {
            AttendanceConfig::create($config);
        }
    }
}
