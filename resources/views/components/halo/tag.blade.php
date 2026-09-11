@props([
    'variant' => halo_default('tag', 'variant', 'secondary'),
    'dismissible' => false,
])

@php
$classes = halo_variants([
    'base' => 'inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium border',
    'variants' => [
        'variant' => [
            'primary' => 'bg-halo-primary/10 text-halo-primary border-halo-primary/20',
            'secondary' => 'bg-halo-secondary text-halo-secondary-foreground border-transparent',
            'success' => 'bg-halo-success/10 text-halo-success border-halo-success/20',
            'danger' => 'bg-halo-danger/10 text-halo-danger border-halo-danger/20',
            'warning' => 'bg-halo-warning/10 text-halo-warning border-halo-warning/20',
        ],
    ],
    'defaults' => ['variant' => 'secondary'],
], compact('variant'), $attributes->get('class'));
@endphp

<span
    @if($dismissible) x-data="{ show: true }" x-show="show" x-transition @endif
    {{ $attributes->except(['variant', 'dismissible', 'class'])->merge(['class' => $classes]) }}
>
    {{ $slot }}

    @if($dismissible)
        <button
            type="button"
            @click="show = false"
            class="-mr-0.5 shrink-0 rounded-full p-0.5 text-current/60 transition-colors hover:text-current focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-halo-ring"
            aria-label="Remove"
        >
            <x-halo::icon name="x" size="xs" />
        </button>
    @endif
</span>
