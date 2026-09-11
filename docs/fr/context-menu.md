---
layout: default
title: Context Menu
permalink: /fr/components/context-menu/
lang: fr
---

# Context Menu

`resources/views/components/halo/context-menu/{index,item}.blade.php`

## Props

`<x-halo::context-menu>` :

Aucune prop. Prend un slot nommé `trigger` (la zone qui répond à un clic droit) et un slot par défaut pour le contenu du menu.

`context-menu.item` :

| Prop | Type | Défaut | Notes |
|---|---|---|---|
| `href` | `string` ou `null` | `null` | Rend un `<a>` si défini, un `<button type="button">` sinon |

## Exemples

```blade
<x-halo::context-menu>
    <x-slot:trigger>
        <div class="rounded-halo border border-dashed border-halo-border p-12 text-center text-sm text-halo-foreground/60">
            Clic droit n'importe où dans cette zone
        </div>
    </x-slot:trigger>

    <x-halo::context-menu.item>Couper</x-halo::context-menu.item>
    <x-halo::context-menu.item>Copier</x-halo::context-menu.item>
    <x-halo::context-menu.item>Coller</x-halo::context-menu.item>
    <x-halo::context-menu.item href="/trash">Supprimer</x-halo::context-menu.item>
</x-halo::context-menu>
```

## Accessibilité

Le panneau a `role="menu"`, les éléments ont `role="menuitem"`. Ouvrir le menu donne le focus au premier élément ; `↓`/`↑` naviguent entre les éléments (selon le [pattern WAI-ARIA menu](https://www.w3.org/WAI/ARIA/apg/patterns/menu-button/)). Se ferme — et redonne le focus à l'élément qui avait le focus avant l'ouverture du menu — sur Échap, un clic à l'extérieur, ou la sélection d'un élément.

## Implémentation

Enregistré comme `Alpine.data('haloContextMenu', ...)` dans `resources/js/init.js`. Structurellement, il s'agit du Dropdown avec un déclencheur d'ouverture différent : un écouteur `@contextmenu.prevent` sur la zone de déclenchement encapsulée appelle `openMenu($event)`, qui lit `$event.clientX`/`clientY` et positionne le panneau (en position `fixed`) à cet endroit via un objet lié `:style="position"`, au lieu de l'approche de Dropdown basée sur un clic pour basculer/ancré sous le déclencheur. La position est ajustée à l'ouverture pour que le panneau ne s'affiche jamais au-delà du bord droit ou inférieur de la fenêtre. Le panneau est référencé via `x-ref="panel"` pour que `menuItems()` puisse interroger les descendants `[role="menuitem"]` pour la logique de focus flottant, sans que `context-menu.item` ait besoin de s'enregistrer nulle part.
