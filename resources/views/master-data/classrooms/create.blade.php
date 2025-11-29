@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <h2 class="text-center mt-3">Tambah Kelas</h2>

    <form action="{{ route('dashboard.classrooms.store') }}" method="POST">
        @csrf

        <div class="row">
            <div class="mb-3">
                <label for="grade_level" class="form-label @error('grade_level') is-invalid @enderror">Tingkat</label>
                <select name="grade_level" id="grade_level" class="form-select @error('grade_level') is-invalid @enderror">
                    <option value="">-- Pilih --</option>
                    <option value="10" {{ old('grade_level') == '10' ? 'selected' : '' }}>10</option>
                    <option value="11" {{ old('grade_level') == '11' ? 'selected' : '' }}>11</option>
                    <option value="12" {{ old('grade_level') == '12' ? 'selected' : '' }}>12</option>
                </select>
                @error('grade_level')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="major" class="form-label @error('major') is-invalid @enderror">Jurusan / Program</label>
                <input type="text" name="major" id="major" class="form-control @error('major') is-invalid @enderror"
                    value="{{ old('major') }}" placeholder="Masukkan jurusan atau program">
                @error('major')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="class_number" class="form-label @error('class_number') is-invalid @enderror">Nomor Kelas</label>
                <input type="number" class="form-control @error('class_number') is-invalid @enderror" name="class_number"
                    id="class_number" placeholder="1" value="{{ old('class_number') }}" min="1">
                @error('class_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="capacity" class="form-label @error('capacity') is-invalid @enderror">Kapasitas</label>
                <input type="number" class="form-control @error('capacity') is-invalid @enderror" name="capacity"
                    id="capacity" value="{{ old('capacity', 36) }}" min="1">
                @error('capacity')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="ms-3 mb-3">
                <div class="form-check">
                    <input class="form-check-input @error('is_active') is-invalid @enderror" type="checkbox" id="is_active"
                        name="is_active" value="1" @checked(old('is_active', '1'))
                        style="position: relative; left: 0; opacity: 1;">
                    <label class="form-check-label" for="is_active">Kelas Siap Digunakan ?</label>
                </div>
                @error('is_active')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="text-end">
            <a href="{{ route('dashboard.classrooms.index') }}" class="btn btn-danger btn-sm mt-3">Kembali</a>
            <button type="submit" class="btn btn-primary btn-sm mt-3">Submit</button>
        </div>
    </form>
@endsection