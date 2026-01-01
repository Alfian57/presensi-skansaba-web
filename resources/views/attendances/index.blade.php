@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <x-ui.page-header title="Presensi" />

    {{-- Inline Filter --}}
    <x-tables.filter-inline :action="route('dashboard.attendances.index')">
        <div class="col-md-2">
            <label class="form-label small mb-1">NISN/Nama</label>
            <input type="text" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="Cari...">
        </div>
        <div class="col-md-2">
            <label class="form-label small mb-1">Kelas</label>
            <select class="form-select form-select-sm" name="classroom_id">
                <option value="">Semua</option>
                @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}" @selected(request('classroom_id') == $classroom->id)>{{ $classroom->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small mb-1">Status</label>
            <select class="form-select form-select-sm" name="status">
                <option value="">Semua</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->value }}" @selected(request('status') == $status->value)>{{ ucfirst($status->value) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small mb-1">Dari Tanggal</label>
            <input type="date" class="form-control form-control-sm" name="start_date" value="{{ request('start_date') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label small mb-1">Sampai Tanggal</label>
            <input type="date" class="form-control form-control-sm" name="end_date" value="{{ request('end_date') }}">
        </div>
    </x-tables.filter-inline>

    {{-- Table --}}
    @if ($attendances->isEmpty())
        @include('components.empty-data')
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th class="action">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attendances as $attendance)
                        <tr>
                            <td>{{ $loop->iteration + ($attendances->currentPage() - 1) * $attendances->perPage() }}</td>
                            <td>{{ $attendance->student->nisn }}</td>
                            <td>{{ $attendance->student->user->name ?? '-' }}</td>
                            <td>{{ $attendance->student->classroom->name ?? '-' }}</td>
                            <td>{{ $attendance->date?->format('d/m/Y') ?? '-' }}</td>
                            <td>
                                @php
                                    $statusValue = $attendance->status->value ?? $attendance->status;
                                    $statusConfig = [
                                        'present' => ['label' => 'Hadir', 'class' => 'success'],
                                        'late' => ['label' => 'Terlambat', 'class' => 'warning'],
                                        'sick' => ['label' => 'Sakit', 'class' => 'info'],
                                        'permission' => ['label' => 'Izin', 'class' => 'primary'],
                                        'absent' => ['label' => 'Alpha', 'class' => 'danger'],
                                    ];
                                    $config = $statusConfig[$statusValue] ?? ['label' => ucfirst($statusValue), 'class' => 'secondary'];
                                @endphp
                                <span class="badge bg-{{ $config['class'] }}">{{ $config['label'] }}</span>
                            </td>
                            <td>{{ $attendance->check_in_time ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i') : '-' }}</td>
                            <td>{{ $attendance->check_out_time ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i') : '-' }}</td>
                            <td>
                                <a href="{{ route('dashboard.attendances.edit', $attendance->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $attendances->withQueryString()->links() }}
        </div>
    @endif
@endsection