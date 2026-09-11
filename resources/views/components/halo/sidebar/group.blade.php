@props([
    'label' => null,
])

@php
$classes = halo_merge_classes('flex flex-col gap-1', $attributes->get('class'));
@endphp

<div {{ $attributes->except(['label', 'class'])->merge(['class' => $classes]) }}>
    @if($label)
        <p class="px-3 pb-1 pt-2 text-xs font-semibold uppercase tracking-wide text-halo-foreground/50">{{ $label }}</p>
    @endif

    <div class="flex flex-col gap-1">
        {{ $slot }}
    </div>
</div>
