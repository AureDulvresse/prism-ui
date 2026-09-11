@php
$classes = halo_merge_classes('flex-1 overflow-y-auto px-6 py-4', $attributes->get('class'));
@endphp

<div {{ $attributes->except(['class'])->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
