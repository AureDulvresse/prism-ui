---
layout: default
title: Command
permalink: /fr/components/command/
lang: fr
---

# Command

`resources/views/components/halo/command/{index,group,item,empty}.blade.php`

Une palette de commandes façon Cmd+K : une boîte de dialogue en surcouche avec un champ de recherche qui filtre ses options en direct au fur et à mesure de la saisie. Elle réutilise la même mécanique d'ouverture/fermeture par événement que [Modal](../modal) (fond obscurci, piège à focus, `role="dialog"`), mais sans `name` — une page a généralement une seule palette de commandes, ouverte depuis n'importe où.

## Props

`<x-halo::command>` n'a pas de props propres ; tout attribut supplémentaire atterrit sur le panneau de la boîte de dialogue.

`command.group` :

| Prop | Type | Défaut | Notes |
|---|---|---|---|
| `label` | `string` ou `null` | `null` | Titre de section affiché au-dessus de ses éléments |

`command.item` :

| Prop | Type | Défaut | Notes |
|---|---|---|---|
| `href` | `string` ou `null` | `null` | Rend un `<a>` si défini, un `<button type="button">` sinon |
| `shortcut` | `string` ou `null` | `null` | Rendu avec `<x-halo::kbd>` à la fin de la ligne |

`command.item` accepte également un slot nommé `icon` optionnel, affiché avant le libellé.

`command.empty` n'a pas de props propres ; son slot par défaut est le message de remplacement.

## Exemples

```blade
<x-halo::button @click="$dispatch('open-command')">
    Rechercher <x-halo::kbd>⌘K</x-halo::kbd>
</x-halo::button>

<x-halo::command>
    <x-halo::command.group label="Suggestions">
        <x-halo::command.item href="/dashboard" shortcut="⌘D">Tableau de bord</x-halo::command.item>
        <x-halo::command.item href="/settings" shortcut="⌘,">Paramètres</x-halo::command.item>
    </x-halo::command.group>

    <x-halo::command.group label="Actions">
        <x-halo::command.item @click="$dispatch('create-project')">Nouveau projet</x-halo::command.item>
        <x-halo::command.item @click="$dispatch('logout')">Se déconnecter</x-halo::command.item>
    </x-halo::command.group>

    <x-halo::command.empty>Aucun résultat trouvé.</x-halo::command.empty>
</x-halo::command>
```

Ouvre-la depuis n'importe où — un gestionnaire de raccourci clavier, un bouton, un autre composant — avec `$dispatch('open-command')`, et ferme-la de manière anticipée avec `$dispatch('close-command')`.

## Accessibilité

La boîte de dialogue a `role="dialog"` et `aria-modal="true"` ; le champ de recherche a `role="combobox"` avec `aria-autocomplete="list"` et `aria-controls` pointant vers le conteneur d'options `role="listbox"`. Chaque élément a `role="option"`. Ouvrir la palette donne le focus au champ de recherche ; `↓`/`↑` déplacent un élément « actif » surligné parmi les options actuellement visibles, Entrée l'active, et Tab est piégé à l'intérieur du panneau (pattern WAI-ARIA dialog). Se ferme — et redonne le focus à ce qui l'a déclenchée — sur Échap, un clic à l'extérieur, ou la sélection d'un élément. Un `command.group` se masque une fois que tous ses éléments ont été filtrés ; `command.empty` n'est affiché qu'une fois que le filtre ne correspond à aucune option dans tout le panneau.

## Implémentation

Enregistré comme `Alpine.data('haloCommand', ...)` dans `resources/js/init.js`. Il combine la mécanique de piège/restauration du focus de `haloModal` avec l'idée de surlignage flottant de `haloDropdown` et le filtre `matches(text, query)` de `haloCombobox` — sauf que le « focus flottant » est un `activeIndex` virtuel parmi les éléments `[role="option"]` actuellement visibles plutôt qu'un vrai focus DOM, puisque le champ de recherche doit garder le focus pendant que l'utilisateur tape. Cliquer sur une option (ou appeler `selectActive()` depuis Entrée) ferme la palette via le même type d'écouteur `closeOnItemClick` qu'utilise `Dropdown`.
