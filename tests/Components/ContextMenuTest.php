<?php

it('renders the trigger and content slots', function () {
    $html = renderComponent('context-menu.index', [
        'slot' => 'Menu content',
    ], ['trigger' => 'Right-click area']);

    expect($html)
        ->toContain('Right-click area')
        ->toContain('Menu content');
});

it('wires up the haloContextMenu data and a menu role', function () {
    $html = renderComponent('context-menu.index', ['slot' => 'Items']);

    expect($html)
        ->toContain('haloContextMenu()')
        ->toContainAttribute('role', 'menu');
});

it('opens on right-click of the trigger rather than a left click', function () {
    $html = renderComponent('context-menu.index', ['slot' => 'Items']);

    expect($html)
        ->toContain('@contextmenu.prevent="openMenu($event)"')
        ->not->toContain('@click="toggle()"');
});

it('positions the panel at the tracked cursor coordinates', function () {
    $html = renderComponent('context-menu.index', ['slot' => 'Items']);

    expect($html)
        ->toContain(':style="position"')
        ->toHaveClass('fixed');
});

it('supports arrow-key navigation and closes the menu when an item is clicked', function () {
    $html = renderComponent('context-menu.index', ['slot' => 'Items']);

    expect($html)
        ->toContain('focusNext()')
        ->toContain('focusPrevious()')
        ->toContain('closeOnItemClick($event)');
});

it('renders an item as a link when given an href', function () {
    $html = renderComponent('context-menu.item', ['href' => '/profile', 'slot' => 'Profile']);

    expect($html)
        ->toContain('<a')
        ->toContainAttribute('href', '/profile')
        ->toContainAttribute('role', 'menuitem');
});

it('renders an item as a button with no href', function () {
    $html = renderComponent('context-menu.item', ['slot' => 'Delete']);

    expect($html)
        ->toContain('<button')
        ->toContain('Delete')
        ->toContainAttribute('role', 'menuitem');
});
