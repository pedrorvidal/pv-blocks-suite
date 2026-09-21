<?php
/**
 * Server-side render for the `pv-blocks-suite/timeline-item` block.
 *
 * @package PV\BlocksSuite
 *
 * @var array<string, mixed> $attributes Block attributes.
 * @var string               $content    Rendered inner blocks markup (unused, block has no InnerBlocks).
 * @var WP_Block              $block     Block instance.
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$date          = (string) ( $attributes['date'] ?? '' );
$heading       = (string) ( $attributes['heading'] ?? '' );
$heading_level = (int) ( $attributes['headingLevel'] ?? 3 );
$heading_level = max( 2, min( 6, $heading_level ) );
$description   = (string) ( $attributes['description'] ?? '' );

$wrapper_attributes = get_block_wrapper_attributes();
?>
<li <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by get_block_wrapper_attributes(). ?>>
	<span class="wp-block-pv-blocks-suite-timeline-item__dot"></span>
	<div class="wp-block-pv-blocks-suite-timeline-item__content">
		<?php if ( $date ) : ?>
			<span class="wp-block-pv-blocks-suite-timeline-item__date"><?php echo wp_kses_post( $date ); ?></span>
		<?php endif; ?>

		<?php if ( $heading ) : ?>
			<h<?php echo absint( $heading_level ); ?> class="wp-block-pv-blocks-suite-timeline-item__heading"><?php echo wp_kses_post( $heading ); ?></h<?php echo absint( $heading_level ); ?>>
		<?php endif; ?>

		<?php if ( $description ) : ?>
			<p class="wp-block-pv-blocks-suite-timeline-item__description"><?php echo wp_kses_post( $description ); ?></p>
		<?php endif; ?>
	</div>
</li>
