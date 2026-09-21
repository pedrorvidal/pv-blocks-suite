<?php
/**
 * Server-side render for the `pv-blocks-suite/alert-box` block.
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

/** @var array<int, string> $valid_variants Whitelist: used for both the CSS class and the icon/label lookups below. */
$valid_variants = [ 'info', 'success', 'warning', 'error' ];

$variant = (string) ( $attributes['variant'] ?? 'info' );

if ( ! in_array( $variant, $valid_variants, true ) ) {
	$variant = 'info';
}

$heading   = (string) ( $attributes['heading'] ?? '' );
$message   = (string) ( $attributes['message'] ?? '' );
$show_icon = (bool) ( $attributes['showIcon'] ?? true );

// Screen-reader-only label carrying the alert's semantic type: sighted
// users get "this is a warning" from color + icon, this is the
// equivalent for screen-reader users — present regardless of whether the
// (purely decorative, aria-hidden) icon itself is shown.
$variant_labels = [
	'info'    => __( 'Info', 'pv-blocks-suite' ),
	'success' => __( 'Success', 'pv-blocks-suite' ),
	'warning' => __( 'Warning', 'pv-blocks-suite' ),
	'error'   => __( 'Error', 'pv-blocks-suite' ),
];

// Simple stroke-based SVGs (no external icon library), matching
// icons.tsx's editor preview shapes exactly — static, trusted markup
// (not user input), safe to output raw. Dashicons aren't used here since
// they're not guaranteed to be enqueued on the front end.
$variant_icons = [
	'info'    => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="2"/><line x1="12" y1="11" x2="12" y2="16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><circle cx="12" cy="7.5" r="1.25" fill="currentColor"/></svg>',
	'success' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="2"/><polyline points="7.5,12.5 10.5,15.5 16.5,9" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
	'warning' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M12 3.5 2.5 20h19z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><line x1="12" y1="10" x2="12" y2="14.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><circle cx="12" cy="17" r="1.1" fill="currentColor"/></svg>',
	'error'   => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="2"/><line x1="8.5" y1="8.5" x2="15.5" y2="15.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><line x1="15.5" y1="8.5" x2="8.5" y2="15.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
];

$wrapper_attributes = get_block_wrapper_attributes( [ 'class' => 'is-' . $variant ] );
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by get_block_wrapper_attributes(). ?>>
	<?php if ( $show_icon ) : ?>
		<span class="wp-block-pv-blocks-suite-alert-box__icon">
			<?php echo $variant_icons[ $variant ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static trusted markup, not user input. ?>
		</span>
	<?php endif; ?>

	<span class="wp-block-pv-blocks-suite-alert-box__visually-hidden"><?php echo esc_html( $variant_labels[ $variant ] . ': ' ); ?></span>

	<div class="wp-block-pv-blocks-suite-alert-box__content">
		<?php if ( $heading ) : ?>
			<strong class="wp-block-pv-blocks-suite-alert-box__heading"><?php echo wp_kses_post( $heading ); ?></strong>
		<?php endif; ?>

		<?php if ( $message ) : ?>
			<p class="wp-block-pv-blocks-suite-alert-box__message"><?php echo wp_kses_post( $message ); ?></p>
		<?php endif; ?>
	</div>
</div>
