@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    {{-- Header Dashboard --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white shadow-lg">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="text-white mb-2"><i class="fas fa-tachometer-alt me-2"></i>Dashboard Presensi</h2>
                            <p class="mb-0 opacity-75">
                                <i class="far fa-calendar me-2"></i>{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
                                <span class="ms-3"><i
                                        class="far fa-clock me-2"></i>{{ \Carbon\Carbon::now()->format('H:i') }} WIB</span>
                            </p>
                        </div>
                        <div class="col-md-4 text-end d-none d-md-block">
                            <div class="attendance-percentage">
                                <h1 class="display-4 mb-0">{{ $attendanceStats['percentage'] }}%</h1>
                                <small>Kehadiran Hari Ini</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row mb-4">
        {{-- Total Siswa --}}
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small mb-1">Total Siswa</div>
                            <h3 class="mb-0">{{ number_format($stats['total_students']) }}</h3>
                        </div>
                        <div class="icon-circle bg-primary">
                            <i class="fas fa-user-graduate text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Guru --}}
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small mb-1">Total Guru</div>
                            <h3 class="mb-0">{{ number_format($stats['total_teachers']) }}</h3>
                        </div>
                        <div class="icon-circle bg-warning">
                            <i class="fas fa-chalkboard-teacher text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Kelas --}}
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small mb-1">Total Kelas</div>
                            <h3 class="mb-0">{{ number_format($stats['total_classrooms']) }}</h3>
                        </div>
                        <div class="icon-circle bg-danger">
                            <i class="fas fa-school text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Jadwal --}}
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small mb-1">Total Jadwal</div>
                            <h3 class="mb-0">{{ number_format($stats['total_schedules']) }}</h3>
                        </div>
                        <div class="icon-circle bg-info">
                            <i class="fas fa-calendar-alt text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Attendance Statistics Today --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2 text-primary"></i>Statistik Presensi Hari Ini</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-2 col-6 mb-3">
                            <div class="stat-box">
                                <i class="fas fa-users text-primary mb-2" style="font-size: 2rem;"></i>
                                <h4 class="mb-0">{{ $attendanceStats['total'] }}</h4>
                                <small class="text-muted">Total Presensi</small>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <div class="stat-box">
                                <i class="fas fa-check-circle text-success mb-2" style="font-size: 2rem;"></i>
                                <h4 class="mb-0">{{ $attendanceStats['present'] }}</h4>
                                <small class="text-muted">Hadir</small>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <div class="stat-box">
                                <i class="fas fa-clock text-warning mb-2" style="font-size: 2rem;"></i>
                                <h4 class="mb-0">{{ $attendanceStats['late'] }}</h4>
                                <small class="text-muted">Terlambat</small>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <div class="stat-box">
                                <i class="fas fa-notes-medical text-info mb-2" style="font-size: 2rem;"></i>
                                <h4 class="mb-0">{{ $attendanceStats['sick'] }}</h4>
                                <small class="text-muted">Sakit</small>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <div class="stat-box">
                                <i class="fas fa-file-alt text-primary mb-2" style="font-size: 2rem;"></i>
                                <h4 class="mb-0">{{ $attendanceStats['permission'] }}</h4>
                                <small class="text-muted">Izin</small>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <div class="stat-box">
                                <i class="fas fa-times-circle text-danger mb-2" style="font-size: 2rem;"></i>
                                <h4 class="mb-0">{{ $attendanceStats['absent'] }}</h4>
                                <small class="text-muted">Alpha</small>
                            </div>
                        </div>
                    </div>

                    @if($notCheckedIn > 0)
                        <div class="alert alert-warning mt-3 mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>{{ $notCheckedIn }}</strong> siswa belum melakukan presensi hari ini
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Charts & Top Classes --}}
    <div class="row mb-4">
        {{-- Weekly Trend --}}
        <div class="col-lg-7 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2 text-success"></i>Tren Kehadiran 7 Hari Terakhir</h5>
                </div>
                <div class="card-body">
                    @if(collect($weeklyTrend)->sum('count') > 0)
                        <canvas id="weeklyChart" height="100"></canvas>
                    @else
                        <div class="d-flex flex-column align-items-center justify-content-center py-5">
                            <i class="fas fa-chart-line fa-4x text-muted mb-3"></i>
                            <h6 class="text-muted">Belum Ada Data</h6>
                            <p class="text-muted small text-center">Data kehadiran 7 hari terakhir akan ditampilkan di sini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Top Classes --}}
        <div class="col-lg-5 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="fas fa-trophy me-2 text-warning"></i>Top 5 Kelas Kehadiran Tertinggi</h5>
                </div>
                <div class="card-body">
                    @forelse($topClasses as $index => $class)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold">
                                    <span class="badge bg-primary me-2">{{ $index + 1 }}</span>
                                    {{ $class['name'] }}
                                </span>
                                <span class="text-muted">{{ $class['total'] }}/{{ $class['total_students'] }}</span>
                            </div>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ $class['percentage'] }}%">
                                    {{ $class['percentage'] }}%
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>Belum ada data presensi hari ini</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Activity & Monthly Summary --}}
    <div class="row mb-4">
        {{-- Recent Attendance --}}
        <div class="col-lg-8 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="fas fa-history me-2 text-info"></i>Aktivitas Presensi Terbaru</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Siswa</th>
                                    <th>Kelas</th>
                                    <th>Status</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentAttendances as $attendance)
                                            <tr>
                                                <td>{{ $attendance->student->user->name }}</td>
                                                <td><span class="badge bg-secondary">{{ $attendance->student->classroom->name }}</span>
                                                </td>
                                                <td>
                                       @php
                                        $statusConfig = [
                                            'present' => ['label' => 'Hadir', 'class' => 'success'],
                                            'late' => ['label' => 'Terlambat', 'class' => 'warning'],
                                            'sick' => ['label' => 'Sakit', 'class' => 'info'],
                                            'permission' => ['label' => 'Izin', 'class' => 'primary'],
                                            'absent' => ['label' => 'Alpha', 'class' => 'danger'],
                                        ];
                                        $status = $statusConfig[$attendance->status->value] ?? ['label' => 'N/A', 'class' => 'secondary'];
                                    @endphp
                                                    <span class="badge bg-{{ $status['class'] }}">{{ $status['label'] }}</span>
                                                </td>
                                                <td>
                                                    <i class="far fa-clock me-1"></i>
                                                    {{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : '-' }}
                                                </td>
                                                <td>
                                                    <i class="far fa-clock me-1"></i>
                                                    {{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : '-' }}
                                                </td>
                                            </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">Belum ada aktivitas presensi hari
                                            ini</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Monthly Summary & Recent Skipping --}}
        <div class="col-lg-4 mb-3">
            {{-- Monthly Summary --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="far fa-calendar-alt me-2 text-primary"></i>Ringkasan Bulan Ini</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <span><i class="fas fa-clipboard-check me-2 text-primary"></i>Total Presensi</span>
                        <strong>{{ number_format($monthlyStats['total_attendance']) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <span><i class="fas fa-chart-line me-2 text-success"></i>Rata-rata Harian</span>
                        <strong>{{ number_format($monthlyStats['avg_daily_attendance']) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-user-times me-2 text-danger"></i>Bolos Pelajaran</span>
                        <strong>{{ number_format($monthlyStats['total_skipping']) }}</strong>
                    </div>
                </div>
            </div>

            {{-- Recent Skipping --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2 text-danger"></i>Bolos Terakhir</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentSkipping as $skip)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong class="d-block">{{ $skip->student->user->name }}</strong>
                                        <small class="text-muted">{{ $skip->schedule->subject->name ?? 'N/A' }}</small>
                                    </div>
                                    <small
                                        class="text-muted">{{ \Carbon\Carbon::parse($skip->created_at)->diffForHumans() }}</small>
                                </div>
                            </div>
                        @empty
                            <div class="list-group-item text-center text-muted py-3">
                                <i class="fas fa-check-circle fa-2x mb-2"></i>
                                <p class="mb-0 small">Tidak ada laporan bolos</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Weekly Attendance Chart
        @if(collect($weeklyTrend)->sum('count') > 0)
        const ctx = document.getElementById('weeklyChart');
        const weeklyData = @json($weeklyTrend);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: weeklyData.map(d => d.full_date),
                datasets: [{
                    label: 'Kehadiran',
                    data: weeklyData.map(d => d.count),
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
        @endif
    </script>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-circle i {
            font-size: 1.5rem;
        }

        .card-hover {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .stat-box {
            padding: 10px;
        }

        canvas {
            max-height: 300px;
        }
    </style>
@endpush