@props([
    'open' => false,
])

@php
$classes = halo_merge_classes('rounded-halo border border-halo-border overflow-hidden', $attributes->get('class'));
@endphp

{{--
    A single, standalone disclosure section — the same open/close mechanic as
    an Accordion item, but for exactly one section (no group, no `multiple`).
    `x-id` scopes Alpine's `$id()` magic so the trigger button and its panel
    below share matching ids without either sub-component needing to know
    about the other — see collapsible/trigger.blade.php and
    collapsible/content.blade.php.
--}}
<div
    x-data="haloCollapsible({{ $open ? 'true' : 'false' }})"
    x-id="['halo-collapsible-trigger', 'halo-collapsible-content']"
    {{ $attributes->except(['open', 'class'])->merge(['class' => $classes]) }}
>
    {{ $slot }}
</div>
