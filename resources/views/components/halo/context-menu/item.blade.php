@props([
    'href' => null,
])

@php
$classes = halo_merge_classes(
    'block w-full text-left px-4 py-2 text-sm text-halo-foreground transition-colors hover:bg-halo-secondary active:bg-halo-secondary/80 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-halo-ring focus-visible:ring-inset',
    $attributes->get('class'),
);
@endphp

@if($href)
    <a href="{{ $href }}" role="menuitem" {{ $attributes->except(['href', 'class'])->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="button" role="menuitem" {{ $attributes->except(['href', 'class'])->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
