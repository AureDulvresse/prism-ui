@php
$classes = halo_merge_classes('shrink-0 px-6 py-4 border-t border-halo-border flex items-center justify-end gap-2', $attributes->get('class'));
@endphp

<div {{ $attributes->except(['class'])->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
