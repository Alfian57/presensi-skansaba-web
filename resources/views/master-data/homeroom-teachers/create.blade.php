@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <h2 class="text-center mt-3">Tambah Wali Kelas</h2>
    <form action="{{ route('dashboard.homeroom-teachers.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="teacher_id" class="form-label @error('teacher_id') is-invalid @enderror">Nama Guru</label>
            <select class="form-select" name="teacher_id" id="teacher_id" required>
                @foreach ($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                @endforeach
            </select>
            @if ($teachers->isEmpty())
                <p class="text-danger">Tidak Ada Guru Yang Tersedia</p>
            @endif
            @error('teacher_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="classroom_id" class="form-label @error('classroom_id') is-invalid @enderror">Kelas</label>
            <select class="form-select" name="classroom_id" id="classroom_id" required>
                @foreach ($classrooms as $classroom)
                    <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                @endforeach
            </select>
            @if ($classrooms->isEmpty())
                <p class="text-danger">Tidak Ada Kelas Yang Tersedia</p>
            @endif
            @error('classroom_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="text-end">
            <a href="{{ route('dashboard.homeroom-teachers.index') }}" class="btn btn-danger btn-sm mt-3">Kembali</a>
            <button type="submit" class="btn btn-primary btn-sm mt-3">Submit</button>
        </div>
    </form>
@endsection