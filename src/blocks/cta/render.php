<?php
/**
 * Server-side render for the `pv-blocks-suite/cta` block.
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

$heading                 = (string) ( $attributes['heading'] ?? '' );
$description             = (string) ( $attributes['description'] ?? '' );
$button_text             = (string) ( $attributes['buttonText'] ?? '' );
$button_url              = (string) ( $attributes['buttonUrl'] ?? '' );
$button_opens_in_new_tab = (bool) ( $attributes['buttonOpensInNewTab'] ?? false );
$text_align              = (string) ( $attributes['textAlign'] ?? '' );
$background_color        = (string) ( $attributes['backgroundColor'] ?? '' );
$background_image        = (string) ( $attributes['backgroundImage'] ?? '' );
$text_color              = (string) ( $attributes['textColor'] ?? '' );
$button_background_color = (string) ( $attributes['buttonBackgroundColor'] ?? '' );
$button_text_color       = (string) ( $attributes['buttonTextColor'] ?? '' );

// The `style.border.radius` attribute isn't ours: it's injected
// automatically by the `supports.border.radius` declaration in block.json,
// and is either a single linked value or (once a user unlinks the corners
// in the Inspector) a per-corner array. Either shape means at least one
// corner is rounded, so content should be clipped to it.
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

/** @var array<string, string|null> $styles CSS property => value map, null entries are skipped. */
$styles = [
	'text-align' => $text_align ?: null,
	'color'      => $text_color ?: null,
	'overflow'   => $has_border_radius ? 'hidden' : null,
];

if ( $background_color ) {
	$styles['background-color'] = $background_color;
}

if ( $background_image ) {
	// esc_url_raw(), not esc_url(): handed to get_block_wrapper_attributes()
	// below, which already runs the combined style value through
	// safecss_filter_attr() and esc_attr() itself. See the `container`
	// block's render.php for the full explanation of this pattern.
	$styles['background-image'] = sprintf( 'url(%s)', esc_url_raw( $background_image ) );
}

$style_attr = '';

// Values are intentionally left unescaped here: get_block_wrapper_attributes()
// below runs the combined style string through safecss_filter_attr() and
// esc_attr() itself. Escaping again here would double-encode values.
foreach ( $styles as $property => $value ) {
	if ( null === $value || '' === $value ) {
		continue;
	}

	$style_attr .= sprintf( '%s:%s;', $property, $value );
}

$wrapper_attributes = get_block_wrapper_attributes( [ 'style' => $style_attr ] );

// Unlike the wrapper above, the button isn't run through
// get_block_wrapper_attributes(), so its style value needs its own
// safecss_filter_attr() + esc_attr() pass before being echoed.
$button_styles = [];

if ( $button_background_color ) {
	$button_styles['background-color'] = $button_background_color;
}

if ( $button_text_color ) {
	$button_styles['color'] = $button_text_color;
}

$button_style_attr = '';

foreach ( $button_styles as $property => $value ) {
	$button_style_attr .= sprintf( '%s:%s;', $property, $value );
}

$button_style_attr = safecss_filter_attr( $button_style_attr );
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by get_block_wrapper_attributes(). ?>>
	<?php if ( $heading ) : ?>
		<h2 class="wp-block-pv-blocks-suite-cta__heading"><?php echo wp_kses_post( $heading ); ?></h2>
	<?php endif; ?>

	<?php if ( $description ) : ?>
		<p class="wp-block-pv-blocks-suite-cta__description"><?php echo wp_kses_post( $description ); ?></p>
	<?php endif; ?>

	<?php if ( $button_text && $button_url ) : ?>
		<a
			class="wp-block-pv-blocks-suite-cta__button"
			href="<?php echo esc_url( $button_url ); ?>"
			<?php if ( $button_style_attr ) : ?>
				style="<?php echo esc_attr( $button_style_attr ); ?>"
			<?php endif; ?>
			<?php if ( $button_opens_in_new_tab ) : ?>
				target="_blank" rel="noopener noreferrer"
			<?php endif; ?>
		><?php echo wp_kses_post( $button_text ); ?></a>
	<?php endif; ?>
</div>
