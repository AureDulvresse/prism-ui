@props([
    'variant' => halo_default('button', 'variant', 'primary'),
    'size' => halo_default('button', 'size', 'md'),
    'type' => 'button',
    'disabled' => false,
    'loading' => false,
    'icon' => null,
    'iconPosition' => 'left',
])

@php
$classes = halo_variants([
    'base' => 'inline-flex items-center justify-center gap-2 font-medium rounded-halo border transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-halo-ring focus-visible:ring-offset-2 focus-visible:ring-offset-halo-background active:opacity-80 disabled:opacity-50 disabled:cursor-not-allowed',
    'variants' => [
        'variant' => [
            'primary' => 'bg-halo-primary text-halo-primary-foreground border-transparent hover:opacity-90',
            'secondary' => 'bg-halo-secondary text-halo-secondary-foreground border-transparent hover:opacity-90',
            'outline' => 'bg-transparent text-halo-foreground border-halo-border hover:bg-halo-secondary',
            'ghost' => 'bg-transparent text-halo-foreground border-transparent hover:bg-halo-secondary',
            'danger' => 'bg-halo-danger text-halo-danger-foreground border-transparent hover:opacity-90',
        ],
        'size' => [
            'sm' => 'px-3 py-1.5 text-sm',
            'md' => 'px-4 py-2 text-base',
            'lg' => 'px-6 py-3 text-lg',
        ],
    ],
    'defaults' => ['variant' => 'primary', 'size' => 'md'],
], compact('variant', 'size'), $attributes->get('class'));
@endphp

<button
    type="{{ $type }}"
    aria-busy="{{ $loading ? 'true' : 'false' }}"
    @if($disabled || $loading) disabled @endif
    {{ $attributes->except(['variant', 'size', 'type', 'disabled', 'loading', 'icon', 'iconPosition', 'class'])->merge(['class' => $classes]) }}
>
    @if($loading)
        <x-halo::spinner size="sm" aria-hidden="true" />
    @elseif($icon && $iconPosition === 'left')
        <x-halo::icon :name="$icon" size="sm" />
    @endif

    {{ $slot }}

    @if($icon && $iconPosition === 'right' && ! $loading)
        <x-halo::icon :name="$icon" size="sm" />
    @endif
</button>

