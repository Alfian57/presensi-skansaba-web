@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        {{-- Header --}}
        <div class="card border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body text-white p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="mb-2"><i class="fas fa-clipboard-check me-2"></i>Presensi Siswa Hari Ini</h2>
                        <p class="mb-0 opacity-75">
                            <i class="far fa-calendar me-2"></i>{{ $today->isoFormat('dddd, D MMMM Y') }}
                            <span class="ms-3"><i class="far fa-clock me-2"></i><span
                                    id="current-time">{{ \Carbon\Carbon::now()->format('H:i:s') }}</span></span>
                            <span class="ms-3 badge bg-light text-dark"><i class="fas fa-sync-alt me-1"></i>Auto refresh
                                30s</span>
                        </p>
                    </div>
                    <div class="col-md-4 text-end d-none d-md-block">
                        <h1 class="display-4 mb-0">{{ $classrooms->count() }}</h1>
                        <small>Total Kelas</small>
                    </div>
                </div>
            </div>
        </div>

        @if($classrooms->isEmpty())
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Tidak ada data kelas</h4>
                    <p class="mb-0 text-muted">Belum ada kelas yang terdaftar di sistem.</p>
                </div>
            </div>
        @else
            <div class="row">
                @foreach($classrooms as $classroom)
                    @php
                        $totalStudents = $classroom->students->count();
                        $presentCount = 0;
                        $lateCount = 0;
                        $sickCount = 0;
                        $permissionCount = 0;
                        $absentCount = 0;

                        foreach ($classroom->students as $student) {
                            $attendance = $student->attendances->first();
                            if ($attendance) {
                                switch ($attendance->status->value) {
                                    case 'present':
                                        $presentCount++;
                                        break;
                                    case 'late':
                                        $lateCount++;
                                        break;
                                    case 'sick':
                                        $sickCount++;
                                        break;
                                    case 'permission':
                                        $permissionCount++;
                                        break;
                                    case 'absent':
                                        $absentCount++;
                                        break;
                                }
                            } else {
                                $absentCount++;
                            }
                        }
                        $attendancePercentage = $totalStudents > 0 ? round((($presentCount + $lateCount) / $totalStudents) * 100, 1) : 0;
                    @endphp

                    <div class="col-12 col-lg-6 col-xl-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm attendance-card">
                            <div class="card-header bg-gradient-primary text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-1"><i class="fas fa-school me-2"></i>{{ $classroom->name }}</h5>
                                        <small><i class="fas fa-users me-1"></i>{{ $totalStudents }} Siswa</small>
                                    </div>
                                    <div class="text-end">
                                        <h3 class="mb-0">{{ $attendancePercentage }}%</h3>
                                        <small>Kehadiran</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="stat-box bg-success-light text-center p-3 rounded">
                                            <i class="fas fa-check-circle text-success mb-2" style="font-size: 1.5rem;"></i>
                                            <h4 class="mb-0 text-success">{{ $presentCount }}</h4>
                                            <small class="text-muted">Hadir</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stat-box bg-warning-light text-center p-3 rounded">
                                            <i class="fas fa-clock text-warning mb-2" style="font-size: 1.5rem;"></i>
                                            <h4 class="mb-0 text-warning">{{ $lateCount }}</h4>
                                            <small class="text-muted">Terlambat</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stat-box bg-info-light text-center p-3 rounded">
                                            <i class="fas fa-notes-medical text-info mb-2" style="font-size: 1.5rem;"></i>
                                            <h4 class="mb-0 text-info">{{ $sickCount }}</h4>
                                            <small class="text-muted">Sakit</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stat-box bg-primary-light text-center p-3 rounded">
                                            <i class="fas fa-file-alt text-primary mb-2" style="font-size: 1.5rem;"></i>
                                            <h4 class="mb-0 text-primary">{{ $permissionCount }}</h4>
                                            <small class="text-muted">Izin</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="stat-box bg-danger-light text-center p-2 rounded">
                                        <i class="fas fa-times-circle text-danger me-2"></i>
                                        <strong class="text-danger">{{ $absentCount }}</strong>
                                        <small class="text-muted ms-1">Siswa Alpha/Belum Presensi</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-light border-0">
                                <a href="{{ route('dashboard.display.attendance.classroom', $classroom) }}"
                                    class="btn btn-sm btn-outline-primary w-100">
                                    <i class="fas fa-eye me-1"></i>Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-success-light {
            background-color: rgba(40, 167, 69, 0.1);
        }

        .bg-warning-light {
            background-color: rgba(255, 193, 7, 0.1);
        }

        .bg-info-light {
            background-color: rgba(23, 162, 184, 0.1);
        }

        .bg-primary-light {
            background-color: rgba(0, 123, 255, 0.1);
        }

        .bg-danger-light {
            background-color: rgba(220, 53, 69, 0.1);
        }

        .attendance-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .attendance-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
        }

        .stat-box {
            transition: transform 0.2s ease;
        }

        .stat-box:hover {
            transform: scale(1.05);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .attendance-card {
            animation: fadeIn 0.5s ease;
        }
    </style>

    <script>
        // Update current time
        function updateTime() {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            document.getElementById('current-time').textContent = `${hours}:${minutes}:${seconds}`;
        }

        setInterval(updateTime, 1000);

        // Auto refresh every 30 seconds
        setTimeout(function () {
            location.reload();
        }, 30000);
    </script>
@endsection