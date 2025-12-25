@extends('layouts.main')

@section('content')
    @include('components.breadcrumb')

    <h2 class="text-center mt-3">Pergantian Semester</h2>

    {{-- Current Status --}}
    <div class="card mt-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-calendar-check"></i> Status Saat Ini</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="border rounded p-3 text-center">
                        <h6 class="text-muted">Tahun Ajaran</h6>
                        <h3 class="text-primary">{{ $summary['academic_year'] }}</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 text-center">
                        <h6 class="text-muted">Semester</h6>
                        <h3 class="text-primary">{{ $summary['semester_label'] }} ({{ $summary['semester'] }})</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded p-3 text-center">
                        <h6 class="text-muted">Jadwal Aktif</h6>
                        <h3 class="text-primary">{{ $summary['schedule_count'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="border rounded p-3 text-center">
                        <h6 class="text-muted">Siswa Aktif</h6>
                        <h3 class="text-success">{{ $summary['total_students'] }}</h3>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded p-3 text-center">
                        <h6 class="text-muted">Kelas Aktif</h6>
                        <h3 class="text-success">{{ $summary['classrooms'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="row mt-4">
        {{-- Transition Semester --}}
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-sync-alt"></i> Ganti Semester</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Pindah ke semester berikutnya.
                        @if($summary['semester'] === 1)
                            <br><strong>Semester 1 → Semester 2</strong>
                        @else
                            <br><strong>Semester 2 → Semester 1</strong>
                            <br><small class="text-warning">Tahun ajaran akan berubah ke tahun berikutnya!</small>
                        @endif
                    </p>
                </div>
                <div class="card-footer">
                    <form id="transitionForm" action="{{ route('dashboard.semester-transition.transition') }}" method="POST">
                        @csrf
                        <button type="button" class="btn btn-warning w-100" onclick="confirmTransition()">
                            <i class="fas fa-arrow-right"></i> Ganti Semester
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Copy Schedules --}}
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="fas fa-copy"></i> Salin Jadwal</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Salin jadwal dari semester sebelumnya ke semester saat ini.
                        Jadwal yang sudah ada tidak akan ditimpa.
                    </p>
                </div>
                <div class="card-footer">
                    <form id="copySchedulesForm" action="{{ route('dashboard.semester-transition.copy-schedules') }}" method="POST">
                        @csrf
                        <button type="button" class="btn btn-info w-100 text-white" onclick="confirmCopySchedules()">
                            <i class="fas fa-clipboard"></i> Salin Jadwal
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Promote Students --}}
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0"><i class="fas fa-graduation-cap"></i> Kenaikan Kelas</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Naikkan siswa ke kelas berikutnya dan luluskan siswa kelas 12.
                        <br><strong class="text-danger">⚠️ Hanya lakukan di akhir tahun ajaran!</strong>
                    </p>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-danger w-100" onclick="showPromotionPreview()">
                        <i class="fas fa-arrow-up"></i> Kenaikan Kelas
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Promotion Preview Modal --}}
    <div class="modal fade" id="promoteModal" tabindex="-1" aria-labelledby="promoteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="promoteModalLabel">
                        <i class="fas fa-exclamation-triangle"></i> Preview Kenaikan Kelas
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle"></i>
                        <strong>Informasi:</strong> Berikut adalah preview perubahan data siswa yang akan dilakukan.
                    </div>

                    @php
                        $totalGrade12 = collect($promotionPreview['grade_12'])->sum('student_count');
                        $totalGrade11 = collect($promotionPreview['grade_11'])->sum('student_count');
                        $totalGrade10 = collect($promotionPreview['grade_10'])->sum('student_count');
                        $totalAffected = $totalGrade12 + $totalGrade11 + $totalGrade10;
                    @endphp

                    {{-- Summary Cards --}}
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-light text-center">
                                <div class="card-body py-2">
                                    <h4 class="mb-0 text-primary">{{ $totalAffected }}</h4>
                                    <small>Total Siswa</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white text-center">
                                <div class="card-body py-2">
                                    <h4 class="mb-0">{{ $totalGrade12 }}</h4>
                                    <small>Akan Lulus</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-dark text-center">
                                <div class="card-body py-2">
                                    <h4 class="mb-0">{{ $totalGrade11 }}</h4>
                                    <small>Kelas 11 → 12</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white text-center">
                                <div class="card-body py-2">
                                    <h4 class="mb-0">{{ $totalGrade10 }}</h4>
                                    <small>Kelas 10 → 11</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Grade 12 - Will Graduate --}}
                    @if(count($promotionPreview['grade_12']) > 0)
                        <div class="card mb-3 border-danger">
                            <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-graduation-cap"></i> Kelas 12 - Siswa Akan Lulus ({{ $totalGrade12 }} siswa)</span>
                                <button class="btn btn-sm btn-outline-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGrade12">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </div>
                            <div class="collapse show" id="collapseGrade12">
                                <div class="card-body p-0">
                                    <table class="table table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th>Kelas</th>
                                                <th>Jumlah</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($promotionPreview['grade_12'] as $item)
                                                <tr>
                                                    <td>{{ $item['classroom'] }}</td>
                                                    <td>{{ $item['student_count'] }} siswa</td>
                                                    <td><span class="badge bg-danger">{{ $item['action'] }}</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Grade 11 --}}
                    @if(count($promotionPreview['grade_11']) > 0)
                        <div class="card mb-3 border-warning">
                            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-arrow-up"></i> Kelas 11 → 12 ({{ $totalGrade11 }} siswa)</span>
                                <button class="btn btn-sm btn-outline-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGrade11">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </div>
                            <div class="collapse" id="collapseGrade11">
                                <div class="card-body p-0">
                                    <table class="table table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th>Kelas</th>
                                                <th>Jumlah</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($promotionPreview['grade_11'] as $item)
                                                <tr>
                                                    <td>{{ $item['classroom'] }}</td>
                                                    <td>{{ $item['student_count'] }} siswa</td>
                                                    <td><span class="badge bg-warning text-dark">{{ $item['action'] }}</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Grade 10 --}}
                    @if(count($promotionPreview['grade_10']) > 0)
                        <div class="card mb-3 border-success">
                            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-arrow-up"></i> Kelas 10 → 11 ({{ $totalGrade10 }} siswa)</span>
                                <button class="btn btn-sm btn-outline-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGrade10">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </div>
                            <div class="collapse" id="collapseGrade10">
                                <div class="card-body p-0">
                                    <table class="table table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th>Kelas</th>
                                                <th>Jumlah</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($promotionPreview['grade_10'] as $item)
                                                <tr>
                                                    <td>{{ $item['classroom'] }}</td>
                                                    <td>{{ $item['student_count'] }} siswa</td>
                                                    <td><span class="badge bg-success">{{ $item['action'] }}</span></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="button" class="btn btn-danger" onclick="confirmPromotion()">
                        <i class="fas fa-check"></i> Lanjutkan Kenaikan Kelas
                    </button>
                </div>
            </div>
        </div>
    </div>

    <form id="promoteForm" action="{{ route('dashboard.semester-transition.promote') }}" method="POST" style="display: none;">
        @csrf
    </form>
@endsection

@push('scripts')
<script>
    function confirmTransition() {
        Swal.fire({
            title: 'Ganti Semester?',
            html: `
                <p>Apakah Anda yakin ingin mengganti semester?</p>
                <p class="text-warning"><strong>Pastikan semua data semester ini sudah lengkap.</strong></p>
                @if($summary['semester'] === 2)
                <p class="text-danger"><strong>⚠️ Tahun ajaran akan berubah ke tahun berikutnya!</strong></p>
                @endif
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-check"></i> Ya, Ganti Semester',
            cancelButtonText: '<i class="fas fa-times"></i> Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('transitionForm').submit();
            }
        });
    }

    function confirmCopySchedules() {
        Swal.fire({
            title: 'Salin Jadwal?',
            text: 'Jadwal dari semester sebelumnya akan disalin ke semester saat ini. Jadwal yang sudah ada tidak akan ditimpa.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#17a2b8',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-copy"></i> Ya, Salin Jadwal',
            cancelButtonText: '<i class="fas fa-times"></i> Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('copySchedulesForm').submit();
            }
        });
    }

    function showPromotionPreview() {
        var modal = new bootstrap.Modal(document.getElementById('promoteModal'));
        modal.show();
    }

    function confirmPromotion() {
        // Close the preview modal first
        var previewModal = bootstrap.Modal.getInstance(document.getElementById('promoteModal'));
        previewModal.hide();

        Swal.fire({
            title: 'Konfirmasi Kenaikan Kelas',
            html: `
                <div class="text-start">
                    <p><strong>Tindakan ini akan:</strong></p>
                    <ul>
                        <li class="text-danger">Meluluskan semua siswa kelas 12 (status: alumni)</li>
                        <li class="text-warning">Menaikkan kelas 11 → 12</li>
                        <li class="text-success">Menaikkan kelas 10 → 11</li>
                    </ul>
                    <p class="text-danger mt-3"><strong>⚠️ PERHATIAN: Tindakan ini TIDAK DAPAT dibatalkan!</strong></p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-graduation-cap"></i> Ya, Proses Kenaikan Kelas',
            cancelButtonText: '<i class="fas fa-times"></i> Batal',
            reverseButtons: true,
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Mohon tunggu, sedang memproses kenaikan kelas.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                document.getElementById('promoteForm').submit();
            }
        });
    }
</script>
@endpush
