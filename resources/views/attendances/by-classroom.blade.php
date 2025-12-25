@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <h2 class="text-center mt-3">Presensi Kelas {{ $classroom->name }}</h2>

    <div class="card mt-4">
        <div class="card-body">
            @if($attendances->isEmpty())
                @include('components.empty-data')
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-primary">
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>NISN</th>
                                <th>Nama</th>
                                <th>Status</th>
                                <th>Jam Masuk</th>
                                <th>Jam Pulang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $attendance)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}</td>
                                    <td>{{ $attendance->student->nisn ?? '-' }}</td>
                                    <td>{{ $attendance->student->user->name ?? '-' }}</td>
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $attendances->links() }}
            @endif
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('dashboard.attendances.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
@endsection
