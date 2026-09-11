@props([
    'icon' => true,
])

@php
$classes = halo_merge_classes(
    'flex w-full items-center justify-between gap-2 px-4 py-3 text-left text-sm font-medium text-halo-foreground transition-colors hover:bg-halo-secondary active:bg-halo-secondary/80 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-halo-ring focus-visible:ring-inset',
    $attributes->get('class'),
);
@endphp

<button
    type="button"
    :id="$id('halo-collapsible-trigger')"
    @click="toggle()"
    :aria-expanded="open ? 'true' : 'false'"
    :aria-controls="$id('halo-collapsible-content')"
    {{ $attributes->except(['icon', 'class'])->merge(['class' => $classes]) }}
>
    {{ $slot }}

    @if($icon)
        {{-- ::style (double colon) is required here, not :style — <x-halo::icon> is a
             real Blade component tag, so a single-colon attribute is evaluated as PHP
             immediately (and "open" isn't a PHP variable here). The double colon tells
             Blade to emit it as a literal attribute instead, so Alpine binds it client-side. --}}
        <x-halo::icon
            name="chevron-down"
            size="sm"
            class="shrink-0 transition-transform"
            ::style="open ? 'transform: rotate(180deg)' : ''"
        />
    @endif
</button>
