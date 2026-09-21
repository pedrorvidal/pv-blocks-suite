<?php
/**
 * Tests for the `pv-blocks-suite/tab-item` block's server-side render, in
 * isolation (no parent tabs block).
 *
 * The tablist/button wiring (label hoisting, aria-selected, tabindex) is
 * covered by Tabs_Render_Test, since that only exists when a tab-item is
 * actually rendered as a child of a real tabs block. This file only
 * covers what a single tab-item renders on its own: its panel.
 *
 * @package PV\BlocksSuite
 */

declare( strict_types=1 );

final class Tab_Item_Render_Test extends WP_UnitTestCase {

	/**
	 * Renders the tab-item block standalone (no parent context).
	 *
	 * @param array<string, mixed> $attrs      Block attributes.
	 * @param string               $inner_html Inner blocks markup.
	 */
	private function render_item( array $attrs = [], string $inner_html = '' ): string {
		return (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/tab-item',
				'attrs'        => $attrs,
				'innerHTML'    => $inner_html,
				'innerContent' => [ $inner_html ],
			]
		);
	}

	public function test_block_is_registered(): void {
		$this->assertTrue( WP_Block_Type_Registry::get_instance()->is_registered( 'pv-blocks-suite/tab-item' ) );
	}

	public function test_renders_as_a_tabpanel_with_matching_ids(): void {
		$output = $this->render_item( [ 'tabId' => 'tab-a' ] );

		$this->assertStringContainsString( 'role="tabpanel"', $output );
		$this->assertStringContainsString( 'id="pv-tab-panel-tab-a"', $output );
		$this->assertStringContainsString( 'aria-labelledby="pv-tab-button-tab-a"', $output );
	}

	public function test_panel_is_focusable(): void {
		$output = $this->render_item( [ 'tabId' => 'tab-a' ] );

		$this->assertStringContainsString( 'tabindex="0"', $output );
	}

	public function test_panel_content_is_preserved(): void {
		$output = $this->render_item( [ 'tabId' => 'tab-a' ], '<p>The panel content.</p>' );

		$this->assertStringContainsString( '<p>The panel content.</p>', $output );
	}

	public function test_panel_is_not_statically_hidden(): void {
		$output = $this->render_item( [ 'tabId' => 'tab-a' ] );

		$this->assertStringNotContainsString( ' hidden', $output );
	}

	public function test_interactivity_bind_directive_is_present(): void {
		$output = $this->render_item( [ 'tabId' => 'tab-a' ] );

		$this->assertStringContainsString( 'data-wp-bind--hidden="state.isHidden"', $output );
	}

	public function test_context_carries_the_tab_id(): void {
		$output = $this->render_item( [ 'tabId' => 'tab-a' ] );

		$this->assertStringContainsString( '"tabId":"tab-a"', $output );
	}

	public function test_no_custom_anchor_support_since_id_is_always_computed(): void {
		$block_type = WP_Block_Type_Registry::get_instance()->get_registered( 'pv-blocks-suite/tab-item' );

		$this->assertNotNull( $block_type );
		$this->assertFalse( $block_type->supports['anchor'] ?? false );
	}
}
