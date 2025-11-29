@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <div class="row mt-3">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Foto & Informasi Dasar</h5>
                </div>
                <div class="card-body text-center">
                    @if ($teacher->user->profile_picture)
                        <img src="{{ asset('storage/' . $teacher->user->profile_picture) }}" alt="Profil" 
                            class="img-fluid rounded-circle mb-3" style="max-width: 200px; height: 200px; object-fit: cover;">
                    @else
                        <i class="fas fa-user-circle fa-10x text-secondary mb-3"></i>
                    @endif
                    <h4 class="mb-1">{{ $teacher->user->name }}</h4>
                    <p class="text-muted mb-2">NIP: {{ $teacher->nip }}</p>
                    <span class="badge bg-{{ $teacher->user->is_active ? 'success' : 'danger' }} mb-3">
                        {{ $teacher->user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                    
                    <div class="text-start mt-4">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <th width="40%">Email</th>
                                <td>: {{ $teacher->user->email }}</td>
                            </tr>
                            <tr>
                                <th>Username</th>
                                <td>: {{ $teacher->user->username ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>No. Telepon</th>
                                <td>: {{ $teacher->phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <td>: {{ $teacher->gender == App\Enums\Gender::MALE->value ? "Laki-laki" : "Perempuan" }}</td>
                            </tr>
                        </table>
                    </div>

                    <div class="text-end mt-3">
                        <a href="{{ route('dashboard.teachers.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('dashboard.teachers.edit', $teacher->employee_number) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Jadwal Mengajar</h5>
                </div>
                <div class="card-body">
                    @if($teacher->schedules->isEmpty())
                        <p class="text-center text-muted">Belum ada jadwal mengajar</p>
                    @else
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center py-2">
                                        <h3 class="mb-0">{{ $teacher->schedules->count() }}</h3>
                                        <small>Total Jadwal</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center py-2">
                                        <h3 class="mb-0">{{ $teacher->schedules->pluck('subject_id')->unique()->count() }}</h3>
                                        <small>Mata Pelajaran</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center py-2">
                                        <h3 class="mb-0">{{ $teacher->schedules->pluck('classroom_id')->unique()->count() }}</h3>
                                        <small>Kelas</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Hari</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Kelas</th>
                                        <th>Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($teacher->schedules->sortBy('day') as $schedule)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ ucfirst($schedule->day) }}</td>
                                            <td>{{ $schedule->subject->name ?? '-' }}</td>
                                            <td>{{ $schedule->classroom->name ?? '-' }}</td>
                                            <td>{{ $schedule->time_start ?? '-' }} - {{ $schedule->time_finish ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            @php
                $homeroom = $teacher->homeroomTeacher;
            @endphp
            @if($homeroom)
                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0">Wali Kelas</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-info-circle"></i> Guru ini adalah wali kelas <strong>{{ $homeroom->classroom->name ?? '-' }}</strong>
                        </div>

                        @if($homeroom->classroom && $homeroom->classroom->students->count() > 0)
                            <h6>Daftar Siswa ({{ $homeroom->classroom->students->count() }} siswa)</h6>
                            <div class="table-responsive">
                                <table class="table table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>NISN</th>
                                            <th>Nama</th>
                                            <th>Jenis Kelamin</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($homeroom->classroom->students->take(10) as $student)
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
                            @if($homeroom->classroom->students->count() > 10)
                                <p class="text-muted text-center">Menampilkan 10 dari {{ $homeroom->classroom->students->count() }} siswa</p>
                            @endif
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection