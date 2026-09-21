<?php
/**
 * Server-side render for the `pv-blocks-suite/tabs` block.
 *
 * A correct ARIA tabs pattern needs every tab BUTTON grouped together in
 * one role="tablist", and every PANEL grouped together afterward — not
 * interleaved button+panel pairs the way each tab-item child naturally
 * bundles them together for editing. So unlike every other parent block in
 * this project (card-grid, accordion, testimonials, stats-counter), which
 * just wrap $content as-is, this render.php actively restructures its
 * children's data: it reads each tab-item's label/tabId directly off
 * $block->inner_blocks (WordPress' already attribute-resolved list of
 * child WP_Block instances) to build the tablist itself, while $content
 * (the children's own pre-rendered output — each tab-item's own
 * render.php produces just its panel) still supplies the panels.
 *
 * @package PV\BlocksSuite
 *
 * @var array<string, mixed> $attributes Block attributes.
 * @var string               $content    Rendered inner blocks markup (each tab-item's own panel).
 * @var WP_Block              $block     Block instance; used to read child attributes directly.
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Named $tab_items, not $tabs: WPCS' GlobalVariablesOverride sniff
// flags $tabs specifically as colliding with a WordPress global variable
// name, even here in purely local scope with no `global` keyword in
// sight — the same class of trap this project already hit with $role in
// testimonial-item/render.php.
$tab_items = [];

foreach ( $block->inner_blocks as $inner_block ) {
	if ( 'pv-blocks-suite/tab-item' !== $inner_block->name ) {
		continue;
	}

	$tab_id = (string) ( $inner_block->attributes['tabId'] ?? '' );

	// tabId is generated client-side on insert (see tab-item/edit.tsx), so
	// this shouldn't happen in practice — but a tab with no stable id
	// can't be wired to its panel via aria-controls/aria-labelledby, so
	// skip it rather than render a broken tab button.
	if ( '' === $tab_id ) {
		continue;
	}

	$tab_items[] = [
		'id'    => $tab_id,
		'label' => (string) ( $inner_block->attributes['label'] ?? '' ),
	];
}

$active_tab_id = $tab_items[0]['id'] ?? '';

$wrapper_attributes = get_block_wrapper_attributes(
	[ 'data-wp-interactive' => 'pv-blocks-suite/tabs' ]
);

$context = wp_interactivity_data_wp_context( [ 'activeTabId' => $active_tab_id ] );
?>
<div <?php echo $wrapper_attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by get_block_wrapper_attributes(). ?> <?php echo $context; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by wp_interactivity_data_wp_context(). ?>>
	<div role="tablist" aria-label="<?php esc_attr_e( 'Tabs', 'pv-blocks-suite' ); ?>" class="wp-block-pv-blocks-suite-tabs__tablist">
		<?php foreach ( $tab_items as $index => $tab_item ) : ?>
			<?php
			$is_active      = 0 === $index;
			$button_context = wp_interactivity_data_wp_context( [ 'tabId' => $tab_item['id'] ] );
			?>
			<button
				type="button"
				role="tab"
				id="pv-tab-button-<?php echo esc_attr( $tab_item['id'] ); ?>"
				aria-controls="pv-tab-panel-<?php echo esc_attr( $tab_item['id'] ); ?>"
				aria-selected="<?php echo esc_attr( $is_active ? 'true' : 'false' ); ?>"
				tabindex="<?php echo esc_attr( $is_active ? '0' : '-1' ); ?>"
				data-tab-id="<?php echo esc_attr( $tab_item['id'] ); ?>"
				class="wp-block-pv-blocks-suite-tabs__tab"
				data-wp-on--click="actions.selectTab"
				data-wp-on--keydown="actions.onTabKeyDown"
				data-wp-bind--aria-selected="state.ariaSelected"
				data-wp-bind--tabindex="state.tabIndexValue"
				<?php echo $button_context; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped by wp_interactivity_data_wp_context(). ?>
			><?php echo wp_kses_post( $tab_item['label'] ); ?></button>
		<?php endforeach; ?>
	</div>
	<div class="wp-block-pv-blocks-suite-tabs__panels">
		<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- inner blocks (tab panels) are already rendered/escaped by their own render.php. ?>
	</div>
</div>
