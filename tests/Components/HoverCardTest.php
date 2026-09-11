<?php

it('renders the trigger and the hover card content', function () {
    $html = renderComponent('hover-card.index', ['slot' => 'Card content'], ['trigger' => 'Hover me']);

    expect($html)
        ->toContain('Hover me')
        ->toContain('Card content');
});

it('positions the hover card via the position prop', function (string $position, string $expectedClass) {
    $html = renderComponent('hover-card.index', ['position' => $position, 'slot' => 'Text']);

    expect($html)->toHaveClass($expectedClass);
})->with([
    ['top', 'bottom-full'],
    ['bottom', 'top-full'],
    ['left', 'right-full'],
    ['right', 'left-full'],
]);

it('defaults to the bottom position', function () {
    expect(renderComponent('hover-card.index', ['slot' => 'Text']))->toHaveClass('top-full');
});

it('is hidden until shown via x-show', function () {
    expect(renderComponent('hover-card.index', ['slot' => 'Text']))->toContain('x-show="open"');
});

it('wires up the haloHoverCard data', function () {
    expect(renderComponent('hover-card.index', ['slot' => 'Text']))->toContain('haloHoverCard(');
});

it('wires the generated id between the Alpine factory and the content it describes', function () {
    // aria-describedby itself is set on the trigger's element at runtime by
    // haloHoverCard() (mirroring haloTooltip), so it isn't in the static
    // markup — what we can assert server-side is that the same generated id
    // is passed to the factory and applied to the panel it should describe.
    $html = renderComponent('hover-card.index', ['slot' => 'Text']);

    preg_match('/id="(halo-hover-card-[^"]+)"/', $html, $matches);

    expect($matches)->toHaveCount(2)
        ->and($html)
        ->toContain('x-ref="trigger"')
        ->toContain("haloHoverCard('{$matches[1]}')");
});
