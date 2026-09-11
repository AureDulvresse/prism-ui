---
layout: default
title: Aspect Ratio
permalink: /fr/components/aspect-ratio/
lang: fr
---

# Aspect Ratio

`resources/views/components/halo/aspect-ratio.blade.php`

Un primitif de mise en page qui contraint son contenu à un rapport largeur/hauteur donné, afin que les images, iframes et autres contenus intégrés ne provoquent jamais de décalage de mise en page pendant leur chargement.

## Props

| Prop | Type | Défaut | Notes |
|---|---|---|---|
| `ratio` | `string` | `16/9` | Un rapport `largeur/hauteur`, par ex. `16/9`, `1/1`, `4/3`. Appliqué via la propriété CSS `aspect-ratio`. |

Le conteneur porte aussi `overflow-hidden`, si bien que tout contenu inséré plus grand que la boîte est rogné à sa taille. Donnez au contenu inséré les classes `w-full h-full object-cover` (pour les images) afin qu'il remplisse la boîte au lieu de conserver sa taille intrinsèque.

## Exemples

### Image

```blade
<x-halo::aspect-ratio ratio="16/9">
    <img src="/img/landscape.jpg" alt="Landscape" class="w-full h-full object-cover" />
</x-halo::aspect-ratio>
```

### Vidéo intégrée (iframe)

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

### Rapport carré

```blade
<x-halo::aspect-ratio ratio="1/1" class="rounded-lg">
    <img src="/img/avatar-large.jpg" alt="Avatar" class="w-full h-full object-cover" />
</x-halo::aspect-ratio>
```

Sans `ratio` fourni, la boîte utilise `16/9` par défaut.
