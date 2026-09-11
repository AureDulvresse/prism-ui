@props([])

@php
$classes = halo_merge_classes('px-4 py-3 text-sm text-halo-foreground/80', $attributes->get('class'));
@endphp

<div
    :id="$id('halo-collapsible-content')"
    role="region"
    :aria-labelledby="$id('halo-collapsible-trigger')"
    x-show="open"
    x-transition
    {{ $attributes->except(['class'])->merge(['class' => $classes]) }}
>
    {{ $slot }}
</div>
