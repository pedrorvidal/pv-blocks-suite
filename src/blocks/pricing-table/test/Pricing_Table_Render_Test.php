<?php
/**
 * Tests for the `pv-blocks-suite/pricing-table` block's server-side render.
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

final class Pricing_Table_Render_Test extends WP_UnitTestCase {

	public function test_block_is_registered(): void {
		$this->assertTrue( WP_Block_Type_Registry::get_instance()->is_registered( 'pv-blocks-suite/pricing-table' ) );
	}

	public function test_pricing_plan_is_registered(): void {
		$this->assertTrue( WP_Block_Type_Registry::get_instance()->is_registered( 'pv-blocks-suite/pricing-plan' ) );
	}

	public function test_inner_blocks_content_is_preserved(): void {
		$output = (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/pricing-table',
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
				'blockName'    => 'pv-blocks-suite/pricing-table',
				'attrs'        => [],
				'innerHTML'    => '',
				'innerContent' => [],
			]
		);

		$this->assertStringContainsString( 'wp-block-pv-blocks-suite-pricing-table', $output );
	}

	public function test_nested_pricing_plan_renders_correctly(): void {
		$item_inner_html = '<p>Feature list</p>';

		$output = (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/pricing-table',
				'attrs'        => [],
				'innerBlocks'  => [
					[
						'blockName'    => 'pv-blocks-suite/pricing-plan',
						'attrs'        => [ 'planName' => 'Pro' ],
						'innerBlocks'  => [],
						'innerHTML'    => $item_inner_html,
						'innerContent' => [ $item_inner_html ],
					],
				],
				'innerHTML'    => '',
				'innerContent' => [ null ],
			]
		);

		$this->assertStringContainsString( 'Pro', $output );
		$this->assertStringContainsString( '<p>Feature list</p>', $output );
	}
}
