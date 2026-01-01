@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <x-ui.card title="Ubah Data Siswa" icon="fas fa-user-edit">
                <form action="{{ route('dashboard.students.update', $student->nisn) }}" method="POST" enctype="multipart/form-data">
                    @method('put')
                    @csrf
                    <input type="hidden" name="old_profile_pic" value="{{ $student->profile_pic }}">
                    <input type="hidden" name="old_nisn" value="{{ $student->nisn }}">
                    <input type="hidden" name="old_nis" value="{{ $student->nis }}">

                    <div class="row">
                        <div class="col-md-6">
                            <x-forms.input name="nisn" label="NISN Siswa" placeholder="NISN" :value="$student->nisn" required />
                        </div>
                        <div class="col-md-6">
                            <x-forms.input name="nis" label="NIS Siswa" placeholder="NIS" :value="$student->nis" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-forms.input name="name" label="Nama Siswa" placeholder="Nama Lengkap" :value="$student->user->name ?? $student->name" required />
                        </div>
                        <div class="col-md-6">
                            <x-forms.input name="email" label="Email Siswa" type="email" placeholder="email@example.com" :value="$student->user->email" required />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-forms.input name="username" label="Username" placeholder="Username (Opsional)" :value="$student->user->username" />
                        </div>
                        <div class="col-md-6">
                            <x-forms.input name="password" label="Password Baru" type="password" placeholder="Kosongkan jika tidak ingin mengubah" hint="Kosongkan jika tidak ingin mengubah." />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-forms.input name="date_of_birth" label="Tanggal Lahir" type="date" :value="$student->date_of_birth?->format('Y-m-d')" required />
                        </div>
                        <div class="col-md-6">
                            <x-forms.select name="gender" label="Jenis Kelamin" :options="['male' => 'Laki-laki', 'female' => 'Perempuan']" :value="$student->gender" required />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-forms.input name="phone" label="No. Telepon" type="tel" placeholder="08xxxxxxxxxx (Opsional)" :value="$student->phone" />
                        </div>
                        <div class="col-md-6">
                            <x-forms.select name="classroom_id" label="Kelas" :options="$classrooms->pluck('name', 'id')" :value="$student->classroom_id" placeholder="-- Pilih Kelas --" required />
                        </div>
                    </div>

                    <x-forms.textarea name="address" label="Alamat" placeholder="Alamat (Opsional)" :value="$student->address" />

                    <div class="row">
                        <div class="col-md-6">
                            <x-forms.input name="parent_name" label="Nama Orang Tua/Wali" placeholder="Nama Orang Tua (Opsional)" :value="$student->parent_name" />
                        </div>
                        <div class="col-md-6">
                            <x-forms.input name="parent_phone" label="No. Telepon Orang Tua" type="tel" placeholder="08xxxxxxxxxx (Opsional)" :value="$student->parent_phone" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-forms.input name="entry_year" label="Tahun Masuk" type="number" placeholder="Contoh: 2024" :value="$student->entry_year" hint="Tahun masuk (4 digit, opsional)" />
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="profile_pic" class="form-label">Foto Siswa</label>
                                @if ($student->user->profile_picture)
                                    <img src="{{ asset('storage/' . $student->user->profile_picture) }}" alt="Profile" class="d-block mb-2 rounded" style="max-width: 150px;">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" name="delete_profile_picture" type="checkbox" value="1" id="deleteImage">
                                        <label class="form-check-label" for="deleteImage">Hapus foto profil</label>
                                    </div>
                                @else
                                    <p class="text-muted small mb-2">Belum ada foto profil</p>
                                @endif
                                <input class="form-control @error('profile_picture') is-invalid @enderror" name="profile_picture" type="file" id="profile_pic" accept="image/*">
                                @error('profile_picture')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Format: JPG, PNG, maksimal 2MB</small>
                            </div>
                        </div>
                    </div>

                    <x-forms.actions :backRoute="route('dashboard.students.index')" submitLabel="Perbarui Data" />
                </form>
            </x-ui.card>
        </div>
    </div>
@endsection