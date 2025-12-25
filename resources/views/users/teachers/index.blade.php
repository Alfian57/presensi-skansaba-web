@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <x-ui.page-header 
        title="Guru" 
        :createRoute="route('dashboard.teachers.create')" 
        createLabel="+ Tambah Guru"
        :exportRoute="route('dashboard.teachers.export')"
        exportLabel="Download Data (Excel)"
    >
        <a class="btn btn-info btn-sm text-white" data-toggle="modal" data-target="#filterModal">
            <i class="fa fa-search"></i> Filter
        </a>
    </x-ui.page-header>

    {{-- Filter Modal --}}
    <x-ui.modal id="filterModal" title="Filter">
        <form action="{{ route('dashboard.teachers.index') }}" method="GET">
            <x-forms.input name="search" label="Cari NIP/Nama" placeholder="NIP atau Nama Guru" :value="request('search')" />
            <div class="text-end">
                <button type="submit" class="btn btn-primary btn-sm">
                    <img src="/img/search.png" class="icon"> Cari
                </button>
            </div>
        </form>
    </x-ui.modal>

    @if ($teachers->isEmpty())
        @include('components.empty-data')
    @else
        <div class="table-responsive mt-3">
            <table class="table table-striped datatable-without-search">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Status</th>
                        <th>Image</th>
                        <th class="action">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($teachers as $teacher)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $teacher->nip }}</td>
                            <td>{{ $teacher->user->name ?? $teacher->name }}</td>
                            <td>
                                <x-ui.badge :type="$teacher->user->is_active ? 'active' : 'inactive'">
                                    {{ $teacher->user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </x-ui.badge>
                            </td>
                            <td>
                                @if ($teacher->user->profile_picture)
                                    <div class="profile-pic-box rounded-circle">
                                        <a href="{{ asset('storage/' . $teacher->user->profile_picture) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $teacher->user->profile_picture) }}" alt="Profile" class="img-fluid">
                                        </a>
                                    </div>
                                @else
                                    <span class="text-danger">Tidak Ada Foto</span>
                                @endif
                            </td>
                            <td>
                                <x-tables.actions 
                                    :showRoute="route('dashboard.teachers.show', $teacher)"
                                    :editRoute="route('dashboard.teachers.edit', $teacher)"
                                    :deleteRoute="route('dashboard.teachers.destroy', $teacher)"
                                    deleteConfirm="Apakah Anda yakin ingin menghapus guru ini?"
                                />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{ $teachers->links() }}
    @endif
@endsection