@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <x-ui.page-header 
        title="Guru" 
        :createRoute="route('dashboard.teachers.create')" 
        createLabel="+ Tambah Guru"
        :exportRoute="route('dashboard.teachers.export')"
        exportLabel="Export Excel"
    />

    {{-- Inline Filter --}}
    <x-tables.filter-inline :action="route('dashboard.teachers.index')">
        <div class="col-md-4">
            <label class="form-label small mb-1">Cari NIP/Nama</label>
            <input type="text" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="NIP atau Nama Guru">
        </div>
    </x-tables.filter-inline>

    @if ($teachers->isEmpty())
        @include('components.empty-data')
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Status</th>
                        <th>Foto</th>
                        <th class="action">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($teachers as $teacher)
                        <tr>
                            <td>{{ $loop->iteration + ($teachers->currentPage() - 1) * $teachers->perPage() }}</td>
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
                                    <span class="text-muted small">-</span>
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
        
        <div class="d-flex justify-content-center mt-4">
            {{ $teachers->withQueryString()->links() }}
        </div>
    @endif
@endsection