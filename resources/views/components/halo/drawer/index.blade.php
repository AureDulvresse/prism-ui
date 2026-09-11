@props([
    'name',
    'side' => 'right',
])

@php
$sides = [
    'right' => [
        'position' => 'inset-y-0 right-0 h-full w-full max-w-sm border-l',
        'closed' => 'translate-x-full',
        'open' => 'translate-x-0',
    ],
    'left' => [
        'position' => 'inset-y-0 left-0 h-full w-full max-w-sm border-r',
        'closed' => '-translate-x-full',
        'open' => 'translate-x-0',
    ],
    'top' => [
        'position' => 'inset-x-0 top-0 w-full max-h-[80vh] border-b',
        'closed' => '-translate-y-full',
        'open' => 'translate-y-0',
    ],
    'bottom' => [
        'position' => 'inset-x-0 bottom-0 w-full max-h-[80vh] border-t',
        'closed' => 'translate-y-full',
        'open' => 'translate-y-0',
    ],
];

$config = $sides[$side] ?? $sides['right'];

$classes = halo_merge_classes(
    'fixed flex flex-col border-halo-border bg-halo-background text-halo-foreground shadow-lg',
    $config['position'],
    $attributes->get('class'),
);
@endphp

{{--
    Drawer is Modal's edge-anchored sibling: the same open-by-name /
    focus-trap / focus-restore mechanics (haloDrawer mirrors haloModal,
    keyed on open-drawer/close-drawer instead), but the panel is pinned to
    a screen edge (`side`) and slides in/out along that axis via
    x-transition's enter/leave classes instead of fading in centered.
    Header/footer stay pinned via the panel's flex-col layout while the
    body (flex-1 overflow-y-auto) scrolls internally, since a left/right
    drawer spans the full viewport height.
--}}
<div
    x-data="haloDrawer('{{ $name }}')"
    x-show="open"
    x-cloak
    @keydown.escape.window="close()"
    class="fixed inset-0 z-50"
    style="display: none;"
>
    <div
        x-show="open"
        x-transition.opacity
        class="absolute inset-0 bg-halo-foreground/50"
        @click="close()"
        aria-hidden="true"
    ></div>

    <div
        x-ref="panel"
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="{{ $config['closed'] }}"
        x-transition:enter-end="{{ $config['open'] }}"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="{{ $config['open'] }}"
        x-transition:leave-end="{{ $config['closed'] }}"
        @click.outside="close()"
        @keydown.tab="trapFocus($event)"
        role="dialog"
        aria-modal="true"
        tabindex="-1"
        {{ $attributes->except(['name', 'side', 'class'])->merge(['class' => $classes]) }}
    >
        <button
            type="button"
            @click="close()"
            class="absolute right-3 top-3 rounded-halo p-2 text-halo-foreground/50 transition-colors hover:text-halo-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-halo-ring"
            aria-label="Close"
        >
            <x-halo::icon name="x" size="sm" />
        </button>

        {{ $slot }}
    </div>
</div>
