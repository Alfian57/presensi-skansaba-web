@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <x-ui.page-header 
        title="Mata Pelajaran" 
        :createRoute="route('dashboard.subjects.create')" 
        createLabel="+ Tambah Mata Pelajaran"
    />

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
                                <x-tables.actions 
                                    :showRoute="route('dashboard.subjects.show', $subject)"
                                    :editRoute="route('dashboard.subjects.edit', $subject)"
                                    :deleteRoute="route('dashboard.subjects.destroy', $subject)"
                                    deleteConfirm="Apakah Anda yakin ingin menghapus mata pelajaran ini?"
                                />
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