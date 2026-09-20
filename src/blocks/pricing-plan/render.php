<?php
/**
 * Server-side render for the `pv-blocks-suite/pricing-plan` block.
 *
 * @package PV\BlocksSuite
 *
 * @var array<string, mixed> $attributes Block attributes.
 * @var string               $content    Rendered inner blocks markup (the plan's feature list).
 * @var WP_Block              $block     Block instance.
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$plan_name               = (string) ( $attributes['planName'] ?? '' );
$heading_level           = (int) ( $attributes['headingLevel'] ?? 3 );
$heading_level           = max( 2, min( 6, $heading_level ) );
$price                   = (string) ( $attributes['price'] ?? '' );
$price_period            = (string) ( $attributes['pricePeriod'] ?? '' );
$description             = (string) ( $attributes['description'] ?? '' );
$button_text             = (string) ( $attributes['buttonText'] ?? '' );
$button_url              = (string) ( $attributes['buttonUrl'] ?? '' );
$button_opens_in_new_tab = (bool) ( $attributes['buttonOpensInNewTab'] ?? false );
$is_featured             = (bool) ( $attributes['isFeatured'] ?? false );
$featured_label          = (string) ( $attributes['featuredLabel'] ?? '' );

// Unlike `style`/`class`/`id`/`aria-label`, get_block_wrapper_attributes()
// doesn't merge/omit other extra attributes gracefully for empty values
// (see the accordion-item `name` attribute gotcha), but `class` IS one of
// the merge-safe keys, so it's fine to pass it conditionally here.
$extra_wrapper_attributes = [];

if ( $is_featured ) {
	$extra_wrapper_attributes['class'] = 'is-featured';
}

$wrapper_attributes = get_block_wrapper_attributes( $extra_wrapper_attributes );
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by get_block_wrapper_attributes(). ?>>
	<?php if ( $is_featured && $featured_label ) : ?>
		<span class="wp-block-pv-blocks-suite-pricing-plan__featured-badge"><?php echo esc_html( $featured_label ); ?></span>
	<?php endif; ?>

	<?php if ( $plan_name ) : ?>
		<h<?php echo absint( $heading_level ); ?> class="wp-block-pv-blocks-suite-pricing-plan__plan-name"><?php echo wp_kses_post( $plan_name ); ?></h<?php echo absint( $heading_level ); ?>>
	<?php endif; ?>

	<?php if ( $price || $price_period ) : ?>
		<div class="wp-block-pv-blocks-suite-pricing-plan__price">
			<?php if ( $price ) : ?>
				<span class="wp-block-pv-blocks-suite-pricing-plan__price-amount"><?php echo wp_kses_post( $price ); ?></span>
			<?php endif; ?>
			<?php if ( $price_period ) : ?>
				<span class="wp-block-pv-blocks-suite-pricing-plan__price-period"><?php echo wp_kses_post( $price_period ); ?></span>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( $description ) : ?>
		<p class="wp-block-pv-blocks-suite-pricing-plan__description"><?php echo wp_kses_post( $description ); ?></p>
	<?php endif; ?>

	<div class="wp-block-pv-blocks-suite-pricing-plan__features">
		<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inner blocks are already rendered/escaped by core. ?>
	</div>

	<?php if ( $button_text && $button_url ) : ?>
		<a
			class="wp-block-pv-blocks-suite-pricing-plan__button"
			href="<?php echo esc_url( $button_url ); ?>"
			<?php if ( $button_opens_in_new_tab ) : ?>
				target="_blank" rel="noopener noreferrer"
			<?php endif; ?>
		>
			<?php echo wp_kses_post( $button_text ); ?>
			<?php if ( $button_opens_in_new_tab ) : ?>
				<span class="wp-block-pv-blocks-suite-pricing-plan__visually-hidden"><?php esc_html_e( '(opens in a new tab)', 'pv-blocks-suite' ); ?></span>
			<?php endif; ?>
		</a>
	<?php endif; ?>
</div>
