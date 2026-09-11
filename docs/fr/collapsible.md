---
layout: default
title: Collapsible
permalink: /fr/components/collapsible/
lang: fr
---

# Collapsible

`resources/views/components/halo/collapsible/{index,trigger,content}.blade.php`

## Props

`<x-halo::collapsible>` :

| Prop | Type | Défaut | Notes |
|---|---|---|---|
| `open` | `bool` | `false` | Si la section démarre développée |

`collapsible.trigger` :

| Prop | Type | Défaut | Notes |
|---|---|---|---|
| `icon` | `bool` | `true` | Affiche l'icône chevron qui pivote selon l'état ouvert |

`collapsible.content` n'a pas de props au-delà des attributs passthrough.

## Exemples

```blade
<x-halo::collapsible>
    <x-halo::collapsible.trigger>Qu'est-ce que HaloUI ?</x-halo::collapsible.trigger>
    <x-halo::collapsible.content>
        Une bibliothèque de composants Blade pour Laravel.
    </x-halo::collapsible.content>
</x-halo::collapsible>

<x-halo::collapsible open>
    {{-- démarre développé --}}
    <x-halo::collapsible.trigger :icon="false">Détails</x-halo::collapsible.trigger>
    <x-halo::collapsible.content>
        Développé dès le premier rendu.
    </x-halo::collapsible.content>
</x-halo::collapsible>
```

## Accessibilité

Le bouton trigger a `aria-expanded` reflétant l'état ouvert et `aria-controls` pointant vers l'`id` du panneau de contenu. Le panneau de contenu a `role="region"` et `aria-labelledby` pointant vers l'`id` du trigger. Les deux ids sont générés par la méthode magique `$id()` d'Alpine, dans le scope défini par `x-id` sur `collapsible/index.blade.php` — c'est ce qui permet au trigger et au contenu de rester liés sans que l'un des sous-composants ait besoin de connaître l'autre. L'icône chevron pivote de 180° à l'ouverture (un indice visuel uniquement — `aria-expanded` porte l'état réel).

## Implémentation

Enregistré comme `Alpine.data('haloCollapsible', ...)` dans `resources/js/init.js`. C'est le même mécanisme d'ouverture/fermeture qu'un `accordion.item`, simplifié à un simple booléen (`open`) et une méthode `toggle()` — pas d'état de groupe, pas de concept `multiple`, puisqu'un Collapsible est toujours autonome.
