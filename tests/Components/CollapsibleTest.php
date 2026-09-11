<?php

it('wires up the haloCollapsible data closed by default', function () {
    expect(renderComponent('collapsible.index', ['slot' => 'Section']))->toContain('haloCollapsible(false)');
});

it('passes the open prop through to the Alpine data', function () {
    expect(renderComponent('collapsible.index', ['open' => true, 'slot' => 'Section']))->toContain('haloCollapsible(true)');
});

it('scopes matching ids for the trigger and content via x-id', function () {
    $html = renderComponent('collapsible.index', ['slot' => 'Section']);

    expect($html)->toContain("x-id=\"['halo-collapsible-trigger', 'halo-collapsible-content']\"");
});

it('renders a trigger button that toggles open state', function () {
    $html = renderComponent('collapsible.trigger', ['slot' => 'Toggle me']);

    expect($html)
        ->toContain('Toggle me')
        ->toContain('@click="toggle()"')
        ->toContain(':aria-expanded="open ? \'true\' : \'false\'"')
        ->toContain(":id=\"\$id('halo-collapsible-trigger')\"")
        ->toContain(":aria-controls=\"\$id('halo-collapsible-content')\"")
        ->toContainAttribute('type', 'button');
});

it('renders the chevron icon by default', function () {
    $html = renderComponent('collapsible.trigger', ['slot' => 'Toggle me']);

    expect($html)
        ->toContain('<svg')
        ->toContain(":style=\"open ? 'transform: rotate(180deg)' : ''\"");
});

it('omits the chevron icon when icon is false', function () {
    expect(renderComponent('collapsible.trigger', ['icon' => false, 'slot' => 'Toggle me']))->not->toContain('<svg');
});

it('renders a content panel linked back to the trigger', function () {
    $html = renderComponent('collapsible.content', ['slot' => 'Section body']);

    expect($html)
        ->toContain('Section body')
        ->toContain('x-show="open"')
        ->toContain('x-transition')
        ->toContain(":id=\"\$id('halo-collapsible-content')\"")
        ->toContain(":aria-labelledby=\"\$id('halo-collapsible-trigger')\"")
        ->toContainAttribute('role', 'region');
});
