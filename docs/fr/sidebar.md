---
layout: default
title: Sidebar
permalink: /fr/components/sidebar/
lang: fr
---

# Sidebar

`resources/views/components/halo/sidebar/{index,group,item}.blade.php`

Pas de JavaScript — du balisage purement statique.

Une barre latérale de navigation autonome et réutilisable, à intégrer dans
n'importe quelle mise en page de votre choix — pas seulement [App Shell](/layout-app-shell/).
Utilisez-la lorsque vous voulez uniquement la colonne de navigation (par
exemple dans une mise en page personnalisée, ou une page qui n'a pas besoin
de la structure topbar/tiroir hors-écran imposée par App Shell).

## Props

`sidebar.group` :

| Prop | Type | Défaut | Notes |
|---|---|---|---|
| `label` | `string` ou `null` | `null` | Petit intitulé de section en majuscules affiché au-dessus des items du groupe ; totalement omis lorsque `null` |

`sidebar.item` :

| Prop | Type | Défaut | Notes |
|---|---|---|---|
| `href` | `string` ou `null` | `null` | La destination du lien |
| `active` | `bool` | `false` | Applique un état visuel distinct et `aria-current="page"` |
| `icon` | `string` ou `null` | `null` | Un nom Blade Icons rendu via `<x-halo::icon>` avant le libellé |

## Exemples

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

- `sidebar.index` rend une colonne flexible pleine hauteur de largeur fixe (`w-64` par défaut, modifiable via la prop `class`) — passez votre propre `class` pour changer la largeur ou les espacements.
- `sidebar.group` est un intitulé de section statique, pas un panneau repliable — voir [Accordion](/fr/components/accordion/) si vous avez besoin d'un comportement d'ouverture/fermeture pour du contenu groupé.
- `resources/views/components/halo/layout/app-shell.blade.php` (la mise en page [App Shell](/layout-app-shell/)) embarque son propre balisage de barre latérale hors-écran, intégré à son squelette de tableau de bord. Il s'agit d'une implémentation séparée et autonome — ce composant n'est pas utilisé par App Shell et ne remplace pas son slot de sidebar. Les deux partagent volontairement certains éléments visuels (mêmes conventions de bordure/fond/espacement) ; utilisez ce composant à la place d'App Shell lorsque vous voulez une barre de navigation sans adopter aussi sa topbar et son tiroir mobile.

## Accessibilité

Le conteneur rend un `<nav aria-label="Sidebar">` afin que les technologies d'assistance puissent l'identifier comme un repère de navigation distinct. L'item correspondant à la page courante doit être marqué `active`, ce qui ajoute `aria-current="page"` plutôt qu'une simple mise en évidence visuelle.
