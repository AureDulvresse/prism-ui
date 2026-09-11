@props([
    'position' => halo_default('hover-card', 'position', 'bottom'),
])

@php
$id = 'halo-hover-card-'.uniqid();

$positions = [
    'top' => 'bottom-full left-1/2 mb-2 -translate-x-1/2',
    'bottom' => 'top-full left-1/2 mt-2 -translate-x-1/2',
    'left' => 'right-full top-1/2 mr-2 -translate-y-1/2',
    'right' => 'left-full top-1/2 ml-2 -translate-y-1/2',
];

$classes = halo_merge_classes(
    'absolute z-50 w-64 rounded-halo border border-halo-border bg-halo-background p-4 text-sm text-halo-foreground shadow-lg',
    $positions[$position] ?? $positions['bottom'],
    $attributes->get('class'),
);
@endphp

<div
    x-data="haloHoverCard('{{ $id }}')"
    class="relative inline-block"
    @mouseenter="show()"
    @mouseleave="hide()"
    @focusin="show()"
    @focusout="hide()"
>
    <div x-ref="trigger">
        {{ $trigger ?? '' }}
    </div>

    <div
        id="{{ $id }}"
        x-show="open"
        x-cloak
        x-transition
        {{ $attributes->except(['position', 'class'])->merge(['class' => $classes]) }}
    >
        {{ $slot }}
    </div>
</div>
