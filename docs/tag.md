---
layout: default
title: Tag
permalink: /components/tag/
---

# Tag

`resources/views/components/halo/tag.blade.php`

## Props

| Prop | Type | Default | Notes |
|---|---|---|---|
| `variant` | `primary`\|`secondary`\|`success`\|`danger`\|`warning` | `secondary` | Config default: `halo.defaults.tag.variant` |
| `dismissible` | `bool` | `false` | Renders a trailing dismiss button that hides the tag |

Any extra attributes pass through to the `<span>` element.

## Examples

```blade
<x-halo::tag variant="success">Active</x-halo::tag>
<x-halo::tag variant="danger">Failed</x-halo::tag>
<x-halo::tag>Default</x-halo::tag>
```

### Dismissible

```blade
<x-halo::tag dismissible>Vue</x-halo::tag>
```

Clicking the `×` button hides the tag using local Alpine state (`x-data="{ show: true }"`) — there's no `Alpine.data()` factory to register, since this is a simple, self-contained toggle rather than shared/cross-cutting behavior.

## Accessibility

The dismiss button carries `aria-label="Remove"` since it renders only an icon. If a tag isn't dismissible, it's plain decorative text (a `<span>`) — add `role="status"` yourself via the component's pass-through attributes if you need it to announce as a live region.
