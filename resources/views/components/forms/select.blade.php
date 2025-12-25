@props([
    'name',
    'label' => null,
    'options' => [],
    'value' => null,
    'placeholder' => '-- Pilih --',
    'required' => false,
    'disabled' => false,
])

@php
    $id = $name;
    $hasError = $errors->has($name);
    $selected = old($name, $value);
@endphp

<div class="mb-3">
    @if($label)
        <label for="{{ $id }}" class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <select 
        id="{{ $id }}"
        name="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => 'form-control' . ($hasError ? ' is-invalid' : '')]) }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach($options as $key => $option)
            @if(is_array($option) || is_object($option))
                @php
                    $optionValue = is_object($option) ? ($option->id ?? $option->value ?? $key) : ($option['id'] ?? $option['value'] ?? $key);
                    $optionLabel = is_object($option) ? ($option->name ?? $option->label ?? $optionValue) : ($option['name'] ?? $option['label'] ?? $optionValue);
                @endphp
                <option value="{{ $optionValue }}" {{ $selected == $optionValue ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @else
                <option value="{{ $key }}" {{ $selected == $key ? 'selected' : '' }}>
                    {{ $option }}
                </option>
            @endif
        @endforeach
    </select>

    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
