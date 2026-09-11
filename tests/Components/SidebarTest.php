<?php

it('renders an aside wrapping a nav with a sidebar label', function () {
    $html = renderComponent('sidebar.index', ['slot' => 'Items']);

    expect($html)
        ->toContain('<aside')
        ->toContain('<nav')
        ->toContainAttribute('aria-label', 'Sidebar')
        ->toContain('Items');
});

it('applies the default width and layout classes to the wrapper', function () {
    expect(renderComponent('sidebar.index', ['slot' => 'Items']))
        ->toHaveClass('w-64')
        ->toHaveClass('flex-col');
});

it('renders a group label above the slot content', function () {
    $html = renderComponent('sidebar.group', ['label' => 'Main', 'slot' => 'Children']);

    expect($html)
        ->toContain('Main')
        ->toContain('Children');
});

it('renders a group with no label when none is given', function () {
    $html = renderComponent('sidebar.group', ['slot' => 'Children']);

    expect($html)
        ->not->toContain('<p')
        ->toContain('Children');
});

it('renders a link item with the given href', function () {
    $html = renderComponent('sidebar.item', ['href' => '/dashboard', 'slot' => 'Dashboard']);

    expect($html)
        ->toContain('<a')
        ->toContainAttribute('href', '/dashboard')
        ->toContain('Dashboard');
});

it('renders an inactive item without aria-current', function () {
    expect(renderComponent('sidebar.item', ['href' => '/dashboard', 'slot' => 'Dashboard']))
        ->not->toContainAttribute('aria-current');
});

it('marks an active item with aria-current and a distinct visual state', function () {
    $html = renderComponent('sidebar.item', ['href' => '/dashboard', 'active' => true, 'slot' => 'Dashboard']);

    expect($html)
        ->toContainAttribute('aria-current', 'page')
        ->toHaveClass('bg-halo-secondary');
});

it('renders a leading icon when the icon prop is given', function () {
    $html = renderComponent('sidebar.item', ['href' => '/dashboard', 'icon' => 'home', 'slot' => 'Dashboard']);

    expect($html)->toContain('<svg');
});

it('renders no icon when the icon prop is omitted', function () {
    $html = renderComponent('sidebar.item', ['href' => '/dashboard', 'slot' => 'Dashboard']);

    expect($html)->not->toContain('<svg');
});
