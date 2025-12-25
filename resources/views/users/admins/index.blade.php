@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <x-ui.page-header 
        title="Admin" 
        :createRoute="route('dashboard.admins.create')" 
        createLabel="+ Tambah Admin"
    />

    @if ($users->isEmpty())
        @include('components.empty-data')
    @else
        <div class="table-responsive mt-3">
            <table id="basic-datatables" class="table table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Status</th>
                        <th class="action">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->username }}</td>
                            <td>
                                <x-ui.badge :type="$user->is_active ? 'active' : 'inactive'">
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </x-ui.badge>
                            </td>
                            <td>
                                <x-tables.actions 
                                    :editRoute="route('dashboard.admins.edit', $user)"
                                    :deleteRoute="route('dashboard.admins.destroy', $user)"
                                    deleteConfirm="Apakah Anda yakin ingin menghapus admin ini?"
                                />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection