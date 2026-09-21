# PV Blocks Suite

**A suite of native Gutenberg blocks for WordPress content creators.**

![Version](https://img.shields.io/badge/version-0.1.0-informational.svg)
[![License: GPL v2+](https://img.shields.io/badge/license-GPL--2.0--or--later-blue.svg)](https://www.gnu.org/licenses/gpl-2.0.html)
[![PHP](https://img.shields.io/badge/PHP-%3E%3D8.2-777bb4.svg)](https://www.php.net/)
[![WordPress](https://img.shields.io/badge/WordPress-Block%20Editor-21759b.svg)](https://wordpress.org/)
[![React](https://img.shields.io/badge/React-19-61dafb.svg)](https://react.dev/)
[![TypeScript](https://img.shields.io/badge/TypeScript-7-3178c6.svg)](https://www.typescriptlang.org/)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES2020-f7df1e.svg)](https://developer.mozilla.org/docs/Web/JavaScript)
[![Sass](https://img.shields.io/badge/Sass-CSS-cc6699.svg)](https://sass-lang.com/)

PV Blocks Suite bundles a growing collection of production-ready Gutenberg
blocks into a single, lightweight plugin. Every block is a **dynamic block**
— rendered server-side in PHP — built with strictly-typed PHP 8.2, React, and
TypeScript on top of WordPress' native block-editor APIs, with no page-builder
dependencies and no bloat.

## Why a suite, not separate plugins?

Shipping every block as one plugin keeps installation simple for clients,
lets blocks share components and registration logic, and avoids inter-plugin
dependency management. A block only gets split into its own plugin if it
grows into a standalone product, needs a heavy unique dependency, or requires
different licensing.

## Blocks

Every block below lives in its own **PV Blocks Suite** category in the
inserter, positioned right after WordPress' first default category.

### Container

A reusable wrapper block for composing layout sections.

- Padding control for each side (top, right, bottom, left)
- Solid background color or background image
- Configurable max-width with automatic horizontal centering
- Wide and full alignment support
- Nests any other blocks via `InnerBlocks`

### CTA

A call-to-action block for driving a single, focused action.

- Rich-text heading (configurable H2–H6 level) and description with
  inline formatting (bold, italic)
- One button with configurable label, URL, and "open in new tab" (with a
  screen-reader announcement for the new-tab behavior and a hover
  animation that works with any button color)
- Text alignment control (left, center, right, justify)
- Independent background and text colors for both the block and the
  button, with a built-in contrast warning for accessibility
- Optional background image
- Native padding and border-radius controls, with automatic content
  clipping (`overflow: hidden`) whenever a radius is applied

### Accordion

A list of collapsible items, ideal for FAQs.

- Any number of items, added, reordered, duplicated, or removed through
  the standard block toolbar
- Each item's panel accepts real nested blocks (paragraphs, images,
  lists, …), not just plain text
- Built on native HTML `<details>`/`<summary>` — accessible and
  JavaScript-free
- Each question is a real, configurable H2–H6 heading, so screen-reader
  users can navigate the list by heading
- Smooth open/close height animation and a rotating caret indicator,
  both pure CSS
- Optional "only one item open at a time" behavior
- Per-item "open by default" control

### Pricing Table

A row of pricing cards for comparing plans.

- Any number of plans, added, reordered, duplicated, or removed through
  the standard block toolbar
- Each plan: name (configurable H2–H6 heading), price with an optional
  period (e.g. "/month"), a short description, and a button (URL,
  "open in new tab" with a screen-reader announcement)
- The feature list is real `InnerBlocks` — defaults to a native list,
  editable like any other WordPress list
- "Featured plan" highlight (border/shadow) with a customizable badge
  label and an optional background/text color pair (with a contrast
  warning) just for that card
- Native padding and border-radius controls

### Card Grid

A responsive grid of cards, ideal for services, portfolios, or blog
teasers.

- Any number of cards, added, reordered, duplicated, or removed through
  the standard block toolbar
- Configurable column count (2–4), responsive down to a single column on
  small screens
- Each card: an image with editable alt text, a heading (configurable
  H2–H6), a description, and a button (URL, "open in new tab" with a
  screen-reader announcement)
- Native padding and border-radius controls

### Alert Box

A colored callout for highlighting important information.

- Four semantic types — info, success, warning, error — each with a
  fixed, accessible color pair and its own icon, so meaning stays
  consistent everywhere the block is used
- Optional heading and a message
- Icon can be toggled off; a screen-reader-only label always announces
  the alert's type regardless, since color and icon alone aren't
  accessible information
- Native padding and border-radius controls

### Timeline

A vertical timeline for showing a sequence of events or steps.

- Any number of events, added, reordered, duplicated, or removed through
  the standard block toolbar
- Renders as a real ordered list, with a connecting line and dot marker
  drawn in pure CSS
- Each event: an optional image with editable alt text, a date or label,
  a heading (configurable H2–H6), and a description

### Before/After

An interactive slider for comparing a before and after image.

- Before and after images, each with editable alt text
- Move the slider by dragging the handle, clicking anywhere on the image,
  or using the arrow keys — fully keyboard-accessible with a proper ARIA
  slider role
- Configurable initial position and aspect ratio (1:1, 4:3, 16:9, 21:9, 3:2)
- Optional, editable "before"/"after" labels
- Built on the native WordPress Interactivity API — no page reload, no
  custom framework
- Native padding and border-radius controls

### Testimonials

A responsive grid of testimonials, ideal for building trust with social
proof.

- Any number of testimonials, added, reordered, duplicated, or removed
  through the standard block toolbar
- Configurable column count (2–4), responsive down to a single column on
  small screens
- Each testimonial: a quote, the author's name, an optional role or
  company, an optional avatar with editable alt text, and an optional
  1–5 star rating
- Renders as a real `<blockquote>`/`<figcaption>`/`<cite>`, not generic
  `<div>`s, for correctly-structured, accessible markup
- Native padding and border-radius controls

### Stats Counter

A grid of animated stat counters, ideal for social proof ("500+
Clients", "20 Years", "99% Satisfaction").

- Any number of stats, added, reordered, duplicated, or removed through
  the standard block toolbar
- Configurable column count (2–4), responsive down to a single column on
  small screens
- Each stat: a target number with optional prefix/suffix and decimal
  places, and a label
- Numbers animate counting up from zero the first time they scroll into
  view, with a shared, configurable animation duration for the whole
  grid; the real final value is always rendered server-side first, so
  the correct number is visible even if JavaScript never loads
- Respects the visitor's reduced-motion preference — counters display
  their final value immediately instead of animating
- Native padding and border-radius controls

### Tabs

A standard, accessible tabbed interface for organizing content into
switchable sections.

- Any number of tabs, added, reordered, duplicated, or removed through
  the standard block toolbar
- Each tab's panel accepts real nested blocks, not just plain text
- Full keyboard support — arrow keys, Home, and End move between tabs
  and switch the active panel, following the WAI-ARIA Tabs pattern
- Correct ARIA roles and attributes throughout (`tablist`/`tab`/
  `tabpanel`, `aria-selected`, roving `tabindex`)
- If JavaScript never loads, every tab's content is still shown, stacked
  and fully readable, rather than a broken widget

## Requirements

- PHP 8.2 or later
- WordPress with the Block Editor (Gutenberg) enabled
- [Node.js](https://nodejs.org/) and npm (development only)
- [Composer](https://getcomposer.org/) (development only)

## Installation

1. Copy (or clone) this repository into `wp-content/plugins/pv-blocks-suite`
   on your WordPress installation.
2. Install dependencies and build the production assets:

   ```bash
   composer install --no-dev
   npm install
   npm run build
   ```

3. Activate **PV Blocks Suite** from the WordPress admin's Plugins screen,
   or via WP-CLI:

   ```bash
   wp plugin activate pv-blocks-suite
   ```

Blocks appear in the block inserter under their respective categories —
no further configuration is required.

## Development

### Stack

- **PHP 8.2+** with `declare(strict_types=1)` and typed signatures throughout
- **React + TypeScript** for the block-editor UI, compiled by
  [`@wordpress/scripts`](https://www.npmjs.com/package/@wordpress/scripts)
- **Native Gutenberg APIs** (`@wordpress/blocks`, `@wordpress/block-editor`,
  `@wordpress/components`) — no third-party editor frameworks

### Project structure

```
pv-blocks-suite/
├── pv-blocks-suite.php     # Plugin bootstrap
├── composer.json           # PHPStan, PSR-4 autoloading
├── package.json
├── phpstan.neon
├── phpcs.xml.dist
├── .wp-env.json             # Development environment (port 8888)
├── .wp-env.tests.json       # Test environment (port 8889)
├── includes/
│   ├── class-block-loader.php     # Auto-registers blocks from src/blocks/*/block.json
│   └── class-block-categories.php # Registers the "PV Blocks Suite" inserter category
├── src/
│   ├── blocks/
│   │   └── {block-name}/
│   │       ├── block.json
│   │       ├── edit.tsx
│   │       ├── render.php   # Dynamic (server-side) render
│   │       ├── style.scss
│   │       └── test/
│   └── shared/
│       ├── components/
│       └── hooks/
└── build/                    # Compiled assets (generated)
```

Every block lives in its own folder under `src/blocks/` and is discovered
automatically by `Block_Loader` — adding a new block never requires touching
the bootstrap.

### Local environment

Local development runs on [`@wordpress/env`](https://www.npmjs.com/package/@wordpress/env)
(`wp-env`), with separate configurations for development and automated
tests:

| Command                      | Description                                  |
| ----------------------------- | --------------------------------------------- |
| `npm run env:start`           | Start the development site (`localhost:8888`) |
| `npm run env:start:tests`     | Start the test environment (`localhost:8889`) |
| `npm run env:stop`            | Stop the development site                     |
| `npm run env:stop:tests`      | Stop the test environment                     |
| `npm run env:destroy`         | Remove the development environment            |
| `npm run env:destroy:tests`   | Remove the test environment                   |
| `npm run start`               | Start the webpack dev build in watch mode     |
| `npm run build`               | Produce a production build                    |

### Quality tooling

Every change is expected to pass the full quality gate before merging:

| Tool                           | Command                | What it checks                                         |
| ------------------------------- | ----------------------- | -------------------------------------------------------- |
| **PHPStan** (level 5+)          | `npm run lint:php:stan` | Static analysis, with WordPress core stubs               |
| **PHP_CodeSniffer** (WPCS)      | `npm run lint:php`      | WordPress-Extra coding standards                         |
| **PHPUnit** (`wp-phpunit`)      | `npm run test:php`      | Server-side rendering, real `WP_UnitTestCase` integration |
| **Jest** (`wp-scripts`)         | `npm run test:js`       | Editor component behavior via Testing Library             |
| **TypeScript**                  | `npm run typecheck`     | Type-checking only, build stays on Babel                 |

Auto-fixable WPCS violations can be corrected with `npm run lint:php:fix`.

## License

Released under the [GPL-2.0-or-later](https://www.gnu.org/licenses/gpl-2.0.html)
license, in line with the WordPress plugin ecosystem.
