<?php

it('renders with the default calendar surface classes', function () {
    $html = renderComponent('calendar.index');

    expect($html)
        ->toHaveClass('rounded-halo')
        ->toHaveClass('border-halo-border')
        ->toHaveClass('bg-halo-background');
});

it('merges a custom class onto the default classes', function () {
    expect(renderComponent('calendar.index', ['class' => 'custom-calendar']))->toHaveClass('custom-calendar');
});

it('wires up the haloCalendar data with null value, min and max by default', function () {
    $html = renderComponent('calendar.index');

    expect($html)->toContain('haloCalendar(null, null, null)');
});

it('reflects the given value in the haloCalendar data', function () {
    $html = renderComponent('calendar.index', ['value' => '2026-01-15']);

    expect($html)->toContain("haloCalendar('2026-01-15', null, null)");
});

it('reflects the given min and max in the haloCalendar data', function () {
    $html = renderComponent('calendar.index', [
        'value' => '2026-01-15',
        'min' => '2026-01-01',
        'max' => '2026-01-31',
    ]);

    expect($html)->toContain("haloCalendar('2026-01-15', '2026-01-01', '2026-01-31')");
});

it('auto-generates an id when none is given', function () {
    expect(renderComponent('calendar.index'))->toContain('id="halo-calendar-');
});

it('uses the given id', function () {
    expect(renderComponent('calendar.index', ['id' => 'trip-start']))->toContainAttribute('id', 'trip-start');
});

it('labels the grid with the month/year caption', function () {
    $html = renderComponent('calendar.index', ['id' => 'trip-start']);

    expect($html)
        ->toContainAttribute('role', 'grid')
        ->toContainAttribute('aria-labelledby', 'trip-start-caption')
        ->toContain('id="trip-start-caption"')
        ->toContain('x-text="monthLabel()"');
});

it('renders a row of weekday column headers starting on Monday', function () {
    $html = renderComponent('calendar.index');

    expect($html)
        ->toContainAttribute('role', 'columnheader')
        ->toContain('abbr="Monday"')
        ->toContain('>Mo<')
        ->toContain('abbr="Sunday"')
        ->toContain('>Su<');

    // Monday must be the first column header, Sunday the last.
    expect(strpos($html, 'abbr="Monday"'))->toBeLessThan(strpos($html, 'abbr="Sunday"'));
});

it('renders previous/next month nav buttons using the chevron icons', function () {
    $html = renderComponent('calendar.index');

    expect($html)
        ->toContain('aria-label="Previous month"')
        ->toContain('@click="previousMonth()"')
        ->toContain('aria-label="Next month"')
        ->toContain('@click="nextMonth()"')
        ->toContain('<svg');
});

it('builds the day grid client-side via weeks(), not server-rendered days', function () {
    $html = renderComponent('calendar.index');

    expect($html)
        ->toContain('x-for="(week, weekIndex) in weeks()"')
        ->toContain('x-for="day in week"')
        ->toContainAttribute('role', 'gridcell');
});

it('binds selection, today and disabled aria state on each day cell', function () {
    $html = renderComponent('calendar.index');

    expect($html)
        ->toContain(':aria-selected="day.date === selected ? \'true\' : \'false\'"')
        ->toContain(':aria-current="day.isToday ? \'date\' : null"')
        ->toContain(':aria-disabled="day.disabled ? \'true\' : null"')
        ->toContain(':tabindex="day.date === focused ? 0 : -1"');
});

it('wires arrow-key, Home/End and Enter/Space keyboard handling on each day cell', function () {
    $html = renderComponent('calendar.index');

    expect($html)
        ->toContain('@keydown.arrow-left.prevent="moveFocus(-1)"')
        ->toContain('@keydown.arrow-right.prevent="moveFocus(1)"')
        ->toContain('@keydown.arrow-up.prevent="moveFocus(-7)"')
        ->toContain('@keydown.arrow-down.prevent="moveFocus(7)"')
        ->toContain('@keydown.home.prevent="focusStartOfWeek()"')
        ->toContain('@keydown.end.prevent="focusEndOfWeek()"')
        ->toContain('@keydown.enter.prevent="select(day.date)"')
        ->toContain('@keydown.space.prevent="select(day.date)"')
        ->toContain('@click="select(day.date)"');
});

it('passes through arbitrary attributes to the root element', function () {
    expect(renderComponent('calendar.index', ['data-testid' => 'trip-calendar']))
        ->toContainAttribute('data-testid', 'trip-calendar');
});
