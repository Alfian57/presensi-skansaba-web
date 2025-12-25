@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <div class="row mt-3">
        <div class="col-md-4">
            <x-ui.card title="Informasi Mata Pelajaran" headerClass="bg-primary text-white">
                <table class="table table-borderless">
                    <tr><th width="40%">Kode</th><td>: {{ $subject->code }}</td></tr>
                    <tr><th>Nama</th><td>: {{ $subject->name }}</td></tr>
                    <tr><th>Deskripsi</th><td>: {{ $subject->description ?? '-' }}</td></tr>
                </table>

                <div class="text-end mt-3">
                    <a href="{{ route('dashboard.subjects.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <a href="{{ route('dashboard.subjects.edit', $subject) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
            </x-ui.card>
        </div>

        <div class="col-md-8">
            <x-ui.card title="Jadwal Mengajar" headerClass="bg-success text-white" class="mb-3">
                @if($subject->schedules->isEmpty())
                    <p class="text-center text-muted">Belum ada jadwal untuk mata pelajaran ini</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead><tr><th>#</th><th>Kelas</th><th>Guru</th><th>Hari</th><th>Waktu</th></tr></thead>
                            <tbody>
                                @foreach($subject->schedules->sortBy('day') as $schedule)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $schedule->classroom->name }}</td>
                                        <td>{{ $schedule->teacher->user->name }}</td>
                                        <td>{{ ucfirst($schedule->day->value ?? $schedule->day) }}</td>
                                        <td>{{ $schedule->time_start ?? $schedule->start_time }} - {{ $schedule->time_finish ?? $schedule->end_time }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-ui.card>

            <x-ui.card title="Guru Pengampu" headerClass="bg-info text-white">
                @php $teachers = $subject->schedules->pluck('teacher')->unique('id'); @endphp

                @if($teachers->isEmpty())
                    <p class="text-center text-muted">Belum ada guru yang mengampu</p>
                @else
                    <div class="row">
                        @foreach($teachers as $teacher)
                            <div class="col-md-6 mb-3">
                                <div class="card border-primary">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                @if($teacher->user->profile_picture)
                                                    <img src="{{ asset('storage/' . $teacher->user->profile_picture) }}" alt="{{ $teacher->user->name }}" class="rounded-circle" width="60" height="60" style="object-fit: cover;">
                                                @else
                                                    <i class="fas fa-user-circle fa-3x text-secondary"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $teacher->user->name }}</h6>
                                                <small class="text-muted">{{ $teacher->nip }}</small><br>
                                                <x-ui.badge type="info">{{ $subject->schedules->where('teacher_id', $teacher->id)->count() }} kelas</x-ui.badge>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-ui.card>
        </div>
    </div>
@endsection