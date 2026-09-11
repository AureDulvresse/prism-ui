<?php

it('renders a dialog wired to its name via haloDrawer', function () {
    $html = renderComponent('drawer.index', ['name' => 'user-drawer', 'slot' => 'Content']);

    expect($html)
        ->toContain("haloDrawer('user-drawer')")
        ->toContainAttribute('role', 'dialog')
        ->toContainAttribute('aria-modal', 'true')
        ->toContain('Content');
});

it('is hidden until opened', function () {
    expect(renderComponent('drawer.index', ['name' => 'x']))->toContain('style="display: none;"');
});

it('defaults to sliding in from the right edge', function () {
    expect(renderComponent('drawer.index', ['name' => 'x']))
        ->toHaveClass('right-0')
        ->toHaveClass('translate-x-full');
});

it('slides in from the left edge', function () {
    expect(renderComponent('drawer.index', ['name' => 'x', 'side' => 'left']))
        ->toHaveClass('left-0')
        ->toHaveClass('-translate-x-full');
});

it('slides in from the top edge', function () {
    expect(renderComponent('drawer.index', ['name' => 'x', 'side' => 'top']))
        ->toHaveClass('top-0')
        ->toHaveClass('-translate-y-full');
});

it('slides in from the bottom edge', function () {
    expect(renderComponent('drawer.index', ['name' => 'x', 'side' => 'bottom']))
        ->toHaveClass('bottom-0')
        ->toHaveClass('translate-y-full');
});

it('renders a close button', function () {
    expect(renderComponent('drawer.index', ['name' => 'x']))->toContain('aria-label="Close"');
});

it('traps focus within the panel on tab and is itself focusable as a fallback', function () {
    $html = renderComponent('drawer.index', ['name' => 'x']);

    expect($html)
        ->toContain('trapFocus($event)')
        ->toContainAttribute('tabindex', '-1');
});

it('closes on backdrop click and escape', function () {
    $html = renderComponent('drawer.index', ['name' => 'x']);

    expect($html)
        ->toContain('@keydown.escape.window="close()"')
        ->toContain('@click.outside="close()"')
        ->toContain('@click="close()"');
});

it('fades the overlay in and out with the panel', function () {
    expect(renderComponent('drawer.index', ['name' => 'x']))->toContain('x-transition.opacity');
});

it('renders a header with a bottom border and room for the close button', function () {
    expect(renderComponent('drawer.header', ['slot' => 'Title']))
        ->toContain('Title')
        ->toHaveClass('border-b')
        ->toHaveClass('pr-12');
});

it('renders a body that scrolls internally', function () {
    expect(renderComponent('drawer.body', ['slot' => 'Body content']))
        ->toContain('Body content')
        ->toHaveClass('flex-1')
        ->toHaveClass('overflow-y-auto');
});

it('renders a footer with actions aligned to the end', function () {
    expect(renderComponent('drawer.footer', ['slot' => 'Actions']))
        ->toContain('Actions')
        ->toHaveClass('justify-end');
});
