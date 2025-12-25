<?php

namespace App\Enums;

enum ConfigKey: string
{
    case CHECK_IN_START = 'check_in_start';
    case CHECK_IN_END = 'check_in_end';
    case LATE_THRESHOLD = 'late_threshold';
    case CHECK_OUT_START = 'check_out_start';
    case QR_CHECK_IN = 'qr_check_in';
    case QR_CHECK_OUT = 'qr_check_out';
    case SCHOOL_DAYS = 'school_days';
    case SCHOOL_NAME = 'school_name';
    case SCHOOL_ADDRESS = 'school_address';
    case SCHOOL_PHONE = 'school_phone';
    case ACADEMIC_YEAR = 'academic_year';
    case CURRENT_SEMESTER = 'current_semester';

    public function label(): string
    {
        return match ($this) {
            self::CHECK_IN_START => 'Waktu Mulai Presensi Masuk',
            self::CHECK_IN_END => 'Waktu Berakhir Presensi Masuk',
            self::LATE_THRESHOLD => 'Batas Waktu Keterlambatan',
            self::CHECK_OUT_START => 'Waktu Mulai Presensi Pulang',
            self::QR_CHECK_IN => 'QR Code Presensi Masuk',
            self::QR_CHECK_OUT => 'QR Code Presensi Pulang',
            self::SCHOOL_DAYS => 'Hari Masuk Sekolah',
            self::SCHOOL_NAME => 'Nama Sekolah',
            self::SCHOOL_ADDRESS => 'Alamat Sekolah',
            self::SCHOOL_PHONE => 'Telepon Sekolah',
            self::ACADEMIC_YEAR => 'Tahun Ajaran',
            self::CURRENT_SEMESTER => 'Semester Saat Ini',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::CHECK_IN_START => 'Waktu mulai siswa dapat melakukan presensi masuk (format: HH:MM:SS)',
            self::CHECK_IN_END => 'Waktu berakhir siswa dapat melakukan presensi masuk (format: HH:MM:SS)',
            self::LATE_THRESHOLD => 'Batas waktu siswa dianggap terlambat (format: HH:MM:SS)',
            self::CHECK_OUT_START => 'Waktu mulai siswa dapat melakukan presensi pulang (format: HH:MM:SS)',
            self::QR_CHECK_IN => 'Token QR Code untuk presensi masuk',
            self::QR_CHECK_OUT => 'Token QR Code untuk presensi pulang',
            self::SCHOOL_DAYS => 'Hari-hari sekolah (JSON array)',
            self::SCHOOL_NAME => 'Nama lengkap sekolah',
            self::SCHOOL_ADDRESS => 'Alamat lengkap sekolah',
            self::SCHOOL_PHONE => 'Nomor telepon sekolah',
            self::ACADEMIC_YEAR => 'Tahun ajaran saat ini (format: 2024/2025)',
            self::CURRENT_SEMESTER => 'Semester aktif saat ini (1 = Ganjil, 2 = Genap)',
        };
    }

    public function defaultValue(): string
    {
        return match ($this) {
            self::CHECK_IN_START => '06:00:00',
            self::CHECK_IN_END => '08:00:00',
            self::LATE_THRESHOLD => '07:00:00',
            self::CHECK_OUT_START => '14:00:00',
            self::QR_CHECK_IN => '',
            self::QR_CHECK_OUT => '',
            self::SCHOOL_DAYS => json_encode(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday']),
            self::SCHOOL_NAME => 'Presensi Skansaba',
            self::SCHOOL_ADDRESS => '',
            self::SCHOOL_PHONE => '',
            self::ACADEMIC_YEAR => '2024/2025',
            self::CURRENT_SEMESTER => '1',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
