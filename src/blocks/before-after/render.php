<?php
/**
 * Server-side render for the `pv-blocks-suite/before-after` block.
 *
 * Two nested wrapper levels, not one: the outer div (from
 * get_block_wrapper_attributes()) carries the block's own "chrome" —
 * native padding/border-radius — while the inner `__stage` div is the
 * actual interactive comparison area (position: relative, a fixed
 * aspect-ratio, unconditional overflow: hidden). Both the images'
 * `inset: 0` and the pointer-position math in view.ts use the `__stage`
 * div as their one reference frame. If the interactive/pointer element
 * were the outer (padded) wrapper instead, a click inside the padding
 * gutter would compute a slider ratio against the full border-box rect
 * while the images (whose `position: absolute` containing block is the
 * ancestor's padding box, per the CSS spec) actually occupy a smaller
 * area — a real, if minor, misalignment. Splitting the two levels avoids
 * that discrepancy entirely.
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

$before_image_url = (string) ( $attributes['beforeImageUrl'] ?? '' );
$before_image_alt = (string) ( $attributes['beforeImageAlt'] ?? '' );
$after_image_url  = (string) ( $attributes['afterImageUrl'] ?? '' );
$after_image_alt  = (string) ( $attributes['afterImageAlt'] ?? '' );
$before_label     = (string) ( $attributes['beforeLabel'] ?? '' );
$after_label      = (string) ( $attributes['afterLabel'] ?? '' );
$show_labels      = (bool) ( $attributes['showLabels'] ?? true );
$aspect_ratio_key = (string) ( $attributes['aspectRatio'] ?? '16:9' );

$initial_position = isset( $attributes['initialPosition'] ) ? (float) $attributes['initialPosition'] : 50.0;
$initial_position = max( 0.0, min( 100.0, $initial_position ) );

/** @var array<string, string> $aspect_ratio_map Attribute value => CSS `aspect-ratio` value. */
$aspect_ratio_map = [
	'1:1'  => '1 / 1',
	'4:3'  => '4 / 3',
	'16:9' => '16 / 9',
	'21:9' => '21 / 9',
	'3:2'  => '3 / 2',
];
$aspect_ratio_css = $aspect_ratio_map[ $aspect_ratio_key ] ?? $aspect_ratio_map['16:9'];

// The `style.border.radius` attribute isn't ours: it's injected
// automatically by the `supports.__experimentalBorder.radius` declaration
// in block.json, and is either a single linked value or (once a user
// unlinks the corners in the Inspector) a per-corner array. Either shape
// means at least one corner is rounded, so the outer wrapper needs
// `overflow: hidden` to actually clip the (rectangular) stage inside it —
// see the `cta`/`card` blocks' render.php for the same pattern.
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

$wrapper_style = $has_border_radius ? 'overflow:hidden;' : '';

$wrapper_attributes = get_block_wrapper_attributes( [ 'style' => $wrapper_style ] );

// The `__stage` div is *not* the block's own wrapper, so it doesn't go
// through get_block_wrapper_attributes() — that helper is meant to be
// called exactly once per block render, on the single top-level element;
// calling it again here would duplicate the block's own class/anchor id/
// alignment classes onto this inner element too.
$stage_style = sprintf( 'aspect-ratio:%s;', $aspect_ratio_css );

$context = wp_interactivity_data_wp_context(
	[
		'position'   => $initial_position,
		'isDragging' => false,
	]
);

$clip_path = sprintf( 'inset(0 %s%% 0 0)', 100 - $initial_position );
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by get_block_wrapper_attributes(). ?>>
	<div
		class="wp-block-pv-blocks-suite-before-after__stage"
		style="<?php echo esc_attr( $stage_style ); ?>"
		data-wp-interactive="pv-blocks-suite/before-after"
		data-wp-on--pointerdown="actions.onPointerDown"
		data-wp-on-window--pointermove="actions.onPointerMove"
		data-wp-on-window--pointerup="actions.onPointerUp"
		<?php echo $context; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by wp_interactivity_data_wp_context(). ?>
	>
		<img
			class="wp-block-pv-blocks-suite-before-after__image wp-block-pv-blocks-suite-before-after__image--before"
			src="<?php echo esc_url( $before_image_url ); ?>"
			alt="<?php echo esc_attr( $before_image_alt ); ?>"
		/>
		<div
			class="wp-block-pv-blocks-suite-before-after__after-wrap"
			style="clip-path:<?php echo esc_attr( $clip_path ); ?>;"
			data-wp-style--clip-path="state.clipPath"
		>
			<img
				class="wp-block-pv-blocks-suite-before-after__image wp-block-pv-blocks-suite-before-after__image--after"
				src="<?php echo esc_url( $after_image_url ); ?>"
				alt="<?php echo esc_attr( $after_image_alt ); ?>"
			/>
		</div>

		<?php if ( $show_labels ) : ?>
			<?php if ( '' !== trim( wp_strip_all_tags( $before_label ) ) ) : ?>
				<span class="wp-block-pv-blocks-suite-before-after__label wp-block-pv-blocks-suite-before-after__label--before"><?php echo wp_kses_post( $before_label ); ?></span>
			<?php endif; ?>
			<?php if ( '' !== trim( wp_strip_all_tags( $after_label ) ) ) : ?>
				<span class="wp-block-pv-blocks-suite-before-after__label wp-block-pv-blocks-suite-before-after__label--after"><?php echo wp_kses_post( $after_label ); ?></span>
			<?php endif; ?>
		<?php endif; ?>

		<div
			class="wp-block-pv-blocks-suite-before-after__handle"
			role="slider"
			tabindex="0"
			aria-label="<?php esc_attr_e( 'Before and after comparison position', 'pv-blocks-suite' ); ?>"
			aria-valuemin="0"
			aria-valuemax="100"
			aria-orientation="horizontal"
			aria-valuenow="<?php echo esc_attr( (string) round( $initial_position ) ); ?>"
			style="left:<?php echo esc_attr( (string) $initial_position ); ?>%;"
			data-wp-style--left="state.handleLeft"
			data-wp-bind--aria-valuenow="state.roundedPosition"
			data-wp-on--keydown="actions.onKeyDown"
		>
			<span class="wp-block-pv-blocks-suite-before-after__handle-grip" aria-hidden="true"></span>
		</div>
	</div>
</div>
