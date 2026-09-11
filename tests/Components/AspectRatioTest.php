<?php

use Illuminate\Support\HtmlString;

it('defaults to a 16/9 ratio', function () {
    expect(renderComponent('aspect-ratio', ['slot' => 'Content']))
        ->toContain('style="aspect-ratio: 16/9"');
});

it('reflects a custom ratio prop', function () {
    $html = renderComponent('aspect-ratio', ['ratio' => '1/1', 'slot' => 'Content']);

    expect($html)
        ->toContain('style="aspect-ratio: 1/1"')
        ->not->toContain('16/9');
});

it('renders the slot content inside the wrapper', function () {
    expect(renderComponent('aspect-ratio', ['slot' => new HtmlString('<img src="/photo.jpg" alt="Photo">')]))
        ->toContain('<img src="/photo.jpg" alt="Photo">');
});

it('clips overflowing content', function () {
    expect(renderComponent('aspect-ratio', ['slot' => 'Content']))
        ->toHaveClass('overflow-hidden');
});

it('merges a caller-supplied class alongside the wrapper defaults', function () {
    $html = renderComponent('aspect-ratio', ['class' => 'rounded-lg', 'slot' => 'Content']);

    expect($html)
        ->toContain('rounded-lg')
        ->toContain('overflow-hidden');
});
