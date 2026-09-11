---
layout: default
title: Number Input
permalink: /components/number-input/
---

# Number Input

`resources/views/components/halo/number-input.blade.php`

A quantity stepper wrapping a native `<input type="number">`: decrement/increment buttons on either side that respect `min`/`max`/`step` and stay in sync with manual typing. Not to be confused with [Stepper](../stepper), which is an unrelated multi-step progress indicator.

## Props

| Prop | Type | Default | Notes |
|---|---|---|---|
| `value` | `number` or `null` | `null` | Initial value |
| `min` | `number` or `null` | `null` | |
| `max` | `number` or `null` | `null` | |
| `step` | `number` | `1` | |
| `size` | `sm`\|`md`\|`lg` | `md` | Config default: `halo.defaults.number-input.size` |
| `invalid` | `bool` | `false` | Sets `aria-invalid="true"` and switches the border/ring to `--halo-danger`. Implied by `error` |
| `disabled` | `bool` | `false` | Also disables both stepper buttons |
| `id` | `string` or `null` | auto-generated | Falls back to the `name` attribute, then a random id |
| `error` | `string` or `null` | `null` | Renders a message below the field, wired via `aria-describedby`, and implies `invalid` |

Any extra attributes (`name`, `placeholder`, `wire:model`, ...) pass through to the `<input>` element.

## Examples

```blade
<x-halo::number-input name="quantity" value="1" min="1" max="10" />

<x-halo::number-input name="rating" value="0" min="0" max="5" step="0.5" />

<x-halo::number-input name="seats" error="At least one seat is required" min="1" />
```

## Behavior

Clicking the decrement/increment buttons doesn't just update internal Alpine state — it dispatches a real `input` event on the underlying `<input>` element, so `wire:model`, a plain form submission, or an `x-model` on a wrapping element all see the change exactly as if it had been typed. Both buttons disable themselves automatically at `min`/`max`.

## Accessibility

The buttons carry `aria-label="Decrement"`/`aria-label="Increment"`. Passing `error` sets `aria-invalid="true"` and `aria-describedby` on the field automatically. Pair the field with a `<label for>` pointing at its `id` (explicit or auto-generated), same as [Input](../input).
