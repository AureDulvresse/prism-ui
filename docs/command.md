---
layout: default
title: Command
permalink: /components/command/
---

# Command

`resources/views/components/halo/command/{index,group,item,empty}.blade.php`

A Cmd+K-style command palette: an overlay dialog with a search input that filters its options live as you type. It reuses [Modal](../modal)'s open/close-by-event shell (backdrop, focus trap, `role="dialog"`) but there's no `name` — a page typically has a single command palette, opened from anywhere.

## Props

`<x-halo::command>` has no props of its own; any extra attribute lands on the dialog panel.

`command.group`:

| Prop | Type | Default | Notes |
|---|---|---|---|
| `label` | `string` or `null` | `null` | Section heading shown above its items |

`command.item`:

| Prop | Type | Default | Notes |
|---|---|---|---|
| `href` | `string` or `null` | `null` | Renders an `<a>` when set, a `<button type="button">` otherwise |
| `shortcut` | `string` or `null` | `null` | Rendered with `<x-halo::kbd>` at the end of the row |

`command.item` also accepts an optional `icon` named slot, shown before the label.

`command.empty` has no props of its own; its default slot is the placeholder message.

## Examples

```blade
<x-halo::button @click="$dispatch('open-command')">
    Search <x-halo::kbd>⌘K</x-halo::kbd>
</x-halo::button>

<x-halo::command>
    <x-halo::command.group label="Suggestions">
        <x-halo::command.item href="/dashboard" shortcut="⌘D">Dashboard</x-halo::command.item>
        <x-halo::command.item href="/settings" shortcut="⌘,">Settings</x-halo::command.item>
    </x-halo::command.group>

    <x-halo::command.group label="Actions">
        <x-halo::command.item @click="$dispatch('create-project')">New project</x-halo::command.item>
        <x-halo::command.item @click="$dispatch('logout')">Log out</x-halo::command.item>
    </x-halo::command.group>

    <x-halo::command.empty>No results found.</x-halo::command.empty>
</x-halo::command>
```

Open it from anywhere — a keyboard shortcut handler, a button, another component — with `$dispatch('open-command')`, and close it early with `$dispatch('close-command')`.

## Accessibility

The dialog has `role="dialog"` and `aria-modal="true"`; the search input has `role="combobox"` with `aria-autocomplete="list"` and `aria-controls` pointing at the `role="listbox"` options container. Each item has `role="option"`. Opening the palette focuses the search input; `↓`/`↑` move a highlighted "active" item among the currently visible options, Enter activates it, and Tab is trapped inside the panel (WAI-ARIA dialog pattern). Closes — and returns focus to whatever triggered it — on Escape, on clicking outside, or on selecting an item. A `command.group` hides itself once every item inside it has been filtered out; `command.empty` is shown only once the filter matches zero options anywhere in the panel.

## Implementation

Registered as `Alpine.data('haloCommand', ...)` in `resources/js/init.js`. It combines `haloModal`'s focus-trap/focus-restore mechanics with `haloDropdown`'s roving-highlight idea and `haloCombobox`'s `matches(text, query)` filter — except the "roving focus" is a virtual `activeIndex` over the currently visible `[role="option"]` elements rather than real DOM focus, since the search input needs to keep focus while the user types. Clicking an option (or calling `selectActive()` from Enter) closes the palette via the same `closeOnItemClick`-style listener `Dropdown` uses.
