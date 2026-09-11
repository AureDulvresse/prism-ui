# HaloUI

<p align="start">
  <a href="https://packagist.org/packages/ironflow/halo-ui">
    <img src="https://img.shields.io/packagist/v/ironflow/halo-ui" alt="Latest Version" />
  </a>
  <a href="https://packagist.org/packages/ironflow/halo-ui">
    <img src="https://img.shields.io/packagist/dt/ironflow/halo-ui" alt="Total Downloads">
  </a>
  <a href="https://packagist.org/packages/ironflow/halo-ui">
    <img src="https://img.shields.io/packagist/l/ironflow/halo-ui" alt="License">
  </a>
  <a href="https://github.com/AureDulvresse/halo-ui/issues">
  <img src="https://img.shields.io/github/issues/AureDulvresse/halo-ui" alt="GitHub issues">
  </a>
</p>

<p align="center">
  <strong>Modern, composable Blade UI component library for Laravel</strong><br>
  Elegant as shadcn/ui, native as Blade. Built with Tailwind CSS v4, Alpine.js, and Blade Icons.
</p>

<p align="center">
  <a href="#status">Status</a> •
  <a href="#installation">Installation</a> •
  <a href="#components">Components</a> •
  <a href="#theming">Theming</a> •
  <a href="#customization">Customization</a> •
  <a href="#testing">Testing</a> •
  <a href="#contributing">Contributing</a>
</p>

---

## Status

**v4 is a from-scratch rebuild, currently at an early, deliberately small stage.** Earlier versions accumulated dead code and a documentation/reality gap (classes and templates that were never wired up, a theme system that didn't actually theme anything). Rather than carry that forward, v4 starts over with a strict rule: **every component that exists is real, tested, and themed correctly** — nothing is listed here until it ships.

Today that's **61 components** (including 6 layout components). More are added incrementally; see [CHANGELOG.md](CHANGELOG.md) for what's shipped and `docs/` for what's next.

## Features

- **Zero-config assets** — `@haloStyles`/`@haloScripts` serve the package's own built CSS/JS (Alpine.js bundled in) with no `vendor:publish`, no Vite config, no separate Alpine install
- **Anonymous Blade components** — no PHP classes to maintain, just `@props()` and a Blade file
- **Real runtime theming** — colors, radius, and dark/light are CSS custom properties (`--halo-*`) mapped into Tailwind v4 via `@theme`; switching themes changes an attribute, not a build step
- **Copy-and-own** — works out of the box after `composer require`; `php artisan halo:install` is an optional eject for full customization
- **Blade Icons integration** — icons resolve through Blade Icons' native component API, not a hand-rolled resolver
- **Accessible by default** — ARIA attributes and keyboard behavior are part of a component's first version, not an afterthought
- **Alpine.js for interactivity** — registered as named `Alpine.data()`/`Alpine.store()`, invoked directly in Blade

---

## Installation

### Requirements

- PHP 8.2+
- Laravel 11+ or 12+

Tailwind and Alpine.js are **not** separate requirements — HaloUI ships its own built CSS and JS (Alpine.js is bundled inside the JS build), so there's nothing extra to install for the fast path below.

### Install via Composer

```bash
composer require ironflow/halo-ui
```

### Fastest path: two tags in your layout

No `vendor:publish`, no Vite config, no separate Alpine.js install. `@haloStyles`/`@haloScripts` serve the package's own built assets directly:

```html
<!DOCTYPE html>
<html lang="en" data-theme="{{ config('halo.theme.default') }}">
<head>
    <meta charset="UTF-8">
    @haloStyles
</head>
<body>
    {{ $slot }}

    @haloScripts
</body>
</html>
```

That's the whole setup. `<x-halo::button>`, `<x-halo::input>`, `<x-halo::badge>`, theme switching, focus traps, roving-focus menus — all working, with no build step of your own.

### Integrated path: your own Vite/Tailwind pipeline

If your app already runs Tailwind v4 and Alpine.js and you want HaloUI's classes to be purgeable/tree-shakeable alongside your own, skip `@haloStyles`/`@haloScripts` and wire the package's sources into your own build instead:

```css
/* resources/css/app.css */
@import "tailwindcss";
@import "../../vendor/ironflow/halo-ui/resources/css/theme.css";
@source "../../vendor/ironflow/halo-ui/resources/views";
```

```js
// resources/js/app.js
import Alpine from 'alpinejs';
import '../../vendor/ironflow/halo-ui/resources/js/init.js'; // registers haloModal, haloDropdown, etc.

window.Alpine = Alpine;
Alpine.start();
```

Set `'assets' => ['serve' => false]` in `config/halo.php` (publish it first with `php artisan vendor:publish --tag=halo-config`) so `@haloStyles`/`@haloScripts`, if you still use them anywhere, point at your own published/bundled files instead of the package's asset routes.

### Eject and customize (optional)

`halo:install` copies the package's own component files into your app, so you can edit them directly:

```bash
# Eject every component
php artisan halo:install --all

# Eject specific components
php artisan halo:install button input

# Overwrite files already ejected
php artisan halo:install --all --force
```

Once ejected to `resources/views/components/halo/`, your local copy takes precedence — the package's own components still work for anything you didn't eject.

---

## Quick Start

```blade
<x-halo::button variant="primary" icon="check">
    Save changes
</x-halo::button>

<x-halo::input
    name="email"
    type="email"
    icon="mail"
    placeholder="you@example.com"
/>

<x-halo::badge variant="success">Active</x-halo::badge>

<x-halo::icon name="user" size="lg" />

<x-halo::alert variant="danger" dismissible>
    Something went wrong — please try again.
</x-halo::alert>

{{-- Modal: opened by name, from anywhere --}}
<x-halo::button @click="$dispatch('open-modal', 'confirm-delete')">
    Delete account
</x-halo::button>

<x-halo::modal name="confirm-delete">
    <x-halo::modal.header>Delete account?</x-halo::modal.header>
    <x-halo::modal.body>This can't be undone.</x-halo::modal.body>
    <x-halo::modal.footer>
        <x-halo::button variant="outline" @click="$dispatch('close-modal')">Cancel</x-halo::button>
        <x-halo::button variant="danger">Delete</x-halo::button>
    </x-halo::modal.footer>
</x-halo::modal>
```

---

## Components

Full props reference for each lives in `docs/{component}.md`; this is the map.

### Typography

| Component | Notes |
|-----------|-------|
| **Heading** | `<h1>`–`<h6>` via `level`, each with a matching default type scale; `size` overrides the look without changing the tag |
| **Text** | Body copy; `as` (`p`/`span`/`div`/`blockquote`), `size`, `muted` |

### Form

| Component | Notes |
|-----------|-------|
| **Button** | Variants `primary`/`secondary`/`outline`/`ghost`/`danger`; sizes `sm`/`md`/`lg`; `icon`, `iconPosition`, `loading`, `disabled` |
| **Input** | Sizes `sm`/`md`/`lg`; `icon`, `iconPosition`, `invalid`, `error` (message + `aria-describedby`), `disabled`, auto-generated `id` |
| **Input Group** | Wraps Input with leading/trailing add-ons (icon, text, or a `<x-halo::button>`) sharing one border |
| **Textarea** | Same size/invalid/error/disabled pattern as Input, plus `rows` and `resize` |
| **Number Input** | Native `<input type="number">` with +/- buttons respecting `min`/`max`/`step`; dispatches a real `input` event so `wire:model`/`x-model` stay in sync |
| **Label** | Pairs with any field via `for`; `required` adds a decorative `*` |
| **Checkbox** | Native `<input type="checkbox">` wrapped in a `<label>`; themed via `accent-halo-primary` |
| **Radio** | Same pattern as Checkbox; never derives its `id` from the shared group `name` |
| **Select** | Custom-styled trigger + `role="listbox"` panel of `select.item` options (or the `options` prop); `invalid`/`error` like Input |
| **Combobox** (+ `.option`) | Text input that filters a server-rendered option list client-side as you type |
| **Switch** | Native `<input type="checkbox" role="switch">` styled as a track/thumb toggle |
| **Toggle** | Single pressable button; `pressed`/`aria-pressed`, variants `default`/`outline` |
| **Toggle Group** (+ `.item`) | Segmented control; `type` `single` (one value) or `multiple` (array), WAI-ARIA toolbar pattern |
| **Slider** | Native `<input type="range">` restyled with a themed track/thumb; `min`/`max`/`step` |
| **Rating** | Server-rendered stars up to `max`; hover preview before committing a value |
| **Calendar** | Month-grid date picker primitive; keyboard grid navigation, `min`/`max` bounds, dispatches `calendar-change` — compose inside `<x-halo::popover>` for a full date-picker UX |
| **File Upload** | Drag-and-drop over a real `<input type="file">`; removable file list, `multiple`, `accept` |
| **Image Upload** | Same drag-and-drop pattern, restricted to images, with live thumbnail previews |

### Display

| Component | Notes |
|-----------|-------|
| **Icon** | Resolves any icon in the `halo` Blade Icons set (`resources/icons/halo/*.svg`) by name; sizes `xs`–`xl` |
| **Badge** | Variants `primary`/`secondary`/`success`/`danger`/`warning` |
| **Tag** | Inline label mirroring Badge's variants, with an optional `dismissible` close button |
| **Kbd** | Small inline element for a keyboard key or shortcut |
| **Avatar** | `src` image, `initials` fallback, or generic icon; `status` dot |
| **Spinner** | Standalone loading indicator; also used internally by Button's `loading` state |
| **Skeleton** | Loading placeholder; variants `rectangle`/`circle`/`text` |
| **Progress** | Determinate (`value`/`max`) or `indeterminate` progress bar |
| **Divider** | Horizontal (with optional centered `label`) or `vertical` |
| **Aspect Ratio** | Constrains slotted content (image, iframe) to a `ratio` (e.g. `16/9`) to prevent layout shift |
| **Scroll Area** | Fixed-height scrollable region with a themed scrollbar; `height` prop |
| **Empty State** | Icon/illustration + title + description + optional `actions` slot for empty lists/tables |
| **Stat Card** | Label + value + optional trend indicator, for dashboard metric grids |
| **Timeline** (+ `.item`) | Vertical event list; each item has an optional `date` |
| **Card** (+ `.header`/`.body`/`.footer`) | Variants `default`/`bordered`/`elevated` |
| **Table** (+ `.row`/`.head`/`.cell`) | Styled wrappers around native `<table>`/`<tr>`/`<th>`/`<td>`; `<thead>`/`<tbody>` stay plain HTML |

### Feedback

| Component | Notes |
|-----------|-------|
| **Alert** | Variants `info`/`success`/`warning`/`danger`, each with a matching default icon; `dismissible` |

### Overlays (Alpine-powered)

| Component | Notes |
|-----------|-------|
| **Modal** (+ `.header`/`.body`/`.footer`) | Opened/closed by `name` via `$dispatch('open-modal', name)` — no `:open` prop to sync; traps focus while open, returns it to the trigger on close |
| **Alert Dialog** (+ `.header`/`.body`/`.footer`) | A stricter Modal for confirmations — its own `open-alert-dialog`/`close-alert-dialog` events so a stray `close-modal` elsewhere can never dismiss it |
| **Drawer** (+ `.header`/`.body`/`.footer`) | Modal-style focus trap sliding in from a screen edge; `side` `left`/`right`/`top`/`bottom` |
| **Dropdown** (+ `.item`) | `trigger` named slot; arrow keys move between items, closes on escape/outside click/selecting an item, returns focus to the trigger |
| **Context Menu** (+ `.item`) | Dropdown's behavior opened by right-click instead of left-click, positioned at the cursor |
| **Command** (+ `.group`/`.item`/`.empty`) | Cmd+K-style palette: searchable, keyboard-navigable list opened via `$dispatch('open-command')` |
| **Popover** | `trigger` named slot for arbitrary rich content; no menu semantics, focus returns to the trigger on close |
| **Tooltip** | `trigger` named slot; shown on hover/focus, `aria-describedby` wired automatically |
| **Hover Card** | Like Tooltip but for rich anchored content, shown after a hover delay via a `trigger` named slot |
| **Toast** | One global queue rendered by a single `<x-halo::toast />`; push via `$store.haloToast.push(message, variant)` |

### Navigation (Alpine-powered)

| Component | Notes |
|-----------|-------|
| **Tabs** (`.list`, `.trigger`, `.panel`) | `default` active tab; arrow keys roam between triggers |
| **Accordion** (+ `.item`) | `multiple` allows more than one item open at once; each item tracked by an explicit `name` or an auto-generated one |
| **Collapsible** (`.trigger`, `.content`) | A single standalone disclosure section — the same mechanic as one Accordion item, without the group |
| **Breadcrumb** (+ `.item`) | `href` for links, `current` for the non-interactive last item |
| **Navigation Menu** (+ `.item`) | Horizontal top-level nav with active-link styling |
| **Pagination** | Page-number list with prev/next controls; collapses a long range with ellipses |
| **Stepper** (+ `.step`) | Multi-step progress indicator; steps before `current` are complete, later ones pending |

### Layouts

| Component | Notes |
|-----------|-------|
| **Layout: Base** | A full `<!DOCTYPE html>`…`</html>` skeleton, `@haloStyles`/`@haloScripts` already wired in — the fastest way to get a page rendering |
| **Layout: Container** | Max-width, centered content wrapper; `size` `sm`–`xl`\|`full` |
| **Layout: App Shell** | Sidebar + topbar dashboard layout; off-canvas drawer below `lg`, static above it |
| **Layout: Auth** | Centered layout for login/register pages, optional `logo` slot |
| **Layout: Two Column** | Content + secondary sidebar (docs nav, settings nav); `sidebarPosition` `left`\|`right` |
| **Layout: Page Header** | Title + description + right-aligned `actions` slot |
| **Sidebar** (+ `.group`/`.item`) | Standalone nav sidebar for a layout of your own choosing — not tied to App Shell's opinionated topbar/drawer structure |

Each component's props, variant maps, and rendered markup live directly in its `.blade.php` file under `resources/views/components/halo/` — read the source, it's short by design.

See [CHANGELOG.md](CHANGELOG.md) for release history and `docs/` for per-component prop references.

---

## Theming

Colors, radius, and light/dark are CSS custom properties defined in `resources/css/theme.css`, scoped to a `data-theme` attribute, and mapped into real Tailwind utility classes (`bg-halo-primary`, `rounded-halo`, ...) via Tailwind v4's `@theme` directive. There is no build-time "active theme" setting — switching themes means changing an attribute.

### Built-in themes

| Theme | `data-theme` | Character |
|-------|--------------|-----------|
| **Halo** | `halo` (default) | Blue, neutral radius — the baseline |
| **Aurora** | `aurora` | Violet/teal accent, larger radius |
| **Eclipse** | `eclipse` | Dark background, same blue family as Halo |
| **Ember** | `ember` | Light, warm orange accent, sharper corners |
| **Nocturne** | `nocturne` | Dark, near-black background, emerald accent |
| **Luma** | `luma` | Warm coral/pink accent, aggressively rounded corners |
| **Flint** | `flint` | Monochrome slate accent, sharp square corners |

```html
<html data-theme="eclipse">
```

### Switching at runtime

`resources/js/init.js` registers an `Alpine.store('haloTheme', ...)` that persists the choice in `localStorage` and applies it to `<html>`:

```html
<button @click="$store.haloTheme.set('aurora')">Aurora</button>
```

Render the correct theme on first paint (before Alpine boots) by reading the configured default server-side:

```blade
<html data-theme="{{ config('halo.theme.default') }}">
```

### Adding a theme

Add a `[data-theme="yourname"]` block to `resources/css/theme.css` defining the same `--halo-*` variables as the existing themes, then add `'yourname'` to `config('halo.theme.available')` and to the `THEMES` array in `resources/js/init.js`.

---

## Customization

### Component-level

Every component merges any extra classes you pass, with the last-wins conflict resolution in `halo_merge_classes()` — a class you pass always overrides the component's own default for the same Tailwind utility family (`bg-*`, `text-*`, `p{x,y,...}-*`, etc.):

```blade
<x-halo::button class="px-10">
    Wider button
</x-halo::button>
```

### Defaults

`config/halo.php` sets each component's default variant/size:

```php
'defaults' => [
    'button' => ['variant' => 'primary', 'size' => 'md'],
],
```

### Helper functions

```php
// CVA-style variant recipe: base classes + variant/size maps + defaults
halo_variants($config, $props, $extraClass);

// Merge class strings, deduping conflicting utilities (last wins)
halo_merge_classes(...$classGroups);

// Read a component's configured default prop
halo_default('button', 'variant'); // 'primary'

// Read a theme.* config value
theme('radius'); // 'rounded-halo'
```

---

## Testing

HaloUI uses Pest:

```bash
composer test
composer test-coverage
```

```php
it('applies the default variant and size classes', function () {
    $html = renderComponent('button', ['slot' => 'Save']);

    expect($html)
        ->toHaveClass('bg-halo-primary')
        ->toHaveClass('px-4 py-2');
});
```

Every component has a render test in `tests/Components/`, including a regression test on Icon that guards against a real bug this rebuild fixed (an icon resolver that could recurse into itself).

---

## Contributing

Contributions are welcome — see [CONTRIBUTING.md](CONTRIBUTING.md).

```bash
git clone https://github.com/AureDulvresse/halo-ui.git
cd halo-ui
composer install
npm install

composer test
vendor/bin/pint --test
npm run build
```

---

## License

HaloUI is open-sourced software licensed under the [MIT license](LICENSE).

## Credits

- Inspired by [shadcn/ui](https://ui.shadcn.com)
- Built with [Tailwind CSS](https://tailwindcss.com)
- Powered by [Alpine.js](https://alpinejs.dev)
- Icons by [Blade Icons](https://github.com/blade-ui-kit/blade-icons)

---

<p align="center">
  <strong>HaloUI — Built with ❤️ by Aure Dulvresse</strong>
</p>
