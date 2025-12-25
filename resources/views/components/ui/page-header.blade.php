@props([
    'title',
    'subtitle' => null,
    'createRoute' => null,
    'createLabel' => 'Tambah Data',
    'exportRoute' => null,
    'exportLabel' => 'Export Excel',
])

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">{{ $title }}</h4>
        @if($subtitle)
            <small class="text-muted">{{ $subtitle }}</small>
        @endif
    </div>
    <div class="d-flex gap-2">
        @if($exportRoute)
            <a href="{{ $exportRoute }}" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel me-1"></i> {{ $exportLabel }}
            </a>
        @endif
        @if($createRoute)
            <a href="{{ $createRoute }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> {{ $createLabel }}
            </a>
        @endif
        {{ $slot }}
    </div>
</div>
