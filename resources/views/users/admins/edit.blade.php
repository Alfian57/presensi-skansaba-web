@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <x-ui.card title="Edit Admin" icon="fas fa-user-shield">
                <form action="{{ route('dashboard.admins.update', $admin->id) }}" method="POST">
                    @method('put')
                    @csrf
                    <input type="hidden" name="password" value="{{ $admin->password }}">
                    <input type="hidden" name="token" value="{{ $admin->remember_token }}">
                    <input type="hidden" name="oldUsername" value="{{ $admin->username }}">
                    <input type="hidden" name="oldEmail" value="{{ $admin->email }}">

                    <x-forms.input name="name" label="Nama Admin" placeholder="Nama lengkap" :value="$admin->name" required />
                    <x-forms.input name="email" label="Email Admin" type="email" placeholder="email@example.com" :value="$admin->email" required />
                    <x-forms.input name="username" label="Username (Tanpa Spasi)" placeholder="username" :value="$admin->username" required />

                    <x-forms.actions :backRoute="route('dashboard.admins.index')" submitLabel="Perbarui Data" />
                </form>
            </x-ui.card>
        </div>
    </div>
@endsection