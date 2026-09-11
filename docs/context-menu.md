---
layout: default
title: Context Menu
permalink: /components/context-menu/
---

# Context Menu

`resources/views/components/halo/context-menu/{index,item}.blade.php`

## Props

`<x-halo::context-menu>`:

No props. Takes a `trigger` named slot (the area that responds to a right-click) and a default slot for the menu content.

`context-menu.item`:

| Prop | Type | Default | Notes |
|---|---|---|---|
| `href` | `string` or `null` | `null` | Renders an `<a>` when set, a `<button type="button">` otherwise |

## Examples

```blade
<x-halo::context-menu>
    <x-slot:trigger>
        <div class="rounded-halo border border-dashed border-halo-border p-12 text-center text-sm text-halo-foreground/60">
            Right-click anywhere in this area
        </div>
    </x-slot:trigger>

    <x-halo::context-menu.item>Cut</x-halo::context-menu.item>
    <x-halo::context-menu.item>Copy</x-halo::context-menu.item>
    <x-halo::context-menu.item>Paste</x-halo::context-menu.item>
    <x-halo::context-menu.item href="/trash">Delete</x-halo::context-menu.item>
</x-halo::context-menu>
```

## Accessibility

The panel has `role="menu"`, items have `role="menuitem"`. Opening the menu focuses the first item; `↓`/`↑` roam between items (per the [WAI-ARIA menu pattern](https://www.w3.org/WAI/ARIA/apg/patterns/menu-button/)). Closes — and returns focus to whatever was focused before the menu opened — on escape, on clicking outside, or on selecting an item.

## Implementation

Registered as `Alpine.data('haloContextMenu', ...)` in `resources/js/init.js`. Structurally it's Dropdown with a different open trigger: a `@contextmenu.prevent` listener on the wrapped trigger area calls `openMenu($event)`, which reads `$event.clientX`/`clientY` and positions the (`fixed`-positioned) panel there via a bound `:style="position"` object, instead of Dropdown's click-to-toggle/anchored-below approach. Position is clamped on open so the panel never renders past the right or bottom edge of the viewport. The panel is referenced via `x-ref="panel"` so `menuItems()` can query `[role="menuitem"]` descendants for the roving-focus logic without `context-menu.item` needing to register itself anywhere.
