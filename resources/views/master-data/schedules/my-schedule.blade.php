@extends('layouts.main')


@section('content')
    @include('components.breadcrumb')

    <div class="schedule-page">
        {{-- Page Header --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-gradient-primary text-white shadow-lg border-0">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="icon-box me-3">
                                <i class="fas fa-calendar-alt fa-2x"></i>
                            </div>
                            <div>
                                <h2 class="mb-0 text-white">Jadwal Mengajar</h2>
                                <p class="mb-0 opacity-75">Daftar jadwal mengajar Anda</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (!$isNotEmpty)
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum Ada Jadwal</h5>
                        <p class="text-muted small">Anda belum memiliki jadwal mengajar yang terdaftar.</p>
                    </div>
                </div>
            </div>
        @else
            @php
                $dayOrder = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                $dayColors = [
                    'monday' => 'primary',
                    'tuesday' => 'success',
                    'wednesday' => 'info',
                    'thursday' => 'warning',
                    'friday' => 'danger',
                    'saturday' => 'secondary',
                ];
                $dayIcons = [
                    'monday' => 'fa-flag',
                    'tuesday' => 'fa-fire',
                    'wednesday' => 'fa-bolt',
                    'thursday' => 'fa-star',
                    'friday' => 'fa-heart',
                    'saturday' => 'fa-sun',
                ];
            @endphp

            @foreach ($dayOrder as $dayValue)
                @if (isset($schedules[$dayValue]) && !$schedules[$dayValue]->isEmpty())
                    @php
                        $dayLabel = \App\Enums\Day::tryFrom($dayValue)?->label() ?? ucfirst($dayValue);
                        $color = $dayColors[$dayValue] ?? 'primary';
                        $icon = $dayIcons[$dayValue] ?? 'fa-calendar-day';
                    @endphp

                    <div class="schedule-day mb-4">
                        <div class="day-header d-flex align-items-center mb-3">
                            <div class="day-badge bg-{{ $color }} text-white rounded-pill px-3 py-2 d-inline-flex align-items-center">
                                <i class="fas {{ $icon }} me-2"></i>
                                <span class="fw-bold">{{ $dayLabel }}</span>
                            </div>
                            <div class="day-line flex-grow-1 ms-3"></div>
                            <span class="badge bg-light text-dark ms-3">{{ $schedules[$dayValue]->count() }} Jadwal</span>
                        </div>

                        <div class="row g-3">
                            @foreach ($schedules[$dayValue] as $item)
                                <div class="col-lg-4 col-md-6">
                                    <div class="card schedule-card border-0 shadow-sm h-100 hover-lift">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start mb-3">
                                                <div
                                                    class="time-badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} rounded px-2 py-1 me-auto">
                                                    <i class="far fa-clock me-1"></i>
                                                    {{ $item->start_time?->format('H:i') ?? '-' }} -
                                                    {{ $item->end_time?->format('H:i') ?? '-' }}
                                                </div>
                                            </div>

                                            <h5 class="card-title mb-2">{{ $item->subject->name }}</h5>

                                            <div class="schedule-info">
                                                <div class="info-item d-flex align-items-center mb-2">
                                                    <div class="info-icon bg-light rounded-circle p-2 me-2">
                                                        <i class="fas fa-door-open text-muted"></i>
                                                    </div>
                                                    <div>
                                                        <small class="text-muted d-block">Kelas</small>
                                                        <span class="fw-medium">{{ $item->classroom->name }}</span>
                                                    </div>
                                                </div>

                                                @if ($item->room)
                                                    <div class="info-item d-flex align-items-center">
                                                        <div class="info-icon bg-light rounded-circle p-2 me-2">
                                                            <i class="fas fa-map-marker-alt text-muted"></i>
                                                        </div>
                                                        <div>
                                                            <small class="text-muted d-block">Ruangan</small>
                                                            <span class="fw-medium">{{ $item->room }}</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .icon-box {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .day-line {
            height: 2px;
            background: linear-gradient(90deg, #dee2e6 0%, transparent 100%);
        }

        .schedule-card {
            transition: all 0.3s ease;
            border-radius: 12px;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .time-badge {
            font-size: 0.85rem;
            font-weight: 500;
        }

        .info-icon {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .info-icon i {
            font-size: 0.9rem;
        }

        .day-badge {
            font-size: 0.95rem;
        }

        .empty-state i {
            opacity: 0.5;
        }

        .schedule-info .info-item:last-child {
            margin-bottom: 0 !important;
        }
    </style>
@endpush