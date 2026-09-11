@php
$classes = halo_merge_classes(
    'fixed z-40 min-w-[10rem] max-h-80 overflow-y-auto rounded-halo border border-halo-border bg-halo-background text-halo-foreground shadow-lg py-1',
    $attributes->get('class'),
);
@endphp

<div x-data="haloContextMenu()" @keydown.escape="close()">
    <div @contextmenu.prevent="openMenu($event)">
        {{ $trigger ?? '' }}
    </div>

    <div
        x-ref="panel"
        x-show="open"
        x-cloak
        x-transition
        :style="position"
        @click.outside="close()"
        @click="closeOnItemClick($event)"
        @keydown.down.prevent="focusNext()"
        @keydown.up.prevent="focusPrevious()"
        role="menu"
        {{ $attributes->except(['class'])->merge(['class' => $classes]) }}
    >
        {{ $slot }}
    </div>
</div>
