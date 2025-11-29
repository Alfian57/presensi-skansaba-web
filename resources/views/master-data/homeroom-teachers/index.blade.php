@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <h2 class="text-center mt-3">Wali Kelas</h2>

    <div class="text-end mb-3">
        <a href="{{ route('dashboard.homeroom-teachers.create') }}" class="btn btn-success btn-sm">+ Tambah Wali Kelas</a>
    </div>

    @if ($homeroomTeachers->isEmpty())
        @include('components.empty-data')
    @else
        <div class="table-responsive mt-3">
            <table id="basic-datatables" class="table table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Nama Guru</th>
                        <th>Kelas</th>
                        <th class="action">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($homeroomTeachers as $homeroomTeacher)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $homeroomTeacher->teacher->name }}</td>
                            <td>{{ $homeroomTeacher->classroom->name }}</td>
                            <td>
                                <a href="{{ route('dashboard.homeroom-teachers.edit', $homeroomTeacher->id) }}"
                                    class="btn btn-warning btn-sm my-2 btn-action">
                                    <img src="/img/edit.png" alt="Edit" class="icon">
                                </a>
                                <form action="{{ route('dashboard.homeroom-teachers.destroy', $homeroomTeacher->id) }}"
                                    method="POST" class="d-inline-block">
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
    @endif
@endsection