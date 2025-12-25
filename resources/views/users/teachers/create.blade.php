@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <x-ui.card title="Tambah Guru" icon="fas fa-chalkboard-teacher" class="mt-3">
        <form action="{{ route('dashboard.teachers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <x-forms.input name="nip" label="NIP/NIK Guru" placeholder="NIP atau NIK" required />
                </div>
                <div class="col-md-6">
                    <x-forms.input name="name" label="Nama Guru" placeholder="Nama Lengkap" required />
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <x-forms.input name="email" label="Email Guru" type="email" placeholder="email@example.com" required />
                </div>
                <div class="col-md-6">
                    <x-forms.input name="username" label="Username" placeholder="Username (Opsional)" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <x-forms.input name="password" label="Password" type="password" placeholder="Minimal 6 karakter" hint="Password minimal 6 karakter" required />
                </div>
                <div class="col-md-6">
                    <x-forms.input name="date_of_birth" label="Tanggal Lahir" type="date" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <x-forms.select name="gender" label="Jenis Kelamin" :options="['male' => 'Laki-laki', 'female' => 'Perempuan']" placeholder="Pilih Jenis Kelamin" required />
                </div>
                <div class="col-md-6">
                    <x-forms.input name="phone" label="No. Telepon" type="tel" placeholder="08xxxxxxxxxx (Opsional)" />
                </div>
            </div>

            <x-forms.textarea name="address" label="Alamat" placeholder="Alamat (Opsional)" />

            <div class="mb-3">
                <label for="profile_picture" class="form-label">Foto Guru</label>
                <input class="form-control @error('profile_picture') is-invalid @enderror" type="file" name="profile_picture" id="profile_picture" accept="image/*">
                @error('profile_picture')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Format: JPG, PNG, maksimal 2MB (Opsional)</small>
            </div>

            <div class="text-end">
                <a href="{{ route('dashboard.teachers.index') }}" class="btn btn-danger btn-sm">Kembali</a>
                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
            </div>
        </form>
    </x-ui.card>
@endsection