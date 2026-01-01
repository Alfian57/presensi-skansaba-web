@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <x-ui.card title="Tambah Admin" icon="fas fa-user-shield">
                <form action="{{ route('dashboard.admins.store') }}" method="POST">
                    @csrf

                    <x-forms.input name="name" label="Nama Admin" placeholder="Nama lengkap" required />
                    <x-forms.input name="email" label="Email Admin" type="email" placeholder="email@example.com" required />
                    <x-forms.input name="username" label="Username (Tanpa Spasi)" placeholder="username" required />
                    <x-forms.input name="password" label="Password" type="password" placeholder="Minimal 6 karakter" required />
                    <x-forms.input name="password_confirmation" label="Konfirmasi Password" type="password" placeholder="Konfirmasi password" required />

                    <x-forms.actions :backRoute="route('dashboard.admins.index')" submitLabel="Simpan Data" />
                </form>
            </x-ui.card>
        </div>
    </div>
@endsection