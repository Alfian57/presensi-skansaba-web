@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <x-ui.card title="Ubah Data Guru" icon="fas fa-user-edit" class="mt-3">
        <form action="{{ route('dashboard.teachers.update', $teacher->nip) }}" method="POST" enctype="multipart/form-data">
            @method('put')
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <x-forms.input name="nip" label="NIP/NIK Guru" placeholder="NIP atau NIK" :value="$teacher->nip" required />
                </div>
                <div class="col-md-6">
                    <x-forms.input name="name" label="Nama Guru" placeholder="Nama Lengkap" :value="$teacher->user->name" required />
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <x-forms.input name="email" label="Email Guru" type="email" placeholder="email@example.com" :value="$teacher->user->email" required />
                </div>
                <div class="col-md-6">
                    <x-forms.input name="username" label="Username" placeholder="Username (Opsional)" :value="$teacher->user->username" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <x-forms.input name="password" label="Password Baru" type="password" placeholder="Kosongkan jika tidak ingin mengubah" hint="Password minimal 6 karakter. Kosongkan jika tidak ingin mengubah." />
                </div>
                <div class="col-md-6">
                    <x-forms.input name="date_of_birth" label="Tanggal Lahir" type="date" :value="$teacher->date_of_birth?->format('Y-m-d')" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    @php
                        $genderValue = $teacher->gender instanceof \App\Enums\Gender ? $teacher->gender->value : $teacher->gender;
                    @endphp
                    <x-forms.select name="gender" label="Jenis Kelamin" :options="['male' => 'Laki-laki', 'female' => 'Perempuan']" :value="$genderValue" placeholder="Pilih Jenis Kelamin" required />
                </div>
                <div class="col-md-6">
                    <x-forms.input name="phone" label="No. Telepon" type="tel" placeholder="08xxxxxxxxxx (Opsional)" :value="$teacher->phone" />
                </div>
            </div>

            <x-forms.textarea name="address" label="Alamat" placeholder="Alamat (Opsional)" :value="$teacher->address" />

            <div class="mb-3">
                <label for="profile_picture" class="form-label">Foto Guru</label>
                @if ($teacher->user->profile_picture)
                    <img src="{{ asset('storage/' . $teacher->user->profile_picture) }}" alt="Profile" class="preview d-block mb-2" style="max-width: 200px;">
                    <div class="form-check mb-2">
                        <input class="form-check-input" name="delete_profile_picture" type="checkbox" value="1" id="deleteImage">
                        <label class="form-check-label" for="deleteImage">Hapus foto profil</label>
                    </div>
                @else
                    <p class="text-muted">Guru ini belum memiliki foto profil</p>
                @endif
                <input class="form-control @error('profile_picture') is-invalid @enderror" name="profile_picture" type="file" id="profile_picture" accept="image/*">
                @error('profile_picture')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Format: JPG, PNG, maksimal 2MB</small>
            </div>

            <div class="text-end">
                <a href="{{ route('dashboard.teachers.index') }}" class="btn btn-danger btn-sm">Kembali</a>
                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
            </div>
        </form>
    </x-ui.card>
@endsection