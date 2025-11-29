@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <h2 class="text-center mt-3">Kelas</h2>

    <div class="text-end mb-3">
        <a href="{{ route('dashboard.classrooms.create') }}" class="btn btn-success btn-sm">+ Tambah Kelas</a>
    </div>

    @if ($classrooms->isEmpty())
        @include('components.empty-data')
    @else
        <div class="table-responsive mt-3">
            <table id="basic-datatables" class="table table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Kelas</th>
                        <th>Kapasitas Kelas</th>
                        <th>Jumlah Siswa</th>
                        <th>Siap Digunakan ?</th>
                        <th class="action">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($classrooms as $classroom)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $classroom->name }}</td>
                            <td>{{ $classroom->students_count }} siswa</td>
                            <td>{{ $classroom->class_number }} siswa</td>
                            <td>
                                @if ($classroom->is_active)
                                    <span class="badge bg-success">Ya</span>
                                @else
                                    <span class="badge bg-danger">Tidak</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('dashboard.classrooms.show', $classroom->slug) }}"
                                    class="btn btn-info btn-sm my-2 btn-action">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('dashboard.classrooms.edit', $classroom->slug) }}"
                                    class="btn btn-warning btn-sm my-2 btn-action">
                                    <img src="/img/edit.png" alt="Edit" class="icon">
                                </a>
                                <form action="{{ route('dashboard.classrooms.destroy', $classroom->slug) }}" method="POST"
                                    class="d-inline-block">
                                    @method('delete')
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm my-2 btn-action btn-delete">
                                        <img src="/img/delete.png" alt="Delete" class="icon">
                                    </button>
                                </form>
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