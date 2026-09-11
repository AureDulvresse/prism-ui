@php
$classes = halo_merge_classes('flex h-full w-64 flex-col gap-6 overflow-y-auto border-r border-halo-border bg-halo-background p-4', $attributes->get('class'));
@endphp

<aside {{ $attributes->except(['class'])->merge(['class' => $classes]) }}>
    <nav aria-label="Sidebar" class="flex flex-1 flex-col gap-6">
        {{ $slot }}
    </nav>
</aside>
