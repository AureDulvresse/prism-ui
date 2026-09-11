---
layout: default
title: Sidebar
permalink: /components/sidebar/
---

# Sidebar

`resources/views/components/halo/sidebar/{index,group,item}.blade.php`

No JavaScript — purely static markup.

A standalone, reusable navigation sidebar you can drop into any layout of
your own — not just [App Shell](/layout-app-shell/). Use it when you want
just the nav column (for example inside a custom layout, or a page that
doesn't need App Shell's opinionated topbar/off-canvas drawer structure).

## Props

`sidebar.group`:

| Prop | Type | Default | Notes |
|---|---|---|---|
| `label` | `string` or `null` | `null` | Small uppercase section heading rendered above the group's items; omitted entirely when `null` |

`sidebar.item`:

| Prop | Type | Default | Notes |
|---|---|---|---|
| `href` | `string` or `null` | `null` | The link's destination |
| `active` | `bool` | `false` | Applies a distinct visual state and `aria-current="page"` |
| `icon` | `string` or `null` | `null` | A Blade Icons name rendered via `<x-halo::icon>` before the label |

## Examples

```blade
<x-halo::sidebar>
    <x-halo::sidebar.item href="/dashboard" icon="home" active>Dashboard</x-halo::sidebar.item>

    <x-halo::sidebar.group label="Settings">
        <x-halo::sidebar.item href="/settings/profile" icon="user">Profile</x-halo::sidebar.item>
        <x-halo::sidebar.item href="/settings/security" icon="lock">Security</x-halo::sidebar.item>
    </x-halo::sidebar.group>
</x-halo::sidebar>
```

## Notes

- `sidebar.index` renders a fixed-width (`w-64` by default, overridable via the `class` prop) full-height flex column — pass your own `class` to change the width or spacing.
- `sidebar.group` is a static section heading, not a collapsible disclosure — see [Accordion](/components/accordion/) if you need expand/collapse behavior for grouped content.
- `resources/views/components/halo/layout/app-shell.blade.php` (the [App Shell](/layout-app-shell/) layout) ships its own inline off-canvas sidebar markup as part of its dashboard skeleton. That's a separate, self-contained implementation — this component is not used by App Shell and doesn't replace its sidebar slot. The two intentionally share some visual language (same border/background/spacing conventions); use this component instead of App Shell when you want a nav sidebar without also adopting App Shell's topbar and mobile drawer.

## Accessibility

The wrapper renders a `<nav aria-label="Sidebar">` so assistive technology can identify it as a distinct navigation landmark. The current page's item should be marked `active`, which adds `aria-current="page"` in place of a purely visual highlight.
