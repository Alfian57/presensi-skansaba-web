@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <x-ui.page-header 
        title="Siswa" 
        :createRoute="route('dashboard.students.create')" 
        createLabel="+ Tambah Siswa"
        :exportRoute="route('dashboard.students.export')"
        exportLabel="Export Excel"
    />

    {{-- Inline Filter --}}
    <x-tables.filter-inline :action="route('dashboard.students.index')">
        <div class="col-md-3">
            <label class="form-label small mb-1">NISN</label>
            <input type="text" class="form-control form-control-sm" name="nisn" value="{{ request('nisn') }}" placeholder="NISN Siswa">
        </div>
        <div class="col-md-3">
            <label class="form-label small mb-1">Kelas</label>
            <select class="form-select form-select-sm" name="classroom_id">
                <option value="">Semua Kelas</option>
                @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}" @selected(request('classroom_id') == $classroom->id)>{{ $classroom->name }}</option>
                @endforeach
            </select>
        </div>
    </x-tables.filter-inline>

    @if ($students->isEmpty())
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
                        <th>Status</th>
                        <th>Foto</th>
                        <th class="action">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $student->nisn }}</td>
                            <td>{{ $student->user->name ?? $student->name }}</td>
                            <td>{{ $student->classroom->name ?? '-' }}</td>
                            <td>
                                <x-ui.badge :type="$student->user->is_active ? 'active' : 'inactive'">
                                    {{ $student->user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </x-ui.badge>
                            </td>
                            <td>
                                @if ($student->user->profile_picture)
                                    <div class="profile-pic-box rounded-circle">
                                        <a href="{{ asset('storage/' . $student->user->profile_picture) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $student->user->profile_picture) }}" alt="Profile" class="img-fluid">
                                        </a>
                                    </div>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                <x-tables.actions 
                                    :showRoute="route('dashboard.students.show', $student)"
                                    :editRoute="route('dashboard.students.edit', $student)"
                                    :deleteRoute="route('dashboard.students.destroy', $student)"
                                    deleteConfirm="Apakah Anda yakin ingin menghapus siswa ini?"
                                />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection