<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Theme tokens
    |--------------------------------------------------------------------------
    |
    | Radius/spacing/typography tokens accessible via the theme() helper.
    | Colors are NOT configured here — they live as CSS custom properties in
    | resources/css/theme.css (--halo-primary, --halo-danger, ...) mapped
    | into real Tailwind utility classes via Tailwind v4's @theme directive.
    | This is what makes theming/dark-mode actually work at runtime instead
    | of requiring config changes and a rebuild.
    |
    | 'default' is rendered server-side as <html data-theme="{{ ... }}"> so
    | the correct theme paints on first load, before HaloUI.theme (Alpine
    | store, see resources/js/init.js) takes over and persists the user's
    | choice in localStorage.
    |
    */

    'theme' => [
        'default' => 'halo',
        'available' => ['halo', 'aurora', 'eclipse', 'ember', 'nocturne', 'luma', 'flint'],
        'radius' => 'rounded-halo',
    ],

    /*
    |--------------------------------------------------------------------------
    | Component Defaults
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'button' => [
            'variant' => 'primary',
            'size' => 'md',
        ],

        'input' => [
            'size' => 'md',
        ],

        'textarea' => [
            'size' => 'md',
        ],

        'select' => [
            'size' => 'md',
        ],

        'badge' => [
            'variant' => 'secondary',
        ],

        'card' => [
            'variant' => 'default',
        ],

        'alert' => [
            'variant' => 'info',
        ],

        'tooltip' => [
            'position' => 'top',
        ],

        'popover' => [
            'align' => 'left',
        ],

        'toggle' => [
            'variant' => 'default',
            'size' => 'md',
        ],

        'skeleton' => [
            'variant' => 'rectangle',
        ],

        'combobox' => [
            'placeholder' => 'Select...',
        ],

        'hover-card' => [
            'position' => 'bottom',
        ],

        'tag' => [
            'variant' => 'secondary',
        ],

        'number-input' => [
            'size' => 'md',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Icon Configuration
    |--------------------------------------------------------------------------
    */

    'icons' => [
        'set' => 'halo',
        'default_class' => 'w-5 h-5',
    ],

    /*
    |--------------------------------------------------------------------------
    | Assets
    |--------------------------------------------------------------------------
    |
    | HaloUI ships its CSS (Alpine.js is bundled inside its JS build — see
    | resources/js/init.js). When 'serve' is true, the package registers two
    | routes that serve those built files directly from the package itself,
    | so @haloStyles/@haloScripts work immediately after `composer require`
    | with no `vendor:publish` or Vite config needed.
    |
    | Set this to false if you'd rather publish the assets (`halo-assets` tag)
    | and bundle them through your own app's Vite pipeline instead.
    |
    */

    'assets' => [
        'serve' => true,
    ],
];
