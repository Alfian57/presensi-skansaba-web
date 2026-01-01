{{-- 
    Form Actions Component
    Usage: <x-forms.actions backRoute="route.name" submitLabel="Simpan" />
--}}
@props([
    'backRoute' => null,
    'backLabel' => 'Kembali',
    'submitLabel' => 'Simpan',
    'submitIcon' => 'fas fa-save',
])

<div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
    @if($backRoute)
        <a href="{{ $backRoute }}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> {{ $backLabel }}
        </a>
    @endif
    <button type="submit" class="btn btn-primary shadow-sm">
        <i class="{{ $submitIcon }} me-1"></i> {{ $submitLabel }}
    </button>
</div>
