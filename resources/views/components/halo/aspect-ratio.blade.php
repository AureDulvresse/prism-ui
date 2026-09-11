@props([
    'ratio' => '16/9',
])

@php
$classes = halo_merge_classes('block overflow-hidden', $attributes->get('class'));
@endphp

<div
    {{ $attributes->except(['ratio', 'class'])->merge(['class' => $classes]) }}
    style="aspect-ratio: {{ $ratio }}"
>
    {{ $slot }}
</div>
