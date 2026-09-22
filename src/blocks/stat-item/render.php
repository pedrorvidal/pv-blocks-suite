<?php
/**
 * Server-side render for the `pv-blocks-suite/stat-item` block.
 *
 * Progressive enhancement matters here: the server always renders the
 * real, final formatted value (never "0") so a visitor whose JavaScript
 * fails to load still sees the correct number. Only once the element
 * actually scrolls into view does view.ts visually reset the display to
 * 0 and animate it back up — a page where the block is already in the
 * viewport on first paint will briefly show 0 -> count-up -> final value,
 * while a page where JS never loads simply always shows the final value,
 * correctly.
 *
 * @package PV\BlocksSuite
 *
 * @var array<string, mixed> $attributes Block attributes.
 * @var string               $content    Rendered inner blocks markup (unused, block has no InnerBlocks).
 * @var WP_Block              $block     Block instance; carries context provided by the parent stats-counter.
 */

declare( strict_types = 1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$value    = isset( $attributes['value'] ) ? (float) $attributes['value'] : 100.0;
$decimals = isset( $attributes['decimals'] ) ? (int) $attributes['decimals'] : 0;
$decimals = max( 0, min( 2, $decimals ) );
$prefix   = (string) ( $attributes['prefix'] ?? '' );
$suffix   = (string) ( $attributes['suffix'] ?? '' );
$label    = (string) ( $attributes['label'] ?? '' );

// Block Context API (providesContext/usesContext) — set by the
// stats-counter parent, distinct from and unrelated to the Interactivity
// API's own same-named data-wp-context used below. Only populated when a
// real matching ancestor exists, so always guard with a default, same
// rule accordion-item already established for groupId/allowMultipleOpen.
$duration = isset( $block->context['pv-blocks-suite/animationDuration'] )
	? (int) $block->context['pv-blocks-suite/animationDuration']
	: 2000;

$formatted_value = $prefix . number_format( $value, $decimals ) . $suffix;

// The `style.border.radius` attribute isn't ours: it's injected
// automatically by the `supports.__experimentalBorder.radius`
// declaration in block.json. See cta/render.php for the full
// explanation of this pattern.
$border_radius     = $attributes['style']['border']['radius'] ?? null;
$has_border_radius = false;

if ( is_string( $border_radius ) && '' !== $border_radius ) {
	$has_border_radius = true;
} elseif ( is_array( $border_radius ) ) {
	foreach ( $border_radius as $corner_value ) {
		if ( is_string( $corner_value ) && '' !== $corner_value ) {
			$has_border_radius = true;
			break;
		}
	}
}

$wrapper_attributes = get_block_wrapper_attributes(
	[
		'style'               => $has_border_radius ? 'overflow:hidden;' : '',
		'data-wp-interactive' => 'pv-blocks-suite/stat-item',
		'data-wp-init'        => 'callbacks.startObserving',
	]
);

$context = wp_interactivity_data_wp_context(
	[
		'targetValue'  => $value,
		'displayValue' => $value,
		'decimals'     => $decimals,
		'prefix'       => $prefix,
		'suffix'       => $suffix,
		'duration'     => $duration,
		'hasAnimated'  => false,
	]
);
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by get_block_wrapper_attributes(). ?> <?php echo $context; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by wp_interactivity_data_wp_context(). ?>>
	<span class="wp-block-pv-blocks-suite-stat-item__value" data-wp-text="state.formattedValue"><?php echo esc_html( $formatted_value ); ?></span>
	<?php if ( '' !== trim( wp_strip_all_tags( $label ) ) ) : ?>
		<p class="wp-block-pv-blocks-suite-stat-item__label"><?php echo wp_kses_post( $label ); ?></p>
	<?php endif; ?>
</div>
