@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <h2 class="text-center mt-3">Mata Pelajaran</h2>

    <div class="text-end mb-3">
        <a href="{{ route('dashboard.subjects.create') }}" class="btn btn-success btn-sm">+ Tambah Mata Pelajaran</a>
    </div>

    @if ($subjects->isEmpty())
        @include('components.empty-data')
    @else
        <div class="table-responsive mt-3">
            <table id="basic-datatables" class="table table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Mata Pelajaran</th>
                        <th class="action">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subjects as $subject)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $subject->code }}</td>
                            <td>{{ $subject->name }}</td>
                            <td>
                                <a href="{{ route('dashboard.subjects.show', $subject->slug) }}"
                                    class="btn btn-info btn-sm my-2 btn-action">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('dashboard.subjects.edit', $subject->slug) }}"
                                    class="btn btn-warning btn-sm my-2 btn-action">
                                    <img src="/img/edit.png" alt="Edit" class="icon">
                                </a>
                                <form action="{{ route('dashboard.subjects.destroy', $subject->slug) }}" method="POST"
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
            {{ $subjects->links() }}
        </div>
    @endif
@endsection