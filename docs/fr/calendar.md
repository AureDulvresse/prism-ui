---
layout: default
title: Calendar
permalink: /fr/components/calendar/
lang: fr
---

# Calendar

`resources/views/components/halo/calendar/index.blade.php`

Un primitif de sélecteur de date en grille mensuelle — pas un "date picker" complet avec son propre champ texte et son popover intégrés. Il ne rend que la grille ; compose-le à l'intérieur de [Popover](../popover) pour obtenir une UX de sélecteur de date complète (voir l'exemple ci-dessous). Les semaines commencent le **lundi** (ISO-8601). Les dates sont traitées partout comme des chaînes `Y-m-d` (`value`, `min`, `max`, et la valeur émise par le composant lors de la sélection) — il n'y a aucune génération de dates côté PHP, toute la grille des jours est calculée côté client à partir de vrais calculs de dates, si bien que naviguer entre les mois ne nécessite jamais d'aller-retour serveur.

## Props

| Prop | Type | Défaut | Notes |
|---|---|---|---|
| `value` | `string` (`Y-m-d`) ou `null` | `null` | Date initialement sélectionnée. Détermine aussi le mois initialement affiché |
| `min` | `string` (`Y-m-d`) ou `null` | `null` | Les jours avant cette date sont grisés et non sélectionnables |
| `max` | `string` (`Y-m-d`) ou `null` | `null` | Les jours après cette date sont grisés et non sélectionnables |
| `id` | `string` ou `null` | auto-généré | Utilisé pour construire l'id de légende `aria-labelledby` de la grille |

Tout attribut supplémentaire (`class`, `data-*`, ...) est transmis à l'élément racine.

## Exemples

```blade
<x-halo::calendar value="2026-01-15" />

<x-halo::calendar min="2026-01-01" max="2026-12-31" />
```

### Brancher la sélection

Sélectionner un jour n'écrit pas dans un champ de formulaire par lui-même — le composant émet un événement `calendar-change` dont le `detail` est la chaîne `Y-m-d` choisie. Écoute-le et affecte la valeur à ce qui alimente ton input caché :

```blade
<div x-data="{ date: '2026-01-15' }">
    <input type="hidden" name="trip_start" :value="date">

    <x-halo::calendar value="2026-01-15" @calendar-change="date = $event.detail" />
</div>
```

### Composer un sélecteur de date avec Popover

Calendar est un primitif à dessein — il n'existe pas de composant `DatePicker` séparé. Construis-en un en le composant à l'intérieur de [`<x-halo::popover>`](../popover), avec un champ texte comme déclencheur affichant la date sélectionnée :

```blade
<div x-data="{ date: '2026-01-15' }">
    <input type="hidden" name="trip_start" :value="date">

    <x-halo::popover>
        <x-slot:trigger>
            <x-halo::input type="text" readonly x-model="date" placeholder="Sélectionner une date" />
        </x-slot:trigger>

        <x-halo::calendar
            value="2026-01-15"
            @calendar-change="date = $event.detail; close()"
        />
    </x-halo::popover>
</div>
```

`close()` est la propre méthode Alpine de Popover, accessible ici car l'événement `calendar-change` de Calendar remonte (bubbling) jusqu'au `haloPopover()` englobant — ce qui ferme le panneau juste après qu'une date est choisie.

## Accessibilité

La grille suit le [pattern WAI-ARIA grid](https://www.w3.org/WAI/ARIA/apg/patterns/grid/) pour les sélecteurs de date :

- La grille des jours a `role="grid"`, reliée via `aria-labelledby` à la légende du mois/année. Chaque semaine est un `role="row"`, les en-têtes de jours de la semaine sont `role="columnheader"`, et chaque jour est un `role="gridcell"`.
- Un unique `tabindex` flottant suit le jour focus (`0` sur la cellule focus, `-1` sur toutes les autres), si bien que `Tab` entre et sort de la grille en une seule étape.
- `←`/`→` déplacent le focus d'un jour, `↑`/`↓` d'une semaine (en traversant les limites de mois, ce qui navigue automatiquement le mois affiché). `Home`/`End` sautent au début/à la fin de la semaine du jour focus. `Enter`/`Espace` sélectionne le jour focus.
- Le jour sélectionné a `aria-selected="true"` ; la date du jour a `aria-current="date"` ; les jours en dehors de `min`/`max` ont `aria-disabled="true"` et sont grisés — ils restent accessibles au focus via la navigation clavier de la grille mais les sélectionner ne fait rien, conformément à la recommandation de l'APG de garder les cellules hors plage atteignables plutôt que de les retirer complètement de l'ordre de tabulation.
- Chaque cellule de jour a un `aria-label` complet (par ex. "September 9, 2026"), puisque son texte visible n'est que le numéro du jour.
- Les boutons mois précédent/suivant sont de vrais `<button>` avec `aria-label="Previous month"` / `"Next month"`.

## Implémentation

Enregistré comme `Alpine.data('haloCalendar', (value, min, max) => ...)` dans `resources/js/init.js`. `weeks()` calcule la grille de 6 semaines (42 cellules) courante à partir de `viewYear`/`viewMonth` à chaque appel, donc elle se recalcule automatiquement dès que ceux-ci (ou `selected`/`min`/`max`) changent — y compris après `previousMonth()`/`nextMonth()`. Les dates sont traitées comme de simples chaînes `Y-m-d` de bout en bout, y compris `min`/`max`, si bien que la vérification des bornes (`isDisabled()`) est une comparaison de chaînes lexicale plutôt qu'une jonglerie avec des objets `Date` et des fuseaux horaires.
