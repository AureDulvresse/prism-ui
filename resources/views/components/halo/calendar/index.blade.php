@props([
    'value' => null,
    'min' => null,
    'max' => null,
    'id' => null,
])

@php
$calendarId = $id ?? uniqid('halo-calendar-');
$captionId = $calendarId.'-caption';

$classes = halo_merge_classes(
    'inline-block rounded-halo border border-halo-border bg-halo-background p-4 text-halo-foreground',
    $attributes->get('class'),
);

// Weeks start on Monday (ISO-8601); these headers are static (they never
// change when navigating months) so they're rendered server-side, unlike
// the day grid itself.
$weekdayLabels = [
    'Mo' => 'Monday',
    'Tu' => 'Tuesday',
    'We' => 'Wednesday',
    'Th' => 'Thursday',
    'Fr' => 'Friday',
    'Sa' => 'Saturday',
    'Su' => 'Sunday',
];
@endphp

{{--
    A month-grid date picker primitive — not a full "DatePicker" with its
    own text input/popover. Compose it inside <x-halo::popover> for that
    (a text input as the trigger, this component as the panel) — see
    docs/calendar.md for the full example. `value`/`min`/`max` are all
    'Y-m-d' date strings (or null).

    Every day cell lives inside a `<template x-for>` and is computed by
    weeks() (registered as Alpine.data('haloCalendar', ...) in
    resources/js/init.js) from real date math — no day is ever generated
    PHP-side, so navigating months re-renders the grid instantly with no
    server round-trip.

    Selecting a date does not write to a form field itself: listen for the
    `calendar-change` event it dispatches (its detail is the picked
    'Y-m-d' string) and assign it to whatever backs your hidden input —
    see docs/calendar.md for the pattern.

    Follows the WAI-ARIA grid pattern for date pickers: role="grid" with
    role="row"/"columnheader"/"gridcell", a single roving tabindex on the
    focused day, arrow keys to move focus by day/week, Home/End for the
    start/end of the focused day's week, and Enter/Space to select the
    focused day.
--}}
<div
    id="{{ $calendarId }}"
    x-data="haloCalendar(@js($value), @js($min), @js($max))"
    {{ $attributes->except(['value', 'min', 'max', 'id', 'class'])->merge(['class' => $classes]) }}
>
    <div class="mb-4 flex items-center justify-between gap-2">
        <button
            type="button"
            @click="previousMonth()"
            class="rounded-halo p-2 text-halo-foreground/50 transition-colors hover:bg-halo-secondary hover:text-halo-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-halo-ring"
            aria-label="Previous month"
        >
            <x-halo::icon name="chevron-left" size="sm" />
        </button>

        <p id="{{ $captionId }}" class="text-sm font-medium" aria-live="polite" x-text="monthLabel()"></p>

        <button
            type="button"
            @click="nextMonth()"
            class="rounded-halo p-2 text-halo-foreground/50 transition-colors hover:bg-halo-secondary hover:text-halo-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-halo-ring"
            aria-label="Next month"
        >
            <x-halo::icon name="chevron-right" size="sm" />
        </button>
    </div>

    <div role="grid" aria-labelledby="{{ $captionId }}" class="w-full">
        <div role="row" class="grid grid-cols-7 gap-1">
            @foreach($weekdayLabels as $short => $full)
                <span
                    role="columnheader"
                    abbr="{{ $full }}"
                    class="flex h-8 w-9 items-center justify-center text-xs font-medium text-halo-foreground/50"
                >{{ $short }}</span>
            @endforeach
        </div>

        <template x-for="(week, weekIndex) in weeks()" :key="weekIndex">
            <div role="row" class="grid grid-cols-7 gap-1">
                <template x-for="day in week" :key="day.date">
                    <button
                        type="button"
                        role="gridcell"
                        :data-date="day.date"
                        :tabindex="day.date === focused ? 0 : -1"
                        :aria-selected="day.date === selected ? 'true' : 'false'"
                        :aria-current="day.isToday ? 'date' : null"
                        :aria-disabled="day.disabled ? 'true' : null"
                        :aria-label="dayLabel(day.date)"
                        :class="{
                            'text-halo-foreground/30': !day.currentMonth,
                            'opacity-40 cursor-not-allowed hover:bg-transparent': day.disabled,
                            'bg-halo-primary text-halo-primary-foreground hover:bg-halo-primary': day.date === selected,
                            'ring-1 ring-inset ring-halo-ring': day.isToday && day.date !== selected,
                        }"
                        class="flex h-9 w-9 items-center justify-center rounded-halo text-sm text-halo-foreground transition-colors hover:bg-halo-secondary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-halo-ring"
                        @click="select(day.date)"
                        @keydown.enter.prevent="select(day.date)"
                        @keydown.space.prevent="select(day.date)"
                        @keydown.arrow-left.prevent="moveFocus(-1)"
                        @keydown.arrow-right.prevent="moveFocus(1)"
                        @keydown.arrow-up.prevent="moveFocus(-7)"
                        @keydown.arrow-down.prevent="moveFocus(7)"
                        @keydown.home.prevent="focusStartOfWeek()"
                        @keydown.end.prevent="focusEndOfWeek()"
                        x-text="day.day"
                    ></button>
                </template>
            </div>
        </template>
    </div>
</div>
