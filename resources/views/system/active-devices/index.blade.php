@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <x-ui.page-header title="Akun Aktif (Device Terdaftar)" />

    {{-- Inline Filter --}}
    <x-tables.filter-inline :action="route('dashboard.devices.index')">
        <div class="col-md-4">
            <label class="form-label small mb-1">NISN / Nama</label>
            <input type="text" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="NISN atau Nama Siswa">
        </div>
    </x-tables.filter-inline>

    @if ($students->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-mobile-alt fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">Belum Ada Perangkat Terdaftar</h5>
            <p class="text-muted">Tidak ada siswa dengan perangkat aktif saat ini.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Terdaftar Pada</th>
                        <th class="action">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $student->nisn }}</td>
                            <td>{{ $student->user->name ?? '-' }}</td>
                            <td>{{ $student->classroom->name ?? '-' }}</td>
                            <td>
                                @if($student->device_registered_at)
                                    {{ \Carbon\Carbon::parse($student->device_registered_at)->format('d/m/Y H:i') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('dashboard.devices.unregister', $student) }}" method="POST" class="d-inline-block">
                                    @method('delete')
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus device ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection