@props([
    'action' => null,
    'method' => 'GET',
])

<form action="{{ $action ?? request()->url() }}" method="{{ $method }}" class="mb-4">
    <div class="card">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                {{ $slot }}
                
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i> Filter
                    </button>
                    <a href="{{ $action ?? request()->url() }}" class="btn btn-secondary">
                        <i class="fas fa-redo me-1"></i> Reset
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
