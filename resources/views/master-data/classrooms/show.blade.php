@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <div class="row mt-3">
        <div class="col-md-4">
            <x-ui.card title="Informasi Kelas" headerClass="bg-primary text-white">
                <table class="table table-borderless">
                    <tr><th width="40%">Nama Kelas</th><td>: {{ $classroom->name }}</td></tr>
                    <tr><th>Tingkat</th><td>: {{ $classroom->grade_level }}</td></tr>
                    <tr><th>Jurusan</th><td>: {{ $classroom->major }}</td></tr>
                    <tr><th>Nomor Kelas</th><td>: {{ $classroom->class_number }}</td></tr>
                    <tr><th>Jumlah Siswa</th><td>: {{ $classroom->students->count() }} siswa</td></tr>
                    <tr><th>Status</th><td>: <x-ui.badge :type="$classroom->is_active ? 'active' : 'inactive'">{{ $classroom->is_active ? 'Aktif' : 'Nonaktif' }}</x-ui.badge></td></tr>
                </table>

                <div class="text-end mt-3">
                    <a href="{{ route('dashboard.classrooms.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <a href="{{ route('dashboard.classrooms.edit', $classroom) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
            </x-ui.card>
        </div>

        <div class="col-md-8">
            <x-ui.card title="Statistik Kehadiran" headerClass="bg-info text-white" class="mb-3">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="card bg-success text-white mb-3"><div class="card-body"><h4>{{ $stats['present'] ?? 0 }}</h4><small>Hadir</small></div></div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white mb-3"><div class="card-body"><h4>{{ $stats['late'] ?? 0 }}</h4><small>Terlambat</small></div></div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white mb-3"><div class="card-body"><h4>{{ $stats['absent'] ?? 0 }}</h4><small>Alpa</small></div></div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-primary text-white mb-3"><div class="card-body"><h4>{{ $stats['sick'] ?? 0 }}</h4><small>Sakit</small></div></div>
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card title="Daftar Siswa" headerClass="bg-primary text-white" class="mb-3">
                @if($classroom->students->isEmpty())
                    <p class="text-center text-muted">Belum ada siswa di kelas ini</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead><tr><th>#</th><th>NISN</th><th>Nama</th><th>Jenis Kelamin</th></tr></thead>
                            <tbody>
                                @foreach($classroom->students as $student)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $student->nisn }}</td>
                                        <td>{{ $student->user->name }}</td>
                                        <td>{{ $student->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-ui.card>

            <x-ui.card title="Jadwal Pelajaran" headerClass="bg-success text-white">
                @if($classroom->schedules->isEmpty())
                    <p class="text-center text-muted">Belum ada jadwal untuk kelas ini</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead><tr><th>Hari</th><th>Mata Pelajaran</th><th>Guru</th><th>Waktu</th></tr></thead>
                            <tbody>
                                @foreach($classroom->schedules->sortBy('day') as $schedule)
                                    <tr>
                                        <td>{{ ucfirst($schedule->day->value ?? $schedule->day) }}</td>
                                        <td>{{ $schedule->subject->name }}</td>
                                        <td>{{ $schedule->teacher->user->name }}</td>
                                        <td>{{ $schedule->time_start ?? $schedule->start_time }} - {{ $schedule->time_finish ?? $schedule->end_time }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-ui.card>
        </div>
    </div>
@endsection