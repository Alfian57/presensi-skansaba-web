@extends('layouts.main')



@section('content')
    @include('components.breadcrumb')

    <h2 class="text-center mt-3">Ubah Jadwal Mengajar</h2>

    <form action="{{ route('dashboard.schedules.update', $schedule->id) }}" method="POST">
        @method('put')
        @csrf
        <div class="row">
            <div class="mb-3">
                <label for="academic_year" class="form-label">Tahun Pelajaran</label>
                <input type="text" class="form-control @error('academic_year') is-invalid @enderror" 
                    name="academic_year" id="academic_year" 
                    placeholder="2022/2023" value="{{ old('academic_year', $schedule->academic_year) }}" required autofocus>
                @error('academic_year')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="semester" class="form-label">Semester</label>
                <select class="form-select @error('semester') is-invalid @enderror" name="semester" id="semester" required>
                    <option value="" disabled>Pilih Semester</option>
                    <option value="1" {{ old('semester', $schedule->semester) == '1' ? 'selected' : '' }}>Ganjil</option>
                    <option value="2" {{ old('semester', $schedule->semester) == '2' ? 'selected' : '' }}>Genap</option>
                </select>
                @error('semester')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="classroom_id" class="form-label">Kelas</label>
                <select class="form-select @error('classroom_id') is-invalid @enderror" name="classroom_id"
                    id="classroom_id" required>
                    <option value="" disabled>Pilih Kelas</option>
                    @foreach ($classrooms as $classroom)
                        <option value="{{ $classroom->id }}" {{ old('classroom_id', $schedule->classroom_id) == $classroom->id ? 'selected' : '' }}>
                            {{ $classroom->name }}
                        </option>
                    @endforeach
                </select>
                @if ($classrooms->isEmpty())
                    <p class="text-danger small mt-1">Data Kelas Masih Kosong</p>
                @endif
                @error('classroom_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="subject_id" class="form-label">Mata Pelajaran</label>
                <select class="form-select @error('subject_id') is-invalid @enderror" name="subject_id" id="subject_id"
                    required>
                    <option value="" disabled>Pilih Mata Pelajaran</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id', $schedule->subject_id) == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
                @if ($subjects->isEmpty())
                    <p class="text-danger small mt-1">Data Mata Pelajaran Masih Kosong</p>
                @endif
                @error('subject_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="teacher_id" class="form-label">Guru Mata Pelajaran</label>
                <select class="form-select @error('teacher_id') is-invalid @enderror" name="teacher_id" id="teacher_id"
                    required>
                    <option value="" disabled>Pilih Guru</option>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ old('teacher_id', $schedule->teacher_id) == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
                @if ($teachers->isEmpty())
                    <p class="text-danger small mt-1">Data Guru Masih Kosong</p>
                @endif
                @error('teacher_id')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="mb-2">Hari</label>
                <div class="d-flex flex-wrap">
                    @foreach ($days as $en => $id_day)
                        <div class="form-check ms-3">
                            <input class="form-check-input" value="{{ $en }}" type="radio" name="day" 
                                id="day_{{ $en }}" {{ old('day', $schedule->day instanceof \App\Enums\Day ? $schedule->day->value : $schedule->day) == $en ? 'checked' : '' }}>
                            <label class="form-check-label" for="day_{{ $en }}">
                                {{ $id_day }}
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('day')
                    <div class="text-danger small mt-1">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="start_time" class="form-label">Waktu Mulai</label>
                <input type="time" class="form-control @error('start_time') is-invalid @enderror" name="start_time"
                    id="start_time" value="{{ old('start_time', $start_time) }}" required>
                @error('start_time')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="end_time" class="form-label">Waktu Selesai</label>
                <input type="time" class="form-control @error('end_time') is-invalid @enderror" name="end_time"
                    id="end_time" value="{{ old('end_time', $end_time) }}" required>
                @error('end_time')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="room" class="form-label">Ruang</label>
                <input type="text" class="form-control @error('room') is-invalid @enderror" name="room" id="room"
                    placeholder="Contoh: Lab Komputer" value="{{ old('room', $schedule->room) }}" required maxlength="100">
                @error('room')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="text-end">
                <a href="{{ route('dashboard.schedules.index') }}" class="btn btn-danger btn-sm me-2 mt-3">Kembali</a>
                <button type="submit" class="btn btn-primary btn-sm mt-3">Submit</button>
            </div>
        </div>
    </form>
@endsection
