@props([
    'title',
    'subtitle' => null,
    'createRoute' => null,
    'createLabel' => 'Tambah Data',
    'exportRoute' => null,
    'exportLabel' => 'Export Excel',
])

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h4 class="mb-0 fw-bold text-dark">{{ $title }}</h4>
        @if($subtitle)
            <small class="text-muted">{{ $subtitle }}</small>
        @endif
    </div>
    <div class="d-flex flex-wrap gap-2">
        @if($exportRoute)
            <a href="{{ $exportRoute }}" class="btn btn-success btn-sm shadow-sm">
                <i class="fas fa-file-excel me-1"></i> {{ $exportLabel }}
            </a>
        @endif
        @if($createRoute)
            <a href="{{ $createRoute }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="fas fa-plus me-1"></i> {{ $createLabel }}
            </a>
        @endif
        {{ $slot }}
    </div>
</div>
