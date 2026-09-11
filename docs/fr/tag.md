---
layout: default
title: Tag
permalink: /fr/components/tag/
lang: fr
---

# Tag

`resources/views/components/halo/tag.blade.php`

## Props

| Prop | Type | Défaut | Notes |
|---|---|---|---|
| `variant` | `primary`\|`secondary`\|`success`\|`danger`\|`warning` | `secondary` | Défaut config : `halo.defaults.tag.variant` |
| `dismissible` | `bool` | `false` | Affiche un bouton de suppression qui masque le tag |

Tout attribut supplémentaire passe tel quel sur l'élément `<span>`.

## Exemples

```blade
<x-halo::tag variant="success">Actif</x-halo::tag>
<x-halo::tag variant="danger">Échec</x-halo::tag>
<x-halo::tag>Par défaut</x-halo::tag>
```

### Amovible

```blade
<x-halo::tag dismissible>Vue</x-halo::tag>
```

Cliquer sur le bouton `×` masque le tag grâce à un état Alpine local (`x-data="{ show: true }"`) — il n'y a pas de factory `Alpine.data()` à enregistrer, puisqu'il s'agit d'un simple état local plutôt que d'un comportement partagé/transversal.

## Accessibilité

Le bouton de suppression porte `aria-label="Remove"` car il n'affiche qu'une icône. Si un tag n'est pas amovible, c'est du texte décoratif par défaut (un `<span>`) — ajoute toi-même `role="status"` via les attributs passthrough du composant si tu as besoin qu'il s'annonce comme une région live.
