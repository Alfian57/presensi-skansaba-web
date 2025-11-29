@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <h2 class="text-center mt-3">Ubah Data Mata Pelajaran</h2>
    <form action="{{ route('dashboard.subjects.update', $subject->slug) }}" method="POST">
        @method('put')
        @csrf
        <div class="mb-3 mt-3">
            <label for="code" class="form-label @error('code') is-invalid @enderror">Kode Mata Pelajaran</label>
            <input type="text" class="form-control" name="code" id="code" placeholder="Kode"
                value="{{ old('code', $subject->code) }}" required autofocus>
            @error('code')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3 mt-3">
            <label for="name" class="form-label @error('name') is-invalid @enderror">Nama Mata Pelajaran</label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Mata Pelajaran"
                value="{{ old('name', $subject->name) }}" required>
            @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3 mt-3">
            <label for="description" class="form-label @error('description') is-invalid @enderror">Deskripsi
                (Opsional)</label>
            <textarea class="form-control" name="description" id="description" placeholder="Deskripsi"
                rows="3">{{ old('description', $subject->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="text-end">
            <a href="{{ route('dashboard.subjects.index') }}" class="btn btn-danger btn-sm mt-3">Kembali</a>
            <button type="submit" class="btn btn-primary btn-sm mt-3">Submit</button>
        </div>
    </form>
@endsection