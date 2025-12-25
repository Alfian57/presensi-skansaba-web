@props([
    'title' => null,
    'headerClass' => 'bg-primary text-white',
    'icon' => null,
])

<div {{ $attributes->merge(['class' => 'card']) }}>
    @if($title)
        <div class="card-header {{ $headerClass }}">
            <h6 class="mb-0">
                @if($icon)
                    <i class="{{ $icon }}"></i>
                @endif
                {{ $title }}
            </h6>
        </div>
    @endif

    <div class="card-body">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endif
</div>
