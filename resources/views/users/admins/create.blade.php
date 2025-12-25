@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <x-ui.card title="Tambah Admin" icon="fas fa-user-shield" class="mt-3">
        <form action="{{ route('dashboard.admins.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <x-forms.input name="name" label="Nama Admin" placeholder="Nama" required />
                </div>
                <div class="col-md-6">
                    <x-forms.input name="email" label="Email Admin" type="email" placeholder="Email" required />
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <x-forms.input name="username" label="Username Admin (Tanpa Spasi)" placeholder="Username" required />
                </div>
                <div class="col-md-6">
                    <x-forms.input name="password" label="Password" type="password" placeholder="Password" required />
                </div>
            </div>

            <x-forms.input name="password_confirmation" label="Konfirmasi Password" type="password" placeholder="Konfirmasi Password" required />

            <div class="text-end">
                <a href="{{ route('dashboard.admins.index') }}" class="btn btn-danger btn-sm">Kembali</a>
                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
            </div>
        </form>
    </x-ui.card>
@endsection