---
layout: default
title: Calendar
permalink: /components/calendar/
---

# Calendar

`resources/views/components/halo/calendar/index.blade.php`

A month-grid date picker primitive — not a full "date picker" with its own text input and popover baked in. It only renders the grid; compose it inside [Popover](../popover) for a complete date-picker UX (see the example below). Weeks start on **Monday** (ISO-8601). Dates are handled everywhere as `Y-m-d` strings (`value`, `min`, `max`, and the value the component emits on selection) — there is no PHP-side date generation, the whole day grid is computed client-side from real date math so navigating months never needs a server round-trip.

## Props

| Prop | Type | Default | Notes |
|---|---|---|---|
| `value` | `string` (`Y-m-d`) or `null` | `null` | Initially selected date. Also seeds the initially displayed month |
| `min` | `string` (`Y-m-d`) or `null` | `null` | Days before this date are greyed out and unselectable |
| `max` | `string` (`Y-m-d`) or `null` | `null` | Days after this date are greyed out and unselectable |
| `id` | `string` or `null` | auto-generated | Used to build the grid's `aria-labelledby` caption id |

Any extra attributes (`class`, `data-*`, ...) pass through to the root element.

## Examples

```blade
<x-halo::calendar value="2026-01-15" />

<x-halo::calendar min="2026-01-01" max="2026-12-31" />
```

### Wiring up selection

Selecting a day does not write to a form field by itself — it dispatches a `calendar-change` event whose `detail` is the picked `Y-m-d` string. Listen for it and assign the value to whatever backs your hidden input:

```blade
<div x-data="{ date: '2026-01-15' }">
    <input type="hidden" name="trip_start" :value="date">

    <x-halo::calendar value="2026-01-15" @calendar-change="date = $event.detail" />
</div>
```

### Composing a date picker with Popover

Calendar is a primitive on purpose — there is no separate `DatePicker` component. Build one by composing it inside [`<x-halo::popover>`](../popover), with a text input as the trigger showing the selected date:

```blade
<div x-data="{ date: '2026-01-15' }">
    <input type="hidden" name="trip_start" :value="date">

    <x-halo::popover>
        <x-slot:trigger>
            <x-halo::input type="text" readonly x-model="date" placeholder="Select a date" />
        </x-slot:trigger>

        <x-halo::calendar
            value="2026-01-15"
            @calendar-change="date = $event.detail; close()"
        />
    </x-halo::popover>
</div>
```

`close()` is Popover's own Alpine method, in scope because Calendar's `calendar-change` event bubbles up to the surrounding `haloPopover()` component — closing the panel right after a date is picked.

## Accessibility

The grid follows the [WAI-ARIA grid pattern](https://www.w3.org/WAI/ARIA/apg/patterns/grid/) for date pickers:

- The day grid has `role="grid"`, labelled via `aria-labelledby` pointing at the month/year caption. Each week is a `role="row"`, weekday headers are `role="columnheader"`, and each day is a `role="gridcell"`.
- A single roving `tabindex` tracks the focused day (`0` on the focused cell, `-1` on every other), so `Tab` moves in and out of the grid in one step.
- `←`/`→` move focus by one day, `↑`/`↓` by one week (across month boundaries, navigating the displayed month automatically). `Home`/`End` jump to the start/end of the focused day's week. `Enter`/`Space` selects the focused day.
- The selected day has `aria-selected="true"`; today's date has `aria-current="date"`; days outside `min`/`max` have `aria-disabled="true"` and are greyed out — they stay focusable via the grid's keyboard navigation but selecting one is a no-op, per the APG recommendation to keep out-of-range cells reachable rather than removing them from the tab sequence entirely.
- Each day cell has a full `aria-label` (e.g. "September 9, 2026"), since its visible text is only the day number.
- The prev/next month buttons are real `<button>`s with `aria-label="Previous month"` / `"Next month"`.

## Implementation

Registered as `Alpine.data('haloCalendar', (value, min, max) => ...)` in `resources/js/init.js`. `weeks()` computes the current 6-week (42-cell) grid from `viewYear`/`viewMonth` on every call, so it recomputes automatically whenever those (or `selected`/`min`/`max`) change — including after `previousMonth()`/`nextMonth()`. Dates are tracked as plain `Y-m-d` strings throughout, including `min`/`max`, so bounds-checking (`isDisabled()`) is a lexical string comparison rather than juggling `Date` objects and timezones.
