<?php
/**
 * Server-side render for the `pv-blocks-suite/card` block.
 *
 * @package PV\BlocksSuite
 *
 * @var array<string, mixed> $attributes Block attributes.
 * @var string               $content    Rendered inner blocks markup (unused, block has no InnerBlocks).
 * @var WP_Block              $block     Block instance.
 */

declare( strict_types = 1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$image_url               = (string) ( $attributes['imageUrl'] ?? '' );
$image_alt               = (string) ( $attributes['imageAlt'] ?? '' );
$heading                 = (string) ( $attributes['heading'] ?? '' );
$heading_level           = (int) ( $attributes['headingLevel'] ?? 3 );
$heading_level           = max( 2, min( 6, $heading_level ) );
$description             = (string) ( $attributes['description'] ?? '' );
$button_text             = (string) ( $attributes['buttonText'] ?? '' );
$button_url              = (string) ( $attributes['buttonUrl'] ?? '' );
$button_opens_in_new_tab = (bool) ( $attributes['buttonOpensInNewTab'] ?? false );

// The `style.border.radius` attribute isn't ours: it's injected
// automatically by the `supports.__experimentalBorder.radius`
// declaration in block.json. See cta/render.php for the full
// explanation of this pattern (single linked value vs. per-corner array,
// and why overflow needs to be forced to clip the image to the radius).
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
	$has_border_radius ? [ 'style' => 'overflow:hidden;' ] : []
);
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by get_block_wrapper_attributes(). ?>>
	<?php if ( $image_url ) : ?>
		<img
			class="wp-block-pv-blocks-suite-card__image"
			src="<?php echo esc_url( $image_url ); ?>"
			alt="<?php echo esc_attr( $image_alt ); ?>"
		/>
	<?php endif; ?>

	<?php if ( $heading ) : ?>
		<h<?php echo absint( $heading_level ); ?> class="wp-block-pv-blocks-suite-card__heading"><?php echo wp_kses_post( $heading ); ?></h<?php echo absint( $heading_level ); ?>>
	<?php endif; ?>

	<?php if ( $description ) : ?>
		<p class="wp-block-pv-blocks-suite-card__description"><?php echo wp_kses_post( $description ); ?></p>
	<?php endif; ?>

	<?php if ( $button_text && $button_url ) : ?>
		<a
			class="wp-block-pv-blocks-suite-card__button"
			href="<?php echo esc_url( $button_url ); ?>"
			<?php if ( $button_opens_in_new_tab ) : ?>
				target="_blank" rel="noopener noreferrer"
			<?php endif; ?>
		>
			<?php echo wp_kses_post( $button_text ); ?>
			<?php if ( $button_opens_in_new_tab ) : ?>
				<span class="wp-block-pv-blocks-suite-card__visually-hidden"><?php esc_html_e( '(opens in a new tab)', 'pv-blocks-suite' ); ?></span>
			<?php endif; ?>
		</a>
	<?php endif; ?>
</div>
