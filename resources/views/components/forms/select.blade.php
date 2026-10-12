@props([
    'name',
    'label'    => '',
    'options'  => [],
    'required' => false,
    'error'    => null,
    'id'       => null,
    'value'    => '',
])

@php
    $fieldId = $id ?? $name;
@endphp

<div class="form-field">
    @if($label)
        <label for="{{ $fieldId }}" class="form-field__label">{{ $label }}</label>
    @endif

    <select
        id="{{ $fieldId }}"
        name="{{ $name }}"
        @if($required) required @endif
        class="form-field__select{{ $error ? ' form-field__select--error' : '' }}"
        {{ $attributes->except(['name', 'label', 'options', 'required', 'error', 'id', 'value']) }}
    >
        <option value="" disabled {{ old($name, $value) === '' ? 'selected' : '' }}>Select an option</option>
        @foreach($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" {{ old($name, $value) == $optionValue ? 'selected' : '' }}>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>

    @if($error)
        <span class="form-field__error-msg">{{ $error }}</span>
    @endif
</div>
