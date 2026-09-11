@php
$listId = uniqid('halo-command-list-');

$classes = halo_merge_classes(
    'relative flex w-full max-w-lg flex-col rounded-halo border border-halo-border bg-halo-background text-halo-foreground shadow-lg',
    $attributes->get('class'),
);
@endphp

{{--
    Command reuses Modal's exact open/close-by-event shell (backdrop, focus
    trap, role="dialog") but swaps Modal's static content for a search input
    plus a live-filtered, keyboard-navigable options list. The roving
    highlight is a virtual activeIndex over the currently visible options
    (see haloCommand in resources/js/init.js) rather than real DOM focus,
    since focus must stay in the input while typing. There's no `name`
    (unlike Modal/AlertDialog) — a page typically has a single command
    palette, opened from anywhere via $dispatch('open-command') and closed
    via $dispatch('close-command').
--}}
<div
    x-data="haloCommand()"
    x-show="open"
    x-cloak
    @keydown.escape.window="close()"
    class="fixed inset-0 z-50 flex items-start justify-center p-4 pt-[15vh]"
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
        x-transition
        @click.outside="close()"
        @click="closeOnItemClick($event)"
        @keydown.tab="trapFocus($event)"
        role="dialog"
        aria-modal="true"
        tabindex="-1"
        {{ $attributes->except(['class'])->merge(['class' => halo_merge_classes($classes, 'max-h-[calc(100vh-2rem)] overflow-hidden')]) }}
    >
        <div class="flex items-center gap-2 border-b border-halo-border px-4">
            <x-halo::icon name="search" size="sm" class="shrink-0 text-halo-foreground/50" />

            <input
                x-ref="input"
                type="text"
                role="combobox"
                aria-expanded="true"
                aria-autocomplete="list"
                aria-controls="{{ $listId }}"
                autocomplete="off"
                placeholder="Type a command or search..."
                x-model="query"
                @input="search()"
                @keydown.arrow-down.prevent="moveActive(1)"
                @keydown.arrow-up.prevent="moveActive(-1)"
                @keydown.enter.prevent="selectActive()"
                class="block w-full border-0 bg-transparent py-3 text-base text-halo-foreground placeholder:text-halo-foreground/50 focus:outline-none focus:ring-0"
            />
        </div>

        <div id="{{ $listId }}" role="listbox" class="max-h-80 overflow-y-auto p-2">
            {{ $slot }}
        </div>
    </div>
</div>
