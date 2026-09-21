<?php
/**
 * Server-side render for the `pv-blocks-suite/team-member` block.
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

$avatar_url = (string) ( $attributes['avatarUrl'] ?? '' );
$avatar_alt = (string) ( $attributes['avatarAlt'] ?? '' );
$name       = (string) ( $attributes['name'] ?? '' );

$heading_level = (int) ( $attributes['headingLevel'] ?? 3 );
$heading_level = max( 2, min( 6, $heading_level ) );

// A variable simply called "role" collides with a WordPress global
// variable name as far as WPCS' GlobalVariablesOverride sniff is
// concerned, even in purely local scope with no `global` keyword in
// sight — the same trap already documented for the tablist/tab-item
// variables in tabs/render.php and for the equivalent field in
// testimonial-item/render.php's own history. Named with a prefix here
// to avoid it from the start.
$member_role = (string) ( $attributes['role'] ?? '' );
$bio         = (string) ( $attributes['bio'] ?? '' );

// The `style.border.radius` attribute isn't ours: it's injected
// automatically by the `supports.__experimentalBorder.radius`
// declaration in block.json. See cta/render.php for the full
// explanation of this pattern (single linked value vs. per-corner array,
// and why overflow needs to be forced to clip the avatar to the radius).
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
	<?php if ( $avatar_url ) : ?>
		<img
			class="wp-block-pv-blocks-suite-team-member__avatar"
			src="<?php echo esc_url( $avatar_url ); ?>"
			alt="<?php echo esc_attr( $avatar_alt ); ?>"
		/>
	<?php endif; ?>

	<?php if ( $name ) : ?>
		<h<?php echo absint( $heading_level ); ?> class="wp-block-pv-blocks-suite-team-member__name"><?php echo wp_kses_post( $name ); ?></h<?php echo absint( $heading_level ); ?>>
	<?php endif; ?>

	<?php if ( $member_role ) : ?>
		<p class="wp-block-pv-blocks-suite-team-member__role"><?php echo esc_html( $member_role ); ?></p>
	<?php endif; ?>

	<?php if ( $bio ) : ?>
		<p class="wp-block-pv-blocks-suite-team-member__bio"><?php echo wp_kses_post( $bio ); ?></p>
	<?php endif; ?>
</div>
