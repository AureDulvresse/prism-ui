@props([
    'value' => null,
    'min' => null,
    'max' => null,
    'step' => 1,
    'size' => halo_default('number-input', 'size', 'md'),
    'invalid' => false,
    'disabled' => false,
    'id' => null,
    'error' => null,
])

@php
$inputId = $id ?? $attributes->get('name') ?? uniqid('halo-number-input-');
$errorId = $inputId.'-error';
$isInvalid = $invalid || $error;

$atMin = $min !== null && $value !== null && (float) $value <= (float) $min;
$atMax = $max !== null && $value !== null && (float) $value >= (float) $max;

$wrapperClasses = halo_merge_classes(
    'inline-flex items-stretch rounded-halo border bg-halo-background disabled:opacity-50 focus-within:outline-none focus-within:ring-2 focus-within:ring-halo-ring',
    $isInvalid ? 'border-halo-danger focus-within:ring-halo-danger' : 'border-halo-border',
    $disabled ? 'opacity-50 cursor-not-allowed' : null,
);

$buttonClasses = halo_variants([
    'base' => 'flex shrink-0 items-center justify-center text-halo-foreground/70 transition-colors hover:bg-halo-secondary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-halo-ring disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-transparent',
    'variants' => [
        'size' => [
            'sm' => 'px-2',
            'md' => 'px-3',
            'lg' => 'px-4',
        ],
    ],
    'defaults' => ['size' => 'md'],
], compact('size'));

$inputClasses = halo_variants([
    'base' => 'min-w-0 flex-1 border-0 bg-transparent text-center text-halo-foreground placeholder:text-halo-foreground/50 focus-visible:outline-none disabled:cursor-not-allowed [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none',
    'variants' => [
        'size' => [
            'sm' => 'px-2 py-1.5 text-sm',
            'md' => 'px-3 py-2 text-base',
            'lg' => 'px-4 py-3 text-lg',
        ],
    ],
    'defaults' => ['size' => 'md'],
], compact('size'));

$inputClasses = halo_merge_classes($inputClasses, $attributes->get('class'));
@endphp

<div>
    <div
        x-data="haloNumberInput({ value: @js($value), min: @js($min), max: @js($max), step: @js($step) })"
        class="{{ $wrapperClasses }}"
    >
        <button
            type="button"
            aria-label="Decrement"
            class="{{ $buttonClasses }}"
            @click="decrement()"
            :disabled="atMin || {{ $disabled ? 'true' : 'false' }}"
            @if($disabled || $atMin) disabled @endif
        >
            <x-halo::icon name="minus" size="sm" />
        </button>

        <input
            type="number"
            id="{{ $inputId }}"
            x-ref="input"
            @if($min !== null) min="{{ $min }}" @endif
            @if($max !== null) max="{{ $max }}" @endif
            step="{{ $step }}"
            @if($value !== null) value="{{ $value }}" @endif
            @input="sync($event)"
            @if($disabled) disabled @endif
            @if($isInvalid) aria-invalid="true" aria-describedby="{{ $errorId }}" @endif
            {{ $attributes->except(['value', 'min', 'max', 'step', 'size', 'invalid', 'disabled', 'id', 'error', 'class'])->merge(['class' => $inputClasses]) }}
        />

        <button
            type="button"
            aria-label="Increment"
            class="{{ $buttonClasses }}"
            @click="increment()"
            :disabled="atMax || {{ $disabled ? 'true' : 'false' }}"
            @if($disabled || $atMax) disabled @endif
        >
            <x-halo::icon name="plus" size="sm" />
        </button>
    </div>

    @if($error)
        <p id="{{ $errorId }}" class="mt-1 text-xs text-halo-danger">{{ $error }}</p>
    @endif
</div>
