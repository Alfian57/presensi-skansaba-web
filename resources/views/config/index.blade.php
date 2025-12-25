@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <h2 class="text-center mt-3">Konfigurasi Sistem</h2>

    <form action="{{ route('dashboard.config.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row mt-4">
            {{-- Time Settings --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-clock"></i> Pengaturan Waktu Presensi</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="check_in_start" class="form-label">Jam Mulai Check-In</label>
                            <input type="time" class="form-control @error('check_in_start') is-invalid @enderror" 
                                   id="check_in_start" name="check_in_start" 
                                   value="{{ old('check_in_start', $configs['check_in_start']->value ?? '06:00') }}">
                            @error('check_in_start')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="late_threshold" class="form-label">Batas Terlambat</label>
                            <input type="time" class="form-control @error('late_threshold') is-invalid @enderror" 
                                   id="late_threshold" name="late_threshold" 
                                   value="{{ old('late_threshold', $configs['late_threshold']->value ?? '07:00') }}">
                            @error('late_threshold')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="check_in_end" class="form-label">Jam Selesai Check-In</label>
                            <input type="time" class="form-control @error('check_in_end') is-invalid @enderror" 
                                   id="check_in_end" name="check_in_end" 
                                   value="{{ old('check_in_end', $configs['check_in_end']->value ?? '08:00') }}">
                            @error('check_in_end')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="check_out_start" class="form-label">Jam Mulai Check-Out</label>
                            <input type="time" class="form-control @error('check_out_start') is-invalid @enderror" 
                                   id="check_out_start" name="check_out_start" 
                                   value="{{ old('check_out_start', $configs['check_out_start']->value ?? '14:00') }}">
                            @error('check_out_start')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- School Info --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="fas fa-school"></i> Informasi Sekolah</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="school_name" class="form-label">Nama Sekolah</label>
                            <input type="text" class="form-control @error('school_name') is-invalid @enderror" 
                                   id="school_name" name="school_name" 
                                   value="{{ old('school_name', $configs['school_name']->value ?? '') }}">
                            @error('school_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="school_address" class="form-label">Alamat Sekolah</label>
                            <textarea class="form-control @error('school_address') is-invalid @enderror" 
                                      id="school_address" name="school_address" rows="2">{{ old('school_address', $configs['school_address']->value ?? '') }}</textarea>
                            @error('school_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="school_phone" class="form-label">Telepon Sekolah</label>
                            <input type="text" class="form-control @error('school_phone') is-invalid @enderror" 
                                   id="school_phone" name="school_phone" 
                                   value="{{ old('school_phone', $configs['school_phone']->value ?? '') }}">
                            @error('school_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="academic_year" class="form-label">Tahun Ajaran</label>
                            <input type="text" class="form-control @error('academic_year') is-invalid @enderror" 
                                   id="academic_year" name="academic_year" placeholder="2024/2025"
                                   value="{{ old('academic_year', $configs['academic_year']->value ?? '') }}">
                            @error('academic_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save"></i> Simpan Konfigurasi
            </button>
        </div>
    </form>

    {{-- QR Code Section --}}
    <div class="card mt-4">
        <div class="card-header bg-info text-white">
            <h6 class="mb-0"><i class="fas fa-qrcode"></i> QR Code Presensi</h6>
        </div>
        <div class="card-body text-center">
            <div class="row">
                <div class="col-md-6">
                    <a href="{{ route('dashboard.config.qr.display') }}" class="btn btn-outline-primary btn-lg" target="_blank">
                        <i class="fas fa-external-link-alt"></i> Tampilkan QR Code
                    </a>
                </div>
                <div class="col-md-6">
                    <form action="{{ route('dashboard.config.qr.refresh') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-lg">
                            <i class="fas fa-sync-alt"></i> Refresh QR Code
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
