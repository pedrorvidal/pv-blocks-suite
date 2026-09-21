<?php
/**
 * Server-side render for the `pv-blocks-suite/testimonial-item` block.
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

$avatar_url  = (string) ( $attributes['avatarUrl'] ?? '' );
$avatar_alt  = (string) ( $attributes['avatarAlt'] ?? '' );
$quote       = (string) ( $attributes['quote'] ?? '' );
$name        = (string) ( $attributes['name'] ?? '' );
$author_role = (string) ( $attributes['role'] ?? '' );
$show_rating = (bool) ( $attributes['showRating'] ?? true );

$rating = (int) ( $attributes['rating'] ?? 5 );
$rating = max( 1, min( 5, $rating ) );

// The `style.border.radius` attribute isn't ours: it's injected
// automatically by the `supports.__experimentalBorder.radius`
// declaration in block.json. See cta/render.php for the full
// explanation of this pattern (single linked value vs. per-corner array,
// and why overflow needs to be forced to clip the avatar/quote to the
// radius).
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

// Same hand-written stroke/fill SVG star, matching icons.tsx's editor
// preview shape exactly — static, trusted markup (not user input), safe
// to output raw. Dashicons aren't used here since they're not guaranteed
// to be enqueued on the front end.
$star_path   = 'M12 2.5l2.9 6.6 7.1.6-5.4 4.7 1.6 7-6.2-3.8-6.2 3.8 1.6-7-5.4-4.7 7.1-.6z';
$star_filled = '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="' . $star_path . '" fill="currentColor"/></svg>';
$star_empty  = '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="' . $star_path . '" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/></svg>';

/* translators: %d: rating out of 5 stars. */
$rating_label = sprintf( __( 'Rated %d out of 5', 'pv-blocks-suite' ), $rating );
?>
<figure <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by get_block_wrapper_attributes(). ?>>
	<?php if ( $quote ) : ?>
		<blockquote class="wp-block-pv-blocks-suite-testimonial-item__quote"><?php echo wp_kses_post( $quote ); ?></blockquote>
	<?php endif; ?>

	<figcaption class="wp-block-pv-blocks-suite-testimonial-item__meta">
		<?php if ( $avatar_url ) : ?>
			<img
				class="wp-block-pv-blocks-suite-testimonial-item__avatar"
				src="<?php echo esc_url( $avatar_url ); ?>"
				alt="<?php echo esc_attr( $avatar_alt ); ?>"
			/>
		<?php endif; ?>

		<div class="wp-block-pv-blocks-suite-testimonial-item__person">
			<?php if ( $name ) : ?>
				<cite class="wp-block-pv-blocks-suite-testimonial-item__name"><?php echo wp_kses_post( $name ); ?></cite>
			<?php endif; ?>

			<?php if ( $author_role ) : ?>
				<span class="wp-block-pv-blocks-suite-testimonial-item__role"><?php echo esc_html( $author_role ); ?></span>
			<?php endif; ?>
		</div>

		<?php if ( $show_rating ) : ?>
			<div class="wp-block-pv-blocks-suite-testimonial-item__rating" role="img" aria-label="<?php echo esc_attr( $rating_label ); ?>">
				<?php for ( $position = 1; $position <= 5; $position++ ) : ?>
					<span class="wp-block-pv-blocks-suite-testimonial-item__star">
						<?php echo $position <= $rating ? $star_filled : $star_empty; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static trusted markup, not user input. ?>
					</span>
				<?php endfor; ?>
			</div>
		<?php endif; ?>
	</figcaption>
</figure>
