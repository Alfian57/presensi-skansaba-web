@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <x-ui.card title="Tambah Siswa" icon="fas fa-user-plus">
                <form action="{{ route('dashboard.students.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <x-forms.input name="nisn" label="NISN Siswa" placeholder="NISN (10 digit)" required />
                        </div>
                        <div class="col-md-6">
                            <x-forms.input name="nis" label="NIS Siswa" placeholder="NIS (Opsional)" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-forms.input name="name" label="Nama Siswa" placeholder="Nama Lengkap" required />
                        </div>
                        <div class="col-md-6">
                            <x-forms.input name="email" label="Email Siswa" type="email" placeholder="email@example.com" required />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-forms.input name="username" label="Username" placeholder="Username (Opsional)" />
                        </div>
                        <div class="col-md-6">
                            <x-forms.input name="password" label="Password" type="password" placeholder="Minimal 6 karakter" hint="Password minimal 6 karakter" required />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-forms.input name="date_of_birth" label="Tanggal Lahir" type="date" required />
                        </div>
                        <div class="col-md-6">
                            <x-forms.select name="gender" label="Jenis Kelamin" :options="['male' => 'Laki-laki', 'female' => 'Perempuan']" required />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-forms.input name="phone" label="No. Telepon" type="tel" placeholder="08xxxxxxxxxx (Opsional)" />
                        </div>
                        <div class="col-md-6">
                            <x-forms.select name="classroom_id" label="Kelas" :options="$classrooms->pluck('name', 'id')" placeholder="-- Pilih Kelas --" required />
                        </div>
                    </div>

                    <x-forms.textarea name="address" label="Alamat" placeholder="Alamat (Opsional)" />

                    <div class="row">
                        <div class="col-md-6">
                            <x-forms.input name="parent_name" label="Nama Orang Tua/Wali" placeholder="Nama Orang Tua (Opsional)" />
                        </div>
                        <div class="col-md-6">
                            <x-forms.input name="parent_phone" label="No. Telepon Orang Tua" type="tel" placeholder="08xxxxxxxxxx (Opsional)" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-forms.input name="entry_year" label="Tahun Masuk" type="number" placeholder="Contoh: 2024" hint="Tahun masuk (4 digit, opsional)" />
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="profile_pic" class="form-label">Foto Siswa</label>
                                <input class="form-control @error('profile_picture') is-invalid @enderror" name="profile_picture" type="file" id="profile_pic" accept="image/*">
                                @error('profile_picture')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Format: JPG, PNG, maksimal 2MB (Opsional)</small>
                            </div>
                        </div>
                    </div>

                    <x-forms.actions :backRoute="route('dashboard.students.index')" submitLabel="Simpan Data" />
                </form>
            </x-ui.card>
        </div>
    </div>
@endsection