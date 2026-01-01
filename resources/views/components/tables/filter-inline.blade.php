{{-- 
    Inline Filter Component
    Usage: <x-tables.filter-inline action="route" />
--}}
@props([
    'action',
])

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body py-3">
        <form action="{{ $action }}" method="GET">
            <div class="row g-3 align-items-end">
                {{ $slot }}
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary btn-sm shadow-sm">
                        <i class="fas fa-search me-1"></i> Filter
                    </button>
                    <a href="{{ $action }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-times"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
