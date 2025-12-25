@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">Dashboard Siswa</h2>

        {{-- Student Info --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        @if(auth()->user()->profile_picture)
                            <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" 
                                 class="rounded-circle" width="80" height="80" alt="Profile">
                        @else
                            <i class="fas fa-user-circle" style="font-size: 80px; color: #6c757d;"></i>
                        @endif
                    </div>
                    <div class="col">
                        <h4 class="mb-1">{{ auth()->user()->name }}</h4>
                        <p class="text-muted mb-0">
                            NISN: {{ $student->nisn }} |
                            Kelas: {{ $student->classroom->name ?? 'Belum ada kelas' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Today's Attendance Status --}}
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="fas fa-calendar-check"></i> Presensi Hari Ini</h6>
            </div>
            <div class="card-body">
                @if($todayAttendance)
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Status:</strong>
                                @switch($todayAttendance->status->value ?? $todayAttendance->status)
                                    @case('present')
                                        <span class="badge bg-success">Hadir</span>
                                        @break
                                    @case('late')
                                        <span class="badge bg-warning">Terlambat</span>
                                        @break
                                    @case('sick')
                                        <span class="badge bg-info">Sakit</span>
                                        @break
                                    @case('permission')
                                        <span class="badge bg-secondary">Izin</span>
                                        @break
                                    @case('absent')
                                        <span class="badge bg-danger">Tidak Hadir</span>
                                        @break
                                @endswitch
                            </p>
                            <p><strong>Jam Masuk:</strong> {{ $todayAttendance->check_in_time ? \Carbon\Carbon::parse($todayAttendance->check_in_time)->format('H:i') : '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Jam Pulang:</strong> {{ $todayAttendance->check_out_time ? \Carbon\Carbon::parse($todayAttendance->check_out_time)->format('H:i') : '-' }}</p>
                        </div>
                    </div>
                @else
                    <p class="text-muted mb-0">Anda belum melakukan presensi hari ini.</p>
                @endif
            </div>
        </div>

        <div class="row">
            {{-- Monthly Summary --}}
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Rekap Bulan Ini</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col">
                                <h4 class="text-success">{{ $attendanceSummary['present'] }}</h4>
                                <small>Hadir</small>
                            </div>
                            <div class="col">
                                <h4 class="text-warning">{{ $attendanceSummary['late'] }}</h4>
                                <small>Terlambat</small>
                            </div>
                            <div class="col">
                                <h4 class="text-info">{{ $attendanceSummary['sick'] }}</h4>
                                <small>Sakit</small>
                            </div>
                            <div class="col">
                                <h4 class="text-secondary">{{ $attendanceSummary['permission'] }}</h4>
                                <small>Izin</small>
                            </div>
                            <div class="col">
                                <h4 class="text-danger">{{ $attendanceSummary['absent'] }}</h4>
                                <small>Absen</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('dashboard.student.attendance') }}" class="btn btn-sm btn-outline-info">
                            Lihat Riwayat Lengkap
                        </a>
                    </div>
                </div>
            </div>

            {{-- Today's Schedule --}}
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="fas fa-clock"></i> Jadwal Hari Ini</h6>
                    </div>
                    <div class="card-body">
                        @if($todaySchedules->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Waktu</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Guru</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($todaySchedules as $schedule)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                                                <td>{{ $schedule->subject->name ?? '-' }}</td>
                                                <td>{{ $schedule->teacher->user->name ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted mb-0">Tidak ada jadwal hari ini.</p>
                        @endif
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('dashboard.student.schedule') }}" class="btn btn-sm btn-outline-success">
                            Lihat Jadwal Lengkap
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
