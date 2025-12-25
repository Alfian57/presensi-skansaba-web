@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <h2 class="text-center mt-3">Rekap Presensi per Siswa</h2>

    {{-- Filter Form --}}
    <div class="card mt-4">
        <div class="card-body">
            <form action="{{ route('dashboard.attendances.recap.student') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                           value="{{ $startDate }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                           value="{{ $endDate }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Students Table --}}
    <div class="card mt-4">
        <div class="card-header">
            <h6 class="mb-0">Data Rekap Siswa ({{ $startDate }} s/d {{ $endDate }})</h6>
        </div>
        <div class="card-body">
            @if($students->isEmpty())
                @include('components.empty-data')
            @else
                <div class="table-responsive">
                    <table class="table table-striped datatable">
                        <thead class="table-primary">
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
                                        <a href="{{ route('dashboard.attendances.by-student', $student) }}" 
                                           class="btn btn-sm btn-info text-white">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
