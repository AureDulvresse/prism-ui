---
layout: default
title: Number Input
permalink: /fr/components/number-input/
lang: fr
---

# Number Input

`resources/views/components/halo/number-input.blade.php`

Un champ numérique avec boutons +/- enveloppant un `<input type="number">` natif : les boutons de décrément/incrément respectent `min`/`max`/`step` et restent synchronisés avec une saisie manuelle. À ne pas confondre avec [Stepper](../stepper), un indicateur de progression multi-étapes sans rapport.

## Props

| Prop | Type | Défaut | Notes |
|---|---|---|---|
| `value` | `number` ou `null` | `null` | Valeur initiale |
| `min` | `number` ou `null` | `null` | |
| `max` | `number` ou `null` | `null` | |
| `step` | `number` | `1` | |
| `size` | `sm`\|`md`\|`lg` | `md` | Défaut config : `halo.defaults.number-input.size` |
| `invalid` | `bool` | `false` | Pose `aria-invalid="true"` et bascule la bordure/le ring vers `--halo-danger`. Impliqué par `error` |
| `disabled` | `bool` | `false` | Désactive aussi les deux boutons |
| `id` | `string` ou `null` | auto-généré | Retombe sur l'attribut `name`, puis un id aléatoire |
| `error` | `string` ou `null` | `null` | Affiche un message sous le champ, relié via `aria-describedby`, et implique `invalid` |

Tout attribut supplémentaire (`name`, `placeholder`, `wire:model`, ...) passe tel quel sur l'élément `<input>`.

## Exemples

```blade
<x-halo::number-input name="quantity" value="1" min="1" max="10" />

<x-halo::number-input name="rating" value="0" min="0" max="5" step="0.5" />

<x-halo::number-input name="seats" error="Au moins un siège est requis" min="1" />
```

## Comportement

Cliquer sur les boutons de décrément/incrément ne se contente pas de mettre à jour l'état interne Alpine — un vrai événement `input` est déclenché sur l'`<input>` sous-jacent, donc `wire:model`, une soumission de formulaire classique, ou un `x-model` sur un élément englobant voient le changement exactement comme s'il avait été tapé. Les deux boutons se désactivent automatiquement à `min`/`max`.

## Accessibilité

Les boutons portent `aria-label="Decrement"`/`aria-label="Increment"`. Passer `error` pose automatiquement `aria-invalid="true"` et `aria-describedby` sur le champ. Associe le champ à un `<label for>` pointant vers son `id` (explicite ou auto-généré), comme pour [Input](../input).
