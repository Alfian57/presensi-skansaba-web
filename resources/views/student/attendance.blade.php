@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">Riwayat Presensi</h2>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">{{ $student->user->name }} - {{ $student->classroom->name ?? 'Belum ada kelas' }}</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Jam Masuk</th>
                                <th>Jam Pulang</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $attendance)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}</td>
                                    <td>
                                        @php $status = $attendance->status->value ?? $attendance->status; @endphp
                                        @switch($status)
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
                                    </td>
                                    <td>{{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : '-' }}</td>
                                    <td>{{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : '-' }}</td>
                                    <td>{{ $attendance->notes ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada data presensi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $attendances->links() }}
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('dashboard.student.home') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
@endsection
