<?php
/**
 * Server-side render for the `pv-blocks-suite/testimonials` block.
 *
 * @package PV\BlocksSuite
 *
 * @var array<string, mixed> $attributes Block attributes.
 * @var string               $content    Rendered inner blocks markup (each testimonial renders itself via its own render.php).
 * @var WP_Block              $block     Block instance.
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$columns = (int) ( $attributes['columns'] ?? 3 );
$columns = max( 2, min( 4, $columns ) );

// A CSS custom property, not a literal grid-template-columns value: the
// actual column *counts per breakpoint* (mobile/tablet) stay in
// style.scss's media queries, this only controls the desktop count.
$wrapper_attributes = get_block_wrapper_attributes(
	[ 'style' => sprintf( '--testimonials-columns:%d;', $columns ) ]
);
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by get_block_wrapper_attributes(). ?>>
	<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inner blocks (testimonial items) are already rendered/escaped by their own render.php. ?>
</div>
