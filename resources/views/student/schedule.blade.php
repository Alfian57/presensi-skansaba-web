@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">Jadwal Pelajaran</h2>

        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0">Kelas: {{ $student->classroom->name ?? 'Belum ada kelas' }}</h6>
            </div>
        </div>

        @php
            $dayLabels = [
                'monday' => 'Senin',
                'tuesday' => 'Selasa',
                'wednesday' => 'Rabu',
                'thursday' => 'Kamis',
                'friday' => 'Jumat',
                'saturday' => 'Sabtu',
            ];
        @endphp

        @if($schedules->count() > 0)
            @foreach($dayLabels as $dayKey => $dayLabel)
                @if(isset($schedules[$dayKey]) && $schedules[$dayKey]->count() > 0)
                    <div class="card mb-3">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">{{ $dayLabel }}</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th width="150">Waktu</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Guru</th>
                                            <th>Ruangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($schedules[$dayKey] as $schedule)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                                                <td>{{ $schedule->subject->name ?? '-' }}</td>
                                                <td>{{ $schedule->teacher->user->name ?? '-' }}</td>
                                                <td>{{ $schedule->room ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Belum ada jadwal untuk kelas ini.
            </div>
        @endif

        <div class="mt-3">
            <a href="{{ route('dashboard.student.home') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
@endsection
