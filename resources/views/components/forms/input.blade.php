@props([
    'name',
    'label'       => '',
    'type'        => 'text',
    'required'    => false,
    'error'       => null,
    'icon'        => null,
    'placeholder' => '',
    'id'          => null,
    'value'       => '',
])

@php
    $fieldId    = $id ?? $name;
    $hasIcon    = !is_null($icon);
    $isPassword = $type === 'password';
@endphp

<div
    class="form-field"
    @if($isPassword) x-data="{ showPassword: false }" @endif
>
    @if($label)
        <label for="{{ $fieldId }}" class="form-field__label">{{ $label }}</label>
    @endif

    <div class="form-field__wrapper{{ $hasIcon ? ' form-field__wrapper--has-icon' : '' }}{{ $isPassword ? ' form-field__wrapper--has-toggle' : '' }}">

        @if($hasIcon)
            <span class="form-field__icon" aria-hidden="true">
                @if($icon === 'email')
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                @elseif($icon === 'lock')
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                @elseif($icon === 'user')
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                @endif
            </span>
        @endif

        <input
            id="{{ $fieldId }}"
            name="{{ $name }}"
            @if($isPassword)
                :type="showPassword ? 'text' : 'password'"
            @else
                type="{{ $type }}"
            @endif
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            @if(!$isPassword) value="{{ old($name, $value) }}" @endif
            class="form-field__input{{ $hasIcon ? ' form-field__input--has-icon' : '' }}{{ $isPassword ? ' form-field__input--has-toggle' : '' }}{{ $error ? ' form-field__input--error' : '' }}"
            {{ $attributes->except(['name', 'label', 'type', 'required', 'error', 'icon', 'placeholder', 'id', 'value']) }}
        >

        @if($isPassword)
            <button
                type="button"
                class="form-field__toggle"
                id="{{ $fieldId }}-toggle"
                @click="showPassword = !showPassword"
                aria-label="Toggle password visibility"
            >
                <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                <svg x-show="showPassword" x-cloak xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
            </button>
        @endif
    </div>

    @if($error)
        <span class="form-field__error-msg">{{ $error }}</span>
    @endif
</div>
