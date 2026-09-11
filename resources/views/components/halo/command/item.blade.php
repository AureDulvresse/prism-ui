@props([
    'href' => null,
    'shortcut' => null,
])

@php
$classes = halo_merge_classes(
    'flex w-full items-center gap-3 rounded-halo px-3 py-2 text-left text-sm text-halo-foreground transition-colors hover:bg-halo-secondary',
    $attributes->get('class'),
);
@endphp

{{--
    A single selectable row. Filtered live via x-show against the search
    query (same matches() substring check as haloCombobox), and highlighted
    via a virtual isActive($el) check rather than real focus — the search
    input keeps DOM focus throughout, so arrow keys/hover only move a
    tracked activeIndex (see haloCommand in resources/js/init.js).
    tabindex="-1" keeps it out of the natural Tab order (like select.item)
    since it's never given real focus; Enter in the input calls
    selectActive(), which programmatically clicks the active item.
--}}
@if($href)
    <a
        href="{{ $href }}"
        role="option"
        tabindex="-1"
        x-show="matches($el.textContent, query)"
        @mouseenter="setActive($el)"
        ::aria-selected="isActive($el).toString()"
        ::class="isActive($el) ? 'bg-halo-secondary' : ''"
        {{ $attributes->except(['href', 'shortcut', 'icon', 'class'])->merge(['class' => $classes]) }}
    >
        @isset($icon)
            <span class="shrink-0 text-halo-foreground/50">{{ $icon }}</span>
        @endisset

        <span class="flex-1 truncate">{{ $slot }}</span>

        @if($shortcut)
            <x-halo::kbd>{{ $shortcut }}</x-halo::kbd>
        @endif
    </a>
@else
    <button
        type="button"
        role="option"
        tabindex="-1"
        x-show="matches($el.textContent, query)"
        @mouseenter="setActive($el)"
        ::aria-selected="isActive($el).toString()"
        ::class="isActive($el) ? 'bg-halo-secondary' : ''"
        {{ $attributes->except(['href', 'shortcut', 'icon', 'class'])->merge(['class' => $classes]) }}
    >
        @isset($icon)
            <span class="shrink-0 text-halo-foreground/50">{{ $icon }}</span>
        @endisset

        <span class="flex-1 truncate">{{ $slot }}</span>

        @if($shortcut)
            <x-halo::kbd>{{ $shortcut }}</x-halo::kbd>
        @endif
    </button>
@endif
