<?php
/**
 * Tests for the `pv-blocks-suite/stats-counter` block's server-side render.
 *
 * Renders through the real `render_block()` / `WP_Block` machinery (not by
 * including render.php directly), so attribute defaults from block.json are
 * applied the same way they are for a real request, and the block must
 * actually be registered — the same guarantee Block_Loader gives in
 * production.
 *
 * @package PV\BlocksSuite
 */

declare( strict_types=1 );

final class Stats_Counter_Render_Test extends WP_UnitTestCase {

	public function test_block_is_registered(): void {
		$this->assertTrue( WP_Block_Type_Registry::get_instance()->is_registered( 'pv-blocks-suite/stats-counter' ) );
	}

	public function test_stat_item_is_registered(): void {
		$this->assertTrue( WP_Block_Type_Registry::get_instance()->is_registered( 'pv-blocks-suite/stat-item' ) );
	}

	public function test_inner_blocks_content_is_preserved(): void {
		$output = (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/stats-counter',
				'attrs'        => [],
				'innerHTML'    => '<p>Marker content</p>',
				'innerContent' => [ '<p>Marker content</p>' ],
			]
		);

		$this->assertStringContainsString( '<p>Marker content</p>', $output );
	}

	public function test_wrapper_has_the_block_class(): void {
		$output = (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/stats-counter',
				'attrs'        => [],
				'innerHTML'    => '',
				'innerContent' => [],
			]
		);

		$this->assertStringContainsString( 'wp-block-pv-blocks-suite-stats-counter', $output );
	}

	public function test_columns_becomes_a_css_custom_property(): void {
		$output = (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/stats-counter',
				'attrs'        => [ 'columns' => 4 ],
				'innerHTML'    => '',
				'innerContent' => [],
			]
		);

		$this->assertStringContainsString( '--stats-counter-columns:4', $output );
	}

	public function test_columns_defaults_to_three(): void {
		$output = (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/stats-counter',
				'attrs'        => [],
				'innerHTML'    => '',
				'innerContent' => [],
			]
		);

		$this->assertStringContainsString( '--stats-counter-columns:3', $output );
	}

	public function test_columns_is_clamped_to_a_valid_range(): void {
		$output_too_low  = (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/stats-counter',
				'attrs'        => [ 'columns' => 1 ],
				'innerHTML'    => '',
				'innerContent' => [],
			]
		);
		$output_too_high = (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/stats-counter',
				'attrs'        => [ 'columns' => 10 ],
				'innerHTML'    => '',
				'innerContent' => [],
			]
		);

		$this->assertStringContainsString( '--stats-counter-columns:2', $output_too_low );
		$this->assertStringContainsString( '--stats-counter-columns:4', $output_too_high );
	}

	/**
	 * Renders a full stats-counter with one nested stat-item, exercising
	 * the real block-context propagation (providesContext/usesContext)
	 * between parent and child for `animationDuration` — the same
	 * mechanism `accordion`/`accordion-item` already use for
	 * `groupId`/`allowMultipleOpen`.
	 */
	private function render_stats_counter_with_item( array $parent_attrs, array $item_attrs ): string {
		return (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/stats-counter',
				'attrs'        => $parent_attrs,
				'innerBlocks'  => [
					[
						'blockName'    => 'pv-blocks-suite/stat-item',
						'attrs'        => $item_attrs,
						'innerBlocks'  => [],
						'innerHTML'    => '',
						'innerContent' => [],
					],
				],
				'innerHTML'    => '',
				'innerContent' => [ null ],
			]
		);
	}

	public function test_animation_duration_reaches_the_item_as_context(): void {
		$output = $this->render_stats_counter_with_item(
			[ 'animationDuration' => 3500 ],
			[ 'value' => 42 ]
		);

		$this->assertStringContainsString( '"duration":3500', $output );
	}

	public function test_nested_stat_item_renders_correctly(): void {
		$output = $this->render_stats_counter_with_item(
			[],
			[
				'value'  => 500,
				'suffix' => '+',
				'label'  => 'Happy Clients',
			]
		);

		$this->assertStringContainsString( '500+', $output );
		$this->assertStringContainsString( 'Happy Clients', $output );
	}
}
