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
	// esc_url_raw(), not esc_url(): this string isn't echoed directly, it's
	// handed to get_block_wrapper_attributes() below, which already runs
	// the combined style value through safecss_filter_attr() and
	// esc_attr(). esc_url() pre-encodes "&" to "&#038;" for immediate
	// display, which get_block_wrapper_attributes() would then re-encode
	// (and safecss_filter_attr() fails to recognize as a URL), silently
	// dropping the whole background-image declaration for any URL with a
	// query string.
	$styles['background-image'] = sprintf( 'url(%s)', esc_url_raw( $background_image ) );
}

$style_attr = '';

// Values are intentionally left unescaped here: get_block_wrapper_attributes()
// below runs the combined style string through safecss_filter_attr() (CSS
// safety) and esc_attr() (HTML-attribute escaping) itself. Escaping again
// here would double-encode values like the URL above.
foreach ( $styles as $property => $value ) {
	if ( null === $value || '' === $value ) {
		continue;
	}

	$style_attr .= sprintf( '%s:%s;', $property, $value );
}

$wrapper_attributes = get_block_wrapper_attributes( [ 'style' => $style_attr ] );
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by get_block_wrapper_attributes(). ?>>
	<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inner blocks are already rendered/escaped by core. ?>
</div>