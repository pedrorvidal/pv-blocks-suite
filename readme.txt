=== PV Blocks Suite ===
Contributors: pedrorvidal
Tags: gutenberg, blocks, block-editor, page-builder, full-site-editing
Requires at least: 6.5
Tested up to: 7.1.1
Requires PHP: 8.2
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A suite of native, production-ready Gutenberg blocks for building content-rich pages without a page-builder plugin.

== Description ==

PV Blocks Suite bundles a growing collection of production-ready Gutenberg blocks into a single, lightweight plugin. Every block is a **dynamic block** — rendered server-side in PHP — built with strictly-typed PHP 8.2, React, and TypeScript on top of WordPress' native block-editor APIs, with no page-builder dependencies and no bloat.

Blocks appear in their own **PV Blocks Suite** category in the inserter, positioned right after WordPress' first default category. Included blocks:

* **Container** — a reusable wrapper for composing layout sections, with padding, background, max-width, and alignment controls.
* **CTA** — a call-to-action block with a rich-text heading, description, and configurable button.
* **Accordion** — collapsible content sections.
* **Pricing Table** — a table of pricing plans.
* **Card Grid** — a responsive grid of content cards.
* **Alert Box** — an inline notice with Info/Success/Warning/Error variants.
* **Timeline** — a chronological list of events.
* **Before/After** — an image comparison slider.
* **Testimonials** — a grid of customer testimonials.
* **Stats Counter** — a row of highlighted statistics.
* **Tabs** — tabbed content panels.
* **Team** — a grid of team member profiles.

= Why a suite, not separate plugins? =

Shipping every block as one plugin keeps installation simple, lets blocks share components and registration logic, and avoids inter-plugin dependency management.

== Installation ==

1. In the WordPress admin, go to **Plugins → Add New Plugin → Upload Plugin** and upload the plugin zip, or extract it into `wp-content/plugins/` manually.
2. Activate **PV Blocks Suite** from the Plugins screen, or via WP-CLI: `wp plugin activate pv-blocks-suite`.
3. Blocks appear in the block inserter under the **PV Blocks Suite** category — no further configuration is required.

== Frequently Asked Questions ==

= Does this plugin require any other page-builder plugin? =

No. Every block is built entirely on WordPress' native block-editor APIs.

= Is the output translated? =

Yes. The plugin is fully translatable (text domain `pv-blocks-suite`), with a Portuguese (Brazil) translation included out of the box. WordPress picks up the right translation automatically based on the site's language setting.

== Changelog ==

= 1.0.1 =
* Security: added the missing direct-access guard to two PHP files that lacked it.
* Security: added a .distignore so dev-only files are excluded from packaged releases.
* Compliance: added the plugin header fields required for WordPress.org Plugin Check (Requires at least, Tested up to, License URI, Domain Path).
* Compliance: added this readme.txt in the standard WordPress.org format.
* Accessibility/i18n: fixed the Alert Box block's screen-reader label so translators control the full label string instead of a hardcoded separator being appended to it.
* Code style: normalized `declare(strict_types=1)` spacing for consistency across the codebase.

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.0.1 =
Compliance, security-hardening, and translation-readiness update. No behavior changes for existing content.
