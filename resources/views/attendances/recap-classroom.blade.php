@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <h2 class="text-center mt-3">Rekap Kelas</h2>

    {{-- <div class="text-end mb-3">
        <a class="btn btn-primary btn-sm" href="{{ route('dashboard.attendances.export') }}">Download Data (Excel)</a>
    </div> --}}

    {{-- Table --}}
    @if ($classrooms->isEmpty())
        @include('components.empty-data')
    @else
        <div class="table-responsive mt-3">
            <table id="basic-datatables" class="table table-striped">
                <thead class="table-primary table-striped">
                    <tr>
                        <th>#</th>
                        <th>Kelas</th>
                        <th>Jumlah Siswa</th>
                        <th class="attendance-action">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($classrooms as $classroom)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $classroom->name }}</td>
                            <td>{{ $classroom->students->count() }} siswa</td>

                            <td>
                                <a href="{{ route('dashboard.attendances.by-classroom', $classroom) }}"
                                    class="btn btn-primary btn-sm my-2 btn-action">
                                    <img src="/img/eye.png" alt="Show" class="icon">
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection