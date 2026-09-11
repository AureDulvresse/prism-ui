@php
$classes = halo_merge_classes(
    'py-6 text-center text-sm text-halo-foreground/50',
    $attributes->get('class'),
);
@endphp

{{-- Shown only once the live filter matches zero options anywhere in the panel. --}}
<div
    x-show="!hasResults()"
    {{ $attributes->except(['class'])->merge(['class' => $classes]) }}
>
    {{ $slot }}
</div>
