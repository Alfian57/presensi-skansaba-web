@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <x-ui.page-header 
        title="Siswa" 
        :createRoute="route('dashboard.students.create')" 
        createLabel="+ Tambah Siswa"
        :exportRoute="route('dashboard.students.export')"
        exportLabel="Download Data (Excel)"
    >
        <a class="btn btn-info btn-sm text-white" data-toggle="modal" data-target="#filterModal">
            <i class="fa fa-search"></i> Filter
        </a>
    </x-ui.page-header>

    {{-- Filter Modal --}}
    <x-ui.modal id="filterModal" title="Filter">
        <form action="{{ route('dashboard.students.index') }}" method="GET">
            <x-forms.input name="nisn" label="NISN" placeholder="NISN Siswa" :value="request('nisn')" />
            <x-forms.select name="classroom_id" label="Kelas" :options="$classrooms->pluck('name', 'id')" :value="request('classroom_id')" placeholder="Semua Kelas" />
            <div class="text-end">
                <button type="submit" class="btn btn-primary btn-sm">
                    <img src="/img/search.png" class="icon"> Cari
                </button>
            </div>
        </form>
    </x-ui.modal>

    @if ($students->isEmpty())
        @include('components.empty-data')
    @else
        <div class="table-responsive mt-3">
            <table class="table table-striped datatable-without-search">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Status</th>
                        <th>Image</th>
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
                                    <span class="text-danger">Tidak Ada Foto</span>
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