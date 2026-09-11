<?php

it('renders the slot content', function () {
    expect(renderComponent('tag', ['slot' => 'New']))->toContain('New');
});

it('defaults to the secondary variant', function () {
    $html = renderComponent('tag', ['slot' => 'New']);

    expect($html)->toHaveClass('bg-halo-secondary');
});

it('applies each variant token classes', function (string $variant, string $expectedClass) {
    $html = renderComponent('tag', ['variant' => $variant, 'slot' => 'Tag']);

    expect($html)->toHaveClass($expectedClass);
})->with([
    ['primary', 'bg-halo-primary/10'],
    ['success', 'bg-halo-success/10'],
    ['danger', 'bg-halo-danger/10'],
    ['warning', 'bg-halo-warning/10'],
]);

it('renders a dismiss button when dismissible', function () {
    $html = renderComponent('tag', ['dismissible' => true]);

    expect($html)
        ->toContain('x-data')
        ->toContain('x-show="show"')
        ->toContain('@click="show = false"')
        ->toContainAttribute('aria-label', 'Remove');
});

it('does not render a dismiss button by default', function () {
    $html = renderComponent('tag');

    expect($html)
        ->not->toContain('x-data')
        ->not->toContainAttribute('aria-label', 'Remove');
});
