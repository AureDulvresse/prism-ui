<?php

use Illuminate\Support\HtmlString;

it('renders a dialog wired to haloCommand with dialog aria attributes', function () {
    $html = renderComponent('command.index', ['slot' => 'Options']);

    expect($html)
        ->toContain('haloCommand()')
        ->toContainAttribute('role', 'dialog')
        ->toContainAttribute('aria-modal', 'true')
        ->toContain('Options');
});

it('is hidden until opened', function () {
    expect(renderComponent('command.index'))->toContain('style="display: none;"');
});

it('applies the panel base classes', function () {
    expect(renderComponent('command.index'))
        ->toHaveClass('rounded-halo')
        ->toHaveClass('max-h-[calc(100vh-2rem)]')
        ->toHaveClass('overflow-hidden');
});

it('fades the overlay in and out with the panel', function () {
    expect(renderComponent('command.index'))->toContain('x-transition.opacity');
});

it('renders a search input as a combobox wired to the query model', function () {
    $html = renderComponent('command.index');

    expect($html)
        ->toContainAttribute('role', 'combobox')
        ->toContainAttribute('aria-autocomplete', 'list')
        ->toContain('x-model="query"')
        ->toContain('@input="search()"');
});

it('renders the search icon', function () {
    expect(renderComponent('command.index'))->toContain('M21 21l-5.197-5.197');
});

it('supports arrow-key navigation and enter-to-select on the search input', function () {
    $html = renderComponent('command.index');

    expect($html)
        ->toContain('moveActive(1)')
        ->toContain('moveActive(-1)')
        ->toContain('selectActive()');
});

it('closes the palette when an option is clicked', function () {
    expect(renderComponent('command.index'))->toContain('closeOnItemClick($event)');
});

it('closes on escape and on an outside click', function () {
    expect(renderComponent('command.index'))
        ->toContain('@keydown.escape.window="close()"')
        ->toContain('@click.outside="close()"');
});

it('traps focus within the panel on tab and is itself focusable as a fallback', function () {
    $html = renderComponent('command.index');

    expect($html)
        ->toContain('trapFocus($event)')
        ->toContainAttribute('tabindex', '-1');
});

it('renders the options list with a listbox role linked to the input', function () {
    $html = renderComponent('command.index', ['slot' => 'Items']);

    expect($html)
        ->toContainAttribute('role', 'listbox')
        ->toContain('Items');
});

it('applies the base classes to a group', function () {
    expect(renderComponent('command.group', ['slot' => 'Item content']))->toHaveClass('py-1');
});

it('renders a labeled group that hides itself once fully filtered out', function () {
    $html = renderComponent('command.group', ['label' => 'Suggestions', 'slot' => 'Item content']);

    expect($html)
        ->toContain('Suggestions')
        ->toContainAttribute('role', 'group')
        ->toContainAttribute('aria-label', 'Suggestions')
        ->toContain('x-show="hasVisibleItems($el)"')
        ->toContain('Item content');
});

it('renders a group without a label', function () {
    $html = renderComponent('command.group', ['slot' => 'Item content']);

    expect($html)
        ->not->toContain('aria-label')
        ->toContain('Item content');
});

it('renders an item as a button by default with option semantics', function () {
    $html = renderComponent('command.item', ['slot' => 'New file']);

    expect($html)
        ->toContain('<button')
        ->toContainAttribute('type', 'button')
        ->toContainAttribute('role', 'option')
        ->toContainAttribute('tabindex', '-1')
        ->toContain('New file');
});

it('applies the base classes to an item', function () {
    expect(renderComponent('command.item', ['slot' => 'New file']))->toHaveClass('rounded-halo');
});

it('renders an item as a link when given an href', function () {
    $html = renderComponent('command.item', ['href' => '/settings', 'slot' => 'Settings']);

    expect($html)
        ->toContain('<a')
        ->toContainAttribute('href', '/settings')
        ->toContainAttribute('role', 'option')
        ->toContain('Settings');
});

it('filters items live against the search query and tracks the active highlight', function () {
    $html = renderComponent('command.item', ['slot' => 'Profile']);

    expect($html)
        ->toContain('x-show="matches($el.textContent, query)"')
        ->toContain('isActive($el)')
        ->toContain('@mouseenter="setActive($el)"');
});

it('renders a keyboard shortcut using x-halo::kbd', function () {
    $html = renderComponent('command.item', ['shortcut' => '⌘K', 'slot' => 'Search']);

    expect($html)
        ->toContain('<kbd')
        ->toContain('⌘K');
});

it('omits the shortcut when none is given', function () {
    expect(renderComponent('command.item', ['slot' => 'Search']))->not->toContain('<kbd');
});

it('renders an optional icon slot', function () {
    // A real <x-slot:icon> is an Htmlable ComponentSlot, so {{ $icon }}
    // doesn't escape it — HtmlString reproduces that here, since a plain
    // string prop would otherwise come through escaped.
    $html = renderComponent('command.item', ['slot' => 'Item'], [
        'icon' => new HtmlString('<span data-testid="icon"></span>'),
    ]);

    expect($html)
        ->toContain('data-testid="icon"')
        ->toContain('Item');
});

it('applies the base classes to the empty state', function () {
    expect(renderComponent('command.empty', ['slot' => 'No results found.']))->toHaveClass('text-center');
});

it('renders an empty state hidden until the filter matches zero items', function () {
    $html = renderComponent('command.empty', ['slot' => 'No results found.']);

    expect($html)
        ->toContain('x-show="!hasResults()"')
        ->toContain('No results found.');
});
