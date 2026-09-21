<?php
/**
 * Server-side render for the `pv-blocks-suite/tab-item` block.
 *
 * Renders this tab's PANEL only — its own `label` is not rendered here at
 * all, it's hoisted by the parent `tabs` block into a shared tablist (see
 * tabs/render.php) to satisfy the ARIA tabs pattern, which requires every
 * tab button grouped together separately from every panel.
 *
 * Deliberately does NOT set a static `hidden` attribute here, even though
 * only the first tab should be visible by default: a tab-item has no
 * simple way to know "am I the first sibling" purely server-side without
 * real extra plumbing (the parent would need to sync a "first child id"
 * back down via the Block Context API, kept in sync by an editor-side
 * watcher — avoidable complexity for a minor tradeoff). The upside: this
 * gives a genuinely reasonable no-JS fallback for free — if JavaScript
 * never loads, every tab's content simply shows stacked, in reading
 * order, fully readable, rather than a broken/non-functional widget. The
 * real cost: JS-enabled visitors see a brief flash of every panel stacked
 * before hydration completes and data-wp-bind--hidden (below) hides all
 * but the active one — a normal, widely-accepted cost of any
 * client-hydrated interactivity, not specific to this implementation.
 *
 * No "anchor" block support here (unlike most other content blocks in
 * this project): get_block_wrapper_attributes()'s own `id` merge callback
 * always prefers a non-empty extra attribute over its auto-generated one
 * (confirmed by reading wp-includes/class-wp-block-supports.php directly),
 * and this block always passes its own computed `id` (below) so the
 * aria-controls/aria-labelledby wiring never breaks — meaning a
 * user-set custom HTML anchor would always be silently overridden. Rather
 * than expose an "Advanced > HTML anchor" control that quietly never
 * works, the support is left off entirely.
 *
 * @package PV\BlocksSuite
 *
 * @var array<string, mixed> $attributes Block attributes.
 * @var string               $content    Rendered inner blocks markup (this tab's own panel content).
 * @var WP_Block              $block     Block instance.
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$tab_id = (string) ( $attributes['tabId'] ?? '' );

$wrapper_attributes = get_block_wrapper_attributes(
	[
		'role'                 => 'tabpanel',
		'id'                   => 'pv-tab-panel-' . $tab_id,
		'aria-labelledby'      => 'pv-tab-button-' . $tab_id,
		'tabindex'             => '0',
		'data-wp-bind--hidden' => 'state.isHidden',
	]
);

$context = wp_interactivity_data_wp_context( [ 'tabId' => $tab_id ] );
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by get_block_wrapper_attributes(). ?> <?php echo $context; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by wp_interactivity_data_wp_context(). ?>>
	<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inner blocks are already rendered/escaped by core. ?>
</div>
