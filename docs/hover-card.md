---
layout: default
title: Hover Card
permalink: /components/hover-card/
---

# Hover Card

`resources/views/components/halo/hover-card/index.blade.php`

A rich content panel shown after briefly hovering or focusing its trigger — a preview card for a user, link, or record. Like [Tooltip](../tooltip), it opens on hover/focus and wires `aria-describedby` automatically, but its `trigger` is a named slot (as in [Popover](../popover)) since it holds arbitrary rich content rather than a short text hint, and its content panel is a [Popover](../popover)-style anchored panel rather than plain text.

## Props

| Prop | Type | Default | Notes |
|---|---|---|---|
| `position` | `top`\|`bottom`\|`left`\|`right` | `bottom` (config default: `halo.defaults.hover-card.position`) | Panel position relative to the trigger |

Takes a `trigger` named slot (the hoverable element) and a default slot for the card content.

## Examples

```blade
<x-halo::hover-card position="bottom">
    <x-slot:trigger>
        <a href="/u/jdoe" class="font-medium text-halo-primary">@jdoe</a>
    </x-slot:trigger>

    <div class="flex gap-3">
        <x-halo::avatar src="/avatars/jdoe.png" alt="Jane Doe" />

        <div>
            <p class="font-semibold">Jane Doe</p>
            <p class="text-sm text-halo-muted">Joined March 2022</p>
        </div>
    </div>
</x-halo::hover-card>
```

## Accessibility

Shown on `mouseenter`/`focusin` of the wrapper, hidden on `mouseleave`/`focusout` — reachable by keyboard, not just the mouse, per the [WAI-ARIA tooltip pattern](https://www.w3.org/WAI/ARIA/apg/patterns/tooltip/) that Hover Card extends for rich content. Opening and closing are debounced (an opening delay so a quick mouse pass across the trigger doesn't flicker the card open, and a closing delay so moving from the trigger into the card content doesn't immediately close it). On mount, `aria-describedby` is set on the trigger's first element automatically, so assistive tech announces the card content alongside the trigger's own label.

## Implementation

Registered as `Alpine.data('haloHoverCard', ...)` in `resources/js/init.js`. Combines Tooltip's hover/focus show-hide and `aria-describedby` id-wiring with Popover's anchored rich-content panel — no focus trap, since a hover card is dismissible simply by moving the pointer away.
