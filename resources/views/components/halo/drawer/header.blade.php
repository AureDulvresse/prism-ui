@php
$classes = halo_merge_classes('shrink-0 px-6 py-4 border-b border-halo-border font-semibold pr-12', $attributes->get('class'));
@endphp

<div {{ $attributes->except(['class'])->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
