@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <x-ui.page-header title="Rekap Presensi per Siswa" />

    {{-- Inline Filter --}}
    <x-tables.filter-inline :action="route('dashboard.attendances.recap.student')">
        <div class="col-md-3">
            <label class="form-label small mb-1">Tanggal Mulai</label>
            <input type="date" class="form-control form-control-sm" name="start_date" value="{{ $startDate }}">
        </div>
        <div class="col-md-3">
            <label class="form-label small mb-1">Tanggal Akhir</label>
            <input type="date" class="form-control form-control-sm" name="end_date" value="{{ $endDate }}">
        </div>
    </x-tables.filter-inline>

    {{-- Students Table --}}
    <x-ui.card title="Data Rekap Siswa ({{ $startDate }} s/d {{ $endDate }})" headerClass="bg-primary text-white">
        @if($students->isEmpty())
            @include('components.empty-data')
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover datatable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>NISN</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $student->nisn }}</td>
                                <td>{{ $student->user->name ?? $student->name ?? '-' }}</td>
                                <td>{{ $student->classroom->name ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('dashboard.attendances.by-student', $student) }}" class="btn btn-sm btn-info text-white">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-ui.card>
@endsection
