<?php
/**
 * Server-side render for the `pv-blocks-suite/accordion` block.
 *
 * @package PV\BlocksSuite
 *
 * @var array<string, mixed> $attributes Block attributes.
 * @var string               $content    Rendered inner blocks markup (each accordion item renders itself via its own render.php).
 * @var WP_Block              $block     Block instance.
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Mirrors WordPress core's own accordion block: grouping the items under
// role="group" gives assistive tech a contextual "group of N items"
// announcement when entering the list, without needing a full landmark.
$wrapper_attributes = get_block_wrapper_attributes( [ 'role' => 'group' ] );
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by get_block_wrapper_attributes(). ?>>
	<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inner blocks (accordion items) are already rendered/escaped by their own render.php. ?>
</div>
