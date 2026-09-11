---
layout: default
title: Aspect Ratio
permalink: /components/aspect-ratio/
---

# Aspect Ratio

`resources/views/components/halo/aspect-ratio.blade.php`

A layout primitive that constrains its content to a given width/height ratio, so images, iframes, and other embeds never cause layout shift while they load.

## Props

| Prop | Type | Default | Notes |
|---|---|---|---|
| `ratio` | `string` | `16/9` | A `width/height` ratio, e.g. `16/9`, `1/1`, `4/3`. Applied via the CSS `aspect-ratio` property. |

The wrapper also carries `overflow-hidden`, so any slotted content larger than the box gets clipped to it. Give slotted content `w-full h-full object-cover` (for images) so it fills the box instead of keeping its own intrinsic size.

## Examples

### Image

```blade
<x-halo::aspect-ratio ratio="16/9">
    <img src="/img/landscape.jpg" alt="Landscape" class="w-full h-full object-cover" />
</x-halo::aspect-ratio>
```

### Embedded video (iframe)

```blade
<x-halo::aspect-ratio ratio="16/9">
    <iframe
        src="https://www.youtube.com/embed/dQw4w9WgXcQ"
        title="Embedded video"
        class="w-full h-full"
        allowfullscreen
    ></iframe>
</x-halo::aspect-ratio>
```

### Square ratio

```blade
<x-halo::aspect-ratio ratio="1/1" class="rounded-lg">
    <img src="/img/avatar-large.jpg" alt="Avatar" class="w-full h-full object-cover" />
</x-halo::aspect-ratio>
```

When no `ratio` is given, the box defaults to `16/9`.
