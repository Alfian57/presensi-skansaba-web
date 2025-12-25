@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <x-ui.card title="Edit Admin" icon="fas fa-user-shield" class="mt-3">
        <form action="{{ route('dashboard.admins.update', $admin->id) }}" method="POST">
            @method('put')
            @csrf
            <input type="hidden" name="password" value="{{ $admin->password }}">
            <input type="hidden" name="token" value="{{ $admin->remember_token }}">
            <input type="hidden" name="oldUsername" value="{{ $admin->username }}">
            <input type="hidden" name="oldEmail" value="{{ $admin->email }}">

            <div class="row">
                <div class="col-md-6">
                    <x-forms.input name="name" label="Nama Admin" placeholder="Nama" :value="$admin->name" required />
                </div>
                <div class="col-md-6">
                    <x-forms.input name="email" label="Email Admin" type="email" placeholder="Email" :value="$admin->email" required />
                </div>
            </div>

            <x-forms.input name="username" label="Username Admin (Tanpa Spasi)" placeholder="Username" :value="$admin->username" required />

            <div class="text-end">
                <a href="{{ route('dashboard.admins.index') }}" class="btn btn-danger btn-sm">Kembali</a>
                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
            </div>
        </form>
    </x-ui.card>
@endsection