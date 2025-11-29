@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <div class="row mt-3">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informasi Kelas</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Nama Kelas</th>
                            <td>: {{ $classroom->name }}</td>
                        </tr>
                        <tr>
                            <th>Tingkat</th>
                            <td>: {{ $classroom->grade_level }}</td>
                        </tr>
                        <tr>
                            <th>Jurusan</th>
                            <td>: {{ $classroom->major }}</td>
                        </tr>
                        <tr>
                            <th>Nomor Kelas</th>
                            <td>: {{ $classroom->class_number }}</td>
                        </tr>
                        <tr>
                            <th>Jumlah Siswa</th>
                            <td>: {{ $classroom->students->count() }} siswa</td>
                        </tr>
                    </table>

                    <div class="text-end mt-3">
                        <a href="{{ route('dashboard.classrooms.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('dashboard.classrooms.edit', $classroom->slug) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Statistik Kehadiran</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="card bg-success text-white mb-3">
                                <div class="card-body">
                                    <h4>{{ $stats['present'] ?? 0 }}</h4>
                                    <small>Hadir</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white mb-3">
                                <div class="card-body">
                                    <h4>{{ $stats['late'] ?? 0 }}</h4>
                                    <small>Terlambat</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white mb-3">
                                <div class="card-body">
                                    <h4>{{ $stats['absent'] ?? 0 }}</h4>
                                    <small>Alpa</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-primary text-white mb-3">
                                <div class="card-body">
                                    <h4>{{ $stats['sick'] ?? 0 }}</h4>
                                    <small>Sakit</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Daftar Siswa</h5>
                </div>
                <div class="card-body">
                    @if($classroom->students->isEmpty())
                        <p class="text-center text-muted">Belum ada siswa di kelas ini</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>NISN</th>
                                        <th>Nama</th>
                                        <th>Jenis Kelamin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($classroom->students as $student)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $student->nisn }}</td>
                                            <td>{{ $student->user->name }}</td>
                                            <td>{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Jadwal Pelajaran</h5>
                </div>
                <div class="card-body">
                    @if($classroom->schedules->isEmpty())
                        <p class="text-center text-muted">Belum ada jadwal untuk kelas ini</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Hari</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Guru</th>
                                        <th>Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($classroom->schedules->sortBy('day')->sortBy('start_time') as $schedule)
                                        <tr>
                                            <td>{{ $schedule->day }}</td>
                                            <td>{{ $schedule->subject->name }}</td>
                                            <td>{{ $schedule->teacher->user->name }}</td>
                                            <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection