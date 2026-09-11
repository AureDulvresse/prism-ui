---
layout: default
title: Drawer
permalink: /components/drawer/
---

# Drawer

`resources/views/components/halo/drawer/{index,header,body,footer}.blade.php`

Opened and closed by name, from anywhere — no `:open` prop to keep in sync between a trigger and the drawer. Structurally a sibling of [Modal](/components/modal/), but the panel slides in from a screen edge instead of fading in centered.

## Props

`<x-halo::drawer>`:

| Prop | Type | Default | Notes |
|---|---|---|---|
| `name` | `string` | *required* | Identifies this drawer for `open-drawer`/`close-drawer` events |
| `side` | `left`\|`right`\|`top`\|`bottom` | `right` | Screen edge the panel slides in from |

`drawer.header`, `drawer.body`, `drawer.footer` take no props beyond pass-through attributes.

## Examples

```blade
<x-halo::button @click="$dispatch('open-drawer', 'edit-profile')">
    Edit profile
</x-halo::button>

<x-halo::drawer name="edit-profile" side="right">
    <x-halo::drawer.header>Edit profile</x-halo::drawer.header>

    <x-halo::drawer.body>
        ...
    </x-halo::drawer.body>

    <x-halo::drawer.footer>
        <x-halo::button variant="outline" @click="$dispatch('close-drawer')">Cancel</x-halo::button>
        <x-halo::button>Save</x-halo::button>
    </x-halo::drawer.footer>
</x-halo::drawer>
```

A bottom sheet for filters, for example, just changes `side`:

```blade
<x-halo::drawer name="filters" side="bottom">
    <x-halo::drawer.header>Filters</x-halo::drawer.header>

    <x-halo::drawer.body>
        ...
    </x-halo::drawer.body>
</x-halo::drawer>
```

Dispatching `close-drawer` with no name (or a matching name) closes the matching drawer(s). Escape and clicking the backdrop close it too.

## Accessibility

Renders `role="dialog"` and `aria-modal="true"`. The close button in the corner has `aria-label="Close"`. Opening the drawer moves focus to the first focusable element inside it (falling back to the panel itself); `Tab`/`Shift+Tab` cycle within the panel instead of escaping to the rest of the page (a focus trap, per the [WAI-ARIA dialog pattern](https://www.w3.org/WAI/ARIA/apg/patterns/dialog-modal/)); closing returns focus to whatever had it before the drawer opened.

`left`/`right` drawers span the full viewport height; `top`/`bottom` drawers cap at `80vh`. Either way, the header and footer stay pinned in place while the body (`flex-1 overflow-y-auto`) scrolls internally, so long content never pushes the footer's actions off-screen.

## Implementation

Registered as `Alpine.data('haloDrawer', ...)` in `resources/js/init.js`, listening on the `window` for `open-drawer`/`close-drawer` custom events matching its `name`. The panel is referenced via `x-ref="panel"` so the focus-trap logic (`focusFirst()`, `trapFocus()`) can query its focusable descendants without needing to know the drawer's content ahead of time. The slide is done with Alpine's `x-transition:enter`/`x-transition:leave` classes, translating the panel off-screen along whichever axis its `side` uses.
