<?php
/**
 * Server-side render for the `pv-blocks-suite/container` block.
 *
 * @package PV\BlocksSuite
 *
 * @var array<string, mixed> $attributes Block attributes.
 * @var string               $content    Rendered inner blocks markup.
 * @var WP_Block              $block     Block instance.
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$padding_top      = (string) ( $attributes['paddingTop'] ?? '' );
$padding_bottom   = (string) ( $attributes['paddingBottom'] ?? '' );
$padding_left     = (string) ( $attributes['paddingLeft'] ?? '' );
$padding_right    = (string) ( $attributes['paddingRight'] ?? '' );
$background_color = (string) ( $attributes['backgroundColor'] ?? '' );
$background_image = (string) ( $attributes['backgroundImage'] ?? '' );
$max_width        = (string) ( $attributes['maxWidth'] ?? '' );

/** @var array<string, string|null> $styles CSS property => value map, null entries are skipped. */
$styles = [
	'padding-top'    => $padding_top ?: null,
	'padding-bottom' => $padding_bottom ?: null,
	'padding-left'   => $padding_left ?: null,
	'padding-right'  => $padding_right ?: null,
	'max-width'      => $max_width ?: null,
	'margin-left'    => $max_width ? 'auto' : null,
	'margin-right'   => $max_width ? 'auto' : null,
];

if ( $background_color ) {
	$styles['background-color'] = $background_color;
}

if ( $background_image ) {
	$styles['background-image'] = sprintf( 'url(%s)', esc_url( $background_image ) );
}

$style_attr = '';

foreach ( $styles as $property => $value ) {
	if ( null === $value || '' === $value ) {
		continue;
	}

	$style_attr .= sprintf( '%s:%s;', esc_attr( $property ), esc_attr( $value ) );
}

$wrapper_attributes = get_block_wrapper_attributes( [ 'style' => $style_attr ] );
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by get_block_wrapper_attributes(). ?>>
	<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inner blocks are already rendered/escaped by core. ?>
</div>