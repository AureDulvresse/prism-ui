@props([
    'href' => null,
    'active' => false,
    'icon' => null,
])

@php
$classes = halo_merge_classes(
    'flex items-center gap-3 rounded-halo px-3 py-2 text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-halo-ring',
    $active ? 'bg-halo-secondary text-halo-foreground' : 'text-halo-foreground/70 hover:bg-halo-secondary hover:text-halo-foreground',
    $attributes->get('class'),
);
@endphp

<a
    href="{{ $href }}"
    @if($active) aria-current="page" @endif
    {{ $attributes->except(['href', 'active', 'icon', 'class'])->merge(['class' => $classes]) }}
>
    @if($icon)
        <x-halo::icon name="{{ $icon }}" size="sm" class="shrink-0" />
    @endif

    {{ $slot }}
</a>
