@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <x-ui.card title="Ubah Data Kelas" icon="fas fa-school" class="mt-3">
        <form action="{{ route('dashboard.classrooms.update', $classroom->slug) }}" method="POST">
            @method('put')
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <x-forms.select name="grade_level" label="Tingkat" :options="['10' => '10', '11' => '11', '12' => '12']" :value="$classroom->grade_level" required />
                </div>
                <div class="col-md-6">
                    <x-forms.input name="major" label="Jurusan / Program" placeholder="Masukkan jurusan atau program" :value="$classroom->major" />
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <x-forms.input name="class_number" label="Nomor Kelas" type="number" placeholder="1" :value="$classroom->class_number" />
                </div>
                <div class="col-md-6">
                    <x-forms.input name="capacity" label="Kapasitas" type="number" :value="$classroom->capacity ?? 36" />
                </div>
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $classroom->is_active))>
                    <label class="form-check-label" for="is_active">Kelas Siap Digunakan?</label>
                </div>
                @error('is_active')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="text-end">
                <a href="{{ route('dashboard.classrooms.index') }}" class="btn btn-danger btn-sm">Kembali</a>
                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
            </div>
        </form>
    </x-ui.card>
@endsection