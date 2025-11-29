<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Attendance Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for attendance system including check-in/out times,
    | late thresholds, and QR code settings.
    |
    */

    'check_in' => [
        'enabled' => env('ATTENDANCE_CHECK_IN_ENABLED', true),
        'start_time' => env('ATTENDANCE_CHECK_IN_START', '06:00'),
        'end_time' => env('ATTENDANCE_CHECK_IN_END', '09:00'),
        'late_threshold' => env('ATTENDANCE_LATE_THRESHOLD', '07:30'),
    ],

    'check_out' => [
        'enabled' => env('ATTENDANCE_CHECK_OUT_ENABLED', true),
        'start_time' => env('ATTENDANCE_CHECK_OUT_START', '14:00'),
        'end_time' => env('ATTENDANCE_CHECK_OUT_END', '17:00'),
    ],

    'qr_code' => [
        'refresh_interval' => env('ATTENDANCE_QR_REFRESH_INTERVAL', 'daily'), // daily, hourly, manual
        'expiry_minutes' => env('ATTENDANCE_QR_EXPIRY', 1440), // 24 hours
        'size' => env('ATTENDANCE_QR_SIZE', 300),
    ],

    'location' => [
        'enabled' => env('ATTENDANCE_LOCATION_ENABLED', true),
        'required' => env('ATTENDANCE_LOCATION_REQUIRED', false),
        'school_latitude' => env('SCHOOL_LATITUDE', -6.2088),
        'school_longitude' => env('SCHOOL_LONGITUDE', 106.8456),
        'radius_meters' => env('ATTENDANCE_RADIUS_METERS', 100),
    ],

    'working_days' => [
        'monday' => true,
        'tuesday' => true,
        'wednesday' => true,
        'thursday' => true,
        'friday' => true,
        'saturday' => true,
        'sunday' => false,
    ],

    'auto_absent' => [
        'enabled' => env('ATTENDANCE_AUTO_ABSENT_ENABLED', true),
        'schedule_time' => env('ATTENDANCE_AUTO_ABSENT_TIME', '00:00'),
    ],

];
