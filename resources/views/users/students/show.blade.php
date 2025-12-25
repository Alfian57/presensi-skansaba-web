@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <div class="row mt-3">
        <div class="col-md-4">
            <x-ui.card title="Foto & Informasi Dasar" headerClass="bg-primary text-white">
                <div class="text-center">
                    @if ($student->user->profile_picture)
                        <img src="{{ asset('storage/' . $student->user->profile_picture) }}" alt="Profil"
                            class="img-fluid rounded-circle mb-3" style="max-width: 200px; height: 200px; object-fit: cover;">
                    @else
                        <i class="fas fa-user-circle fa-10x text-secondary mb-3"></i>
                    @endif
                    <h4 class="mb-1">{{ $student->user->name }}</h4>
                    <p class="text-muted mb-2">{{ $student->nisn }}</p>
                    <x-ui.badge :type="$student->user->is_active ? 'active' : 'inactive'" class="mb-3">
                        {{ $student->user->is_active ? 'Aktif' : 'Tidak Aktif' }}
                    </x-ui.badge>

                    <div class="text-start mt-4">
                        <table class="table table-borderless table-sm">
                            <tr><th width="40%">Email</th><td>: {{ $student->user->email }}</td></tr>
                            <tr><th>Username</th><td>: {{ $student->user->username ?? '-' }}</td></tr>
                            <tr><th>Kelas</th><td>: {{ $student->classroom->name ?? '-' }}</td></tr>
                        </table>
                    </div>

                    <div class="text-end mt-3">
                        <a href="{{ route('dashboard.students.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <a href="{{ route('dashboard.students.edit', $student) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
            </x-ui.card>
        </div>

        <div class="col-md-8">
            <x-ui.card title="Data Pribadi" headerClass="bg-info text-white" class="mb-3">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr><th width="45%">NISN</th><td>: {{ $student->nisn }}</td></tr>
                            <tr><th>NIS</th><td>: {{ $student->nis ?? '-' }}</td></tr>
                            <tr><th>Jenis Kelamin</th><td>: {{ $student->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                            <tr><th>Tanggal Lahir</th><td>: {{ $student->date_of_birth?->format('d F Y') ?? '-' }}</td></tr>
                            <tr><th>Umur</th><td>: {{ $student->age ?? '-' }} tahun</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr><th width="45%">No. Telepon</th><td>: {{ $student->phone ?? '-' }}</td></tr>
                            <tr><th>Alamat</th><td>: {{ $student->address ?? '-' }}</td></tr>
                            <tr><th>Tahun Masuk</th><td>: {{ $student->entry_year ?? '-' }}</td></tr>
                            <tr><th>Nama Orang Tua</th><td>: {{ $student->parent_name ?? '-' }}</td></tr>
                            <tr><th>Telp. Orang Tua</th><td>: {{ $student->parent_phone ?? '-' }}</td></tr>
                        </table>
                    </div>
                </div>
            </x-ui.card>

            <x-ui.card title="Statistik Kehadiran" headerClass="bg-success text-white">
                @if($student->attendances->isEmpty())
                    <p class="text-center text-muted">Belum ada data kehadiran</p>
                @else
                    @php
                        $totalAttendances = $student->attendances->count();
                        $present = $student->attendances->where('status', 'present')->count();
                        $late = $student->attendances->where('status', 'late')->count();
                        $sick = $student->attendances->where('status', 'sick')->count();
                        $permit = $student->attendances->where('status', 'permit')->count();
                        $absent = $student->attendances->where('status', 'absent')->count();
                    @endphp

                    <div class="row text-center mb-3">
                        <div class="col-md-2">
                            <div class="card bg-success text-white"><div class="card-body py-2"><h4 class="mb-0">{{ $present }}</h4><small>Hadir</small></div></div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-warning text-white"><div class="card-body py-2"><h4 class="mb-0">{{ $late }}</h4><small>Terlambat</small></div></div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-info text-white"><div class="card-body py-2"><h4 class="mb-0">{{ $sick }}</h4><small>Sakit</small></div></div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-primary text-white"><div class="card-body py-2"><h4 class="mb-0">{{ $permit }}</h4><small>Izin</small></div></div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-danger text-white"><div class="card-body py-2"><h4 class="mb-0">{{ $absent }}</h4><small>Alpa</small></div></div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-dark text-white"><div class="card-body py-2"><h4 class="mb-0">{{ $totalAttendances }}</h4><small>Total</small></div></div>
                        </div>
                    </div>

                    <h6 class="mt-4">Riwayat Kehadiran Terakhir</h6>
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr><th>Tanggal</th><th>Status</th><th>Waktu Masuk</th><th>Keterangan</th></tr>
                            </thead>
                            <tbody>
                                @foreach($student->attendances->sortByDesc('date')->take(10) as $attendance)
                                    <tr>
                                        <td>{{ $attendance->date->format('d/m/Y') }}</td>
                                        <td><x-ui.badge :type="$attendance->status">{{ $attendance->status }}</x-ui.badge></td>
                                        <td>{{ $attendance->check_in_time ?? '-' }}</td>
                                        <td>{{ $attendance->notes ?? '-' }}</td>
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