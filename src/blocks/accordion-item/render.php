<?php
/**
 * Server-side render for the `pv-blocks-suite/accordion-item` block.
 *
 * @package PV\BlocksSuite
 *
 * @var array<string, mixed> $attributes Block attributes.
 * @var string               $content    Rendered inner blocks markup (the item's panel content).
 * @var WP_Block              $block     Block instance; carries context provided by the parent accordion.
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$summary             = (string) ( $attributes['summary'] ?? '' );
$open_by_default     = (bool) ( $attributes['openByDefault'] ?? false );
$group_id            = (string) ( $block->context['pv-blocks-suite/accordionGroupId'] ?? '' );
$allow_multiple_open = (bool) ( $block->context['pv-blocks-suite/accordionAllowMultipleOpen'] ?? false );

// The native `name` attribute is what makes sibling <details> elements
// mutually exclusive (like radio buttons), with zero JavaScript. Only set
// it when the parent accordion wants single-open behavior and actually
// provided a group id. Unlike style/class/id/aria-label,
// get_block_wrapper_attributes() does NOT omit an arbitrary extra
// attribute for an empty string — it would render a literal `name=""` if
// passed unconditionally, so the key itself is left out instead.
$extra_wrapper_attributes = [];

if ( ! $allow_multiple_open && '' !== $group_id ) {
	$extra_wrapper_attributes['name'] = $group_id;
}

$wrapper_attributes = get_block_wrapper_attributes( $extra_wrapper_attributes );
?>
<details
	<?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by get_block_wrapper_attributes(). ?>
	<?php if ( $open_by_default ) : ?>
		open
	<?php endif; ?>
>
	<summary class="wp-block-pv-blocks-suite-accordion-item__summary"><?php echo wp_kses_post( $summary ); ?></summary>
	<div class="wp-block-pv-blocks-suite-accordion-item__content">
		<div class="wp-block-pv-blocks-suite-accordion-item__content-inner">
			<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inner blocks are already rendered/escaped by core. ?>
		</div>
	</div>
</details>
