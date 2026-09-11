---
layout: default
title: HaloUI
permalink: /
---

# HaloUI

**Modern, composable Blade UI component library for Laravel.** Elegant as shadcn/ui, native as Blade. Built with Tailwind CSS v4, Alpine.js, and Blade Icons.

61 components across Typography, Layouts, Form, Display, Feedback, Overlays, and Navigation — plus 7 themes, all real CSS custom properties, no config-only theming that never actually changes anything rendered.

## Get started

- [Installation](/installation) — `composer require`, then two tags in your layout
- [Theming](/theming) — how the `--halo-*` tokens work and how to add a theme
- [Components](#components) — full prop reference per component, linked below

## Features

- **Zero-config assets** — `@haloStyles`/`@haloScripts` serve the package's own built CSS/JS (Alpine.js bundled in), no `vendor:publish`, no Vite config
- **Anonymous Blade components** — no PHP classes, just `@props()` and a Blade file
- **Real runtime theming** — colors, radius, and dark/light are CSS custom properties mapped into Tailwind v4 via `@theme`
- **Copy-and-own** — works out of the box after `composer require`; `php artisan halo:install` is an optional eject for full customization
- **Accessible by default** — ARIA attributes and keyboard behavior are part of a component's first version

## Components

See the **Components** and **Layouts** sections in the navigation above for the full list, or browse the [source on GitHub](https://github.com/AureDulvresse/halo-ui/tree/main/resources/views/components/halo).

## Source

[github.com/AureDulvresse/halo-ui](https://github.com/AureDulvresse/halo-ui)
