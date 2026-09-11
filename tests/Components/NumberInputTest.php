<?php

// Extracts a single <button ...> opening tag identified by its aria-label,
// so assertions can inspect its attributes without being thrown off by the
// (long) class attribute that precedes the trailing `disabled` attribute.
function extractButtonTag(string $html, string $label): string
{
    foreach (explode('<button', $html) as $chunk) {
        if (str_contains($chunk, "aria-label=\"{$label}\"")) {
            return '<button'.substr($chunk, 0, strpos($chunk, '>') + 1);
        }
    }

    return '';
}

it('renders a number input', function () {
    expect(renderComponent('number-input'))->toContainAttribute('type', 'number');
});

it('applies the default (md) size classes', function () {
    $html = renderComponent('number-input');

    expect($html)
        ->toHaveClass('px-3')
        ->toHaveClass('py-2')
        ->toHaveClass('text-base');
});

it('applies the sm size classes', function () {
    $html = renderComponent('number-input', ['size' => 'sm']);

    expect($html)
        ->toHaveClass('px-2')
        ->toHaveClass('py-1.5')
        ->toHaveClass('text-sm');
});

it('applies the lg size classes', function () {
    $html = renderComponent('number-input', ['size' => 'lg']);

    expect($html)
        ->toHaveClass('px-4')
        ->toHaveClass('py-3')
        ->toHaveClass('text-lg');
});

it('renders base wrapper classes', function () {
    $html = renderComponent('number-input');

    expect($html)
        ->toHaveClass('rounded-halo')
        ->toHaveClass('border-halo-border');
});

it('applies min, max, step and value as attributes', function () {
    $html = renderComponent('number-input', ['min' => 0, 'max' => 20, 'step' => 2, 'value' => 8]);

    expect($html)
        ->toContainAttribute('min', '0')
        ->toContainAttribute('max', '20')
        ->toContainAttribute('step', '2')
        ->toContainAttribute('value', '8');
});

it('defaults step to 1', function () {
    expect(renderComponent('number-input'))->toContainAttribute('step', '1');
});

it('wires up the haloNumberInput Alpine data with the initial value, min, max and step', function () {
    $html = renderComponent('number-input', ['value' => 5, 'min' => 0, 'max' => 10, 'step' => 2]);

    expect($html)->toContain('haloNumberInput({ value: 5, min: 0, max: 10, step: 2 })');
});

it('wires up the haloNumberInput Alpine data with nulls when nothing is given', function () {
    expect(renderComponent('number-input'))
        ->toContain('haloNumberInput({ value: null, min: null, max: null, step: 1 })');
});

it('auto-generates an id when none is given', function () {
    expect(renderComponent('number-input'))->toContain('id="halo-number-input-');
});

it('uses the given id when provided', function () {
    $html = renderComponent('number-input', ['id' => 'quantity-field']);

    expect($html)->toContainAttribute('id', 'quantity-field');
});

it('uses the name attribute as the id when no id is given', function () {
    $html = renderComponent('number-input', ['name' => 'quantity']);

    expect($html)->toContainAttribute('id', 'quantity');
});

it('marks the field as invalid', function () {
    $html = renderComponent('number-input', ['invalid' => true]);

    expect($html)
        ->toContainAttribute('aria-invalid', 'true')
        ->toHaveClass('border-halo-danger');
});

it('renders an error message linked via aria-describedby', function () {
    $html = renderComponent('number-input', ['id' => 'quantity', 'error' => 'Quantity is required']);

    expect($html)
        ->toContainAttribute('aria-invalid', 'true')
        ->toContainAttribute('aria-describedby', 'quantity-error')
        ->toContain('id="quantity-error"')
        ->toContain('Quantity is required');
});

it('can be disabled', function () {
    $html = renderComponent('number-input', ['disabled' => true]);

    expect($html)->toContainAttribute('disabled');
});

it('renders a decrement and an increment button', function () {
    $html = renderComponent('number-input');

    expect($html)
        ->toContainAttribute('aria-label', 'Decrement')
        ->toContainAttribute('aria-label', 'Increment')
        ->toContain('decrement()')
        ->toContain('increment()');
});

it('disables the decrement button server-side when the initial value is at min', function () {
    $html = renderComponent('number-input', ['value' => 0, 'min' => 0]);

    expect(extractButtonTag($html, 'Decrement'))->toContain('disabled');
});

it('disables the increment button server-side when the initial value is at max', function () {
    $html = renderComponent('number-input', ['value' => 10, 'max' => 10]);

    expect(extractButtonTag($html, 'Increment'))->toContain('disabled');
});

it('bakes the disabled prop into the reactive button bindings', function () {
    $html = renderComponent('number-input', ['disabled' => true]);

    expect($html)
        ->toContain(':disabled="atMin || true"')
        ->toContain(':disabled="atMax || true"');
});
