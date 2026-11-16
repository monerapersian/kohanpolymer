@props([
    'name',
    'id' => null,
    'value' => null,
    'placeholder' => 'انتخاب تاریخ',
    'required' => false,
    'disabled' => false,
    'class' => '',
])

@php
    $fieldId = $id ?: 'jalali-date-' . uniqid();
    $targetId = $fieldId . '-gregorian';

    $jalaliValue = $value
        ? \App\Support\NumberHelper::toPersian(
            \App\Support\DateHelper::toPersian($value, 'Y/m/d')
        )
        : '';
@endphp

<div class="relative w-full">
    <input
        type="text"
        id="{{ $fieldId }}"
        value="{{ $jalaliValue }}"
        placeholder="{{ $placeholder }}"
        autocomplete="off"
        data-jdp
        data-jdp-only-date
        data-jdp-target-value-input="#{{ $targetId }}"
        data-jdp-target-value-type="gregorian"
        @if($required) required @endif
        @if($disabled) disabled @endif
        class="{{ $class }}"
    >

    <input
        type="hidden"
        name="{{ $name }}"
        id="{{ $targetId }}"
        value="{{ $value }}"
    >
</div>