@props([
    'label' => null,
])

@php
$classes = halo_merge_classes('py-1', $attributes->get('class'));
@endphp

{{--
    Hides itself once every item it contains has been filtered out, so a
    labeled section heading never lingers above an empty list while
    searching. hasVisibleItems($el) re-checks this group's own [role="option"]
    descendants against the live query on every keystroke (see haloCommand
    in resources/js/init.js).
--}}
<div
    x-show="hasVisibleItems($el)"
    role="group"
    @if($label) aria-label="{{ $label }}" @endif
    {{ $attributes->except(['label', 'class'])->merge(['class' => $classes]) }}
>
    @if($label)
        <p class="px-3 py-1.5 text-xs font-medium uppercase tracking-wide text-halo-foreground/50">{{ $label }}</p>
    @endif

    {{ $slot }}
</div>
