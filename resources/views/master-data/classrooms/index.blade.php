@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <x-ui.page-header 
        title="Kelas" 
        :createRoute="route('dashboard.classrooms.create')" 
        createLabel="+ Tambah Kelas"
    />

    @if ($classrooms->isEmpty())
        @include('components.empty-data')
    @else
        <div class="table-responsive mt-3">
            <table id="basic-datatables" class="table table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Kelas</th>
                        <th>Kapasitas</th>
                        <th>Jumlah Siswa</th>
                        <th>Status</th>
                        <th class="action">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($classrooms as $classroom)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $classroom->name }}</td>
                            <td>{{ $classroom->capacity ?? $classroom->class_number }} siswa</td>
                            <td>{{ $classroom->students_count }} siswa</td>
                            <td>
                                <x-ui.badge :type="$classroom->is_active ? 'active' : 'inactive'">
                                    {{ $classroom->is_active ? 'Aktif' : 'Nonaktif' }}
                                </x-ui.badge>
                            </td>
                            <td>
                                <x-tables.actions 
                                    :showRoute="route('dashboard.classrooms.show', $classroom)"
                                    :editRoute="route('dashboard.classrooms.edit', $classroom)"
                                    :deleteRoute="route('dashboard.classrooms.destroy', $classroom)"
                                    deleteConfirm="Apakah Anda yakin ingin menghapus kelas ini?"
                                />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $classrooms->links() }}
        </div>
    @endif
@endsection