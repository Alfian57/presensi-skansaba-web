@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <x-ui.card title="Tambah Data Siswa Bolos" icon="fas fa-user-times">
                <form action="{{ route('dashboard.class-absences.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="student_id" class="form-label">Nama Siswa <span class="text-danger">*</span></label>
                        <select class="form-select @error('student_id') is-invalid @enderror" name="student_id" id="student_id" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>
                                    {{ $student->user->name ?? 'N/A' }} - {{ $student->classroom->name ?? '' }}
                                </option>
                            @endforeach
                        </select>
                        @if ($students->isEmpty())
                            <small class="text-danger">Tidak ada siswa yang tersedia</small>
                        @endif
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="schedule_id" class="form-label">Jadwal Pelajaran <span class="text-danger">*</span></label>
                        <select class="form-select @error('schedule_id') is-invalid @enderror" name="schedule_id" id="schedule_id" required>
                            <option value="">-- Pilih terlebih dahulu siswa --</option>
                        </select>
                        <small id="schedule_hint" class="text-muted">Pilih siswa terlebih dahulu untuk melihat jadwal</small>
                        @error('schedule_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <x-forms.textarea name="reason" label="Alasan (Opsional)" placeholder="Masukkan alasan jika ada" />

                    <x-forms.actions :backRoute="route('dashboard.class-absences.index')" submitLabel="Simpan Data" />
                </form>
            </x-ui.card>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#student_id').change(function() {
                var studentId = $(this).val();
                var scheduleSelect = $('#schedule_id');
                var hint = $('#schedule_hint');
                
                if (!studentId) {
                    scheduleSelect.html('<option value="">-- Pilih terlebih dahulu siswa --</option>');
                    hint.text('Pilih siswa terlebih dahulu untuk melihat jadwal').removeClass('text-danger').addClass('text-muted');
                    return;
                }
                
                hint.text('Memuat jadwal...').removeClass('text-danger').addClass('text-muted');
                
                $.ajax({
                    type: "GET",
                    url: "/api/get-schedules/" + studentId,
                    success: function(response) {
                        scheduleSelect.html('<option value="">-- Pilih Jadwal --</option>');
                        
                        if (response.length == 0) {
                            hint.text('Tidak ada jadwal untuk siswa ini hari ini').addClass('text-danger').removeClass('text-muted');
                        } else {
                            hint.text('').removeClass('text-danger text-muted');
                            $.each(response, function(index, value) {
                                scheduleSelect.append(
                                    '<option value="' + value.id + '">' + value.name + ' | ' + value.time_start + ' - ' + value.time_finish + '</option>'
                                );
                            });
                        }
                    },
                    error: function() {
                        hint.text('Gagal memuat jadwal').addClass('text-danger').removeClass('text-muted');
                    }
                });
            });
        });
    </script>
    @endpush
@endsection