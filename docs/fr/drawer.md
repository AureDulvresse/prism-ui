---
layout: default
title: Drawer
permalink: /fr/components/drawer/
lang: fr
---

# Drawer

`resources/views/components/halo/drawer/{index,header,body,footer}.blade.php`

Ouvert et fermé par son nom, depuis n'importe où — pas de prop `:open` à garder synchronisée entre un déclencheur et le tiroir. Structurellement un frère de [Modal](/fr/components/modal/), mais le panneau glisse depuis un bord de l'écran au lieu d'apparaître en fondu au centre.

## Props

`<x-halo::drawer>` :

| Prop | Type | Défaut | Notes |
|---|---|---|---|
| `name` | `string` | *requis* | Identifie ce tiroir pour les événements `open-drawer`/`close-drawer` |
| `side` | `left`\|`right`\|`top`\|`bottom` | `right` | Bord de l'écran depuis lequel le panneau glisse |

`drawer.header`, `drawer.body`, `drawer.footer` n'ont pas de props au-delà des attributs passthrough.

## Exemples

```blade
<x-halo::button @click="$dispatch('open-drawer', 'edit-profile')">
    Modifier le profil
</x-halo::button>

<x-halo::drawer name="edit-profile" side="right">
    <x-halo::drawer.header>Modifier le profil</x-halo::drawer.header>

    <x-halo::drawer.body>
        ...
    </x-halo::drawer.body>

    <x-halo::drawer.footer>
        <x-halo::button variant="outline" @click="$dispatch('close-drawer')">Annuler</x-halo::button>
        <x-halo::button>Enregistrer</x-halo::button>
    </x-halo::drawer.footer>
</x-halo::drawer>
```

Une feuille de filtres en bas d'écran, par exemple, ne change que `side` :

```blade
<x-halo::drawer name="filters" side="bottom">
    <x-halo::drawer.header>Filtres</x-halo::drawer.header>

    <x-halo::drawer.body>
        ...
    </x-halo::drawer.body>
</x-halo::drawer>
```

Émettre `close-drawer` sans nom (ou avec un nom correspondant) ferme le ou les tiroirs correspondants. Échap et un clic sur le fond le ferment aussi.

## Accessibilité

Rend `role="dialog"` et `aria-modal="true"`. Le bouton de fermeture dans le coin a `aria-label="Close"`. Ouvrir le tiroir déplace le focus vers le premier élément focusable à l'intérieur (ou le panneau lui-même à défaut) ; `Tab`/`Shift+Tab` boucle à l'intérieur du panneau au lieu de s'échapper vers le reste de la page (un piège à focus, selon le [pattern WAI-ARIA dialog](https://www.w3.org/WAI/ARIA/apg/patterns/dialog-modal/)) ; la fermeture redonne le focus à ce qui l'avait avant l'ouverture du tiroir.

Les tiroirs `left`/`right` occupent toute la hauteur du viewport ; les tiroirs `top`/`bottom` sont plafonnés à `80vh`. Dans les deux cas, l'en-tête et le pied restent fixes tandis que le corps (`flex-1 overflow-y-auto`) défile en interne, afin qu'un contenu long ne pousse jamais les actions du pied hors écran.

## Implémentation

Enregistré comme `Alpine.data('haloDrawer', ...)` dans `resources/js/init.js`, à l'écoute sur `window` des événements custom `open-drawer`/`close-drawer` correspondant à son `name`. Le panneau est référencé via `x-ref="panel"` pour que la logique de piège à focus (`focusFirst()`, `trapFocus()`) puisse interroger ses descendants focusables sans avoir besoin de connaître le contenu du tiroir à l'avance. Le glissement est réalisé avec les classes `x-transition:enter`/`x-transition:leave` d'Alpine, qui translatent le panneau hors écran selon l'axe utilisé par son `side`.
