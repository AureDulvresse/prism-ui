---
layout: default
title: Hover Card
permalink: /fr/components/hover-card/
lang: fr
---

# Hover Card

`resources/views/components/halo/hover-card/index.blade.php`

Un panneau de contenu riche affiché après un bref survol ou focus de son déclencheur — une carte d'aperçu pour un utilisateur, un lien ou un enregistrement. Comme [Tooltip](../tooltip), il s'ouvre au survol/focus et câble automatiquement `aria-describedby`, mais son `trigger` est un slot nommé (comme dans [Popover](../popover)) puisqu'il contient du contenu riche arbitraire plutôt qu'un court indice textuel, et son panneau de contenu est un panneau ancré de type [Popover](../popover) plutôt que du texte brut.

## Props

| Prop | Type | Défaut | Notes |
|---|---|---|---|
| `position` | `top`\|`bottom`\|`left`\|`right` | `bottom` (défaut config : `halo.defaults.hover-card.position`) | Position du panneau par rapport au déclencheur |

Prend un slot nommé `trigger` (l'élément survolable) et un slot par défaut pour le contenu de la carte.

## Exemples

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

## Accessibilité

Affiché sur `mouseenter`/`focusin` du conteneur, masqué sur `mouseleave`/`focusout` — donc atteignable au clavier, pas seulement à la souris, selon le [pattern WAI-ARIA tooltip](https://www.w3.org/WAI/ARIA/apg/patterns/tooltip/) que Hover Card étend pour du contenu riche. L'ouverture et la fermeture sont temporisées (un délai d'ouverture pour qu'un simple passage rapide de la souris sur le déclencheur ne fasse pas clignoter la carte, et un délai de fermeture pour que le passage du déclencheur vers le contenu de la carte ne la ferme pas immédiatement). Au montage, `aria-describedby` est posé automatiquement sur le premier élément du déclencheur, pour que les technologies d'assistance annoncent le contenu de la carte en plus du label propre du déclencheur.

## Implémentation

Enregistré comme `Alpine.data('haloHoverCard', ...)` dans `resources/js/init.js`. Combine l'affichage/masquage au survol/focus de Tooltip et le câblage d'id `aria-describedby`, avec le panneau ancré à contenu riche de Popover — pas de piège à focus, puisqu'une hover card se referme simplement en éloignant le pointeur.
