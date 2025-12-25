@props([
    'type' => 'default',
    'text' => null,
])

@php
    $colors = [
        'success' => 'bg-success',
        'danger' => 'bg-danger',
        'warning' => 'bg-warning text-dark',
        'info' => 'bg-info',
        'primary' => 'bg-primary',
        'secondary' => 'bg-secondary',
        'default' => 'bg-secondary',
        // Attendance statuses
        'hadir' => 'bg-success',
        'terlambat' => 'bg-warning text-dark',
        'sakit' => 'bg-info',
        'izin' => 'bg-primary',
        'alpha' => 'bg-danger',
        // User status
        'active' => 'bg-success',
        'inactive' => 'bg-danger',
    ];

    $colorClass = $colors[strtolower($type)] ?? $colors['default'];
@endphp

<span {{ $attributes->merge(['class' => 'badge ' . $colorClass]) }}>
    {{ $text ?? $slot }}
</span>
