@props([
    'title' => null,
    'headerClass' => 'bg-gradient-primary text-white',
    'icon' => null,
    'bodyClass' => '',
])

<div {{ $attributes->merge(['class' => 'card shadow-sm border-0']) }}>
    @if($title)
        <div class="card-header {{ $headerClass }}">
            <h5 class="card-title mb-0" style="color: inherit;">
                @if($icon)
                    <i class="{{ $icon }} me-2"></i>
                @endif
                {{ $title }}
            </h5>
        </div>
    @endif

    <div class="card-body {{ $bodyClass }}">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="card-footer bg-light border-top-0">
            {{ $footer }}
        </div>
    @endif
</div>

@once
    @push('styles')
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card.shadow-sm {
            border-radius: 0.75rem;
        }
        .card-header {
            border-radius: 0.75rem 0.75rem 0 0 !important;
            padding: 1rem 1.25rem;
        }
        .card-title {
            font-weight: 600;
        }
    </style>
    @endpush
@endonce
