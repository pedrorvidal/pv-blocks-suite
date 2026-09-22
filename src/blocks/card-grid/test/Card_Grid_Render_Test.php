<?php
/**
 * Tests for the `pv-blocks-suite/card-grid` block's server-side render.
 *
 * Renders through the real `render_block()` / `WP_Block` machinery (not by
 * including render.php directly), so attribute defaults from block.json are
 * applied the same way they are for a real request, and the block must
 * actually be registered — the same guarantee Block_Loader gives in
 * production.
 *
 * @package PV\BlocksSuite
 */

declare( strict_types = 1 );

final class Card_Grid_Render_Test extends WP_UnitTestCase {

	public function test_block_is_registered(): void {
		$this->assertTrue( WP_Block_Type_Registry::get_instance()->is_registered( 'pv-blocks-suite/card-grid' ) );
	}

	public function test_card_is_registered(): void {
		$this->assertTrue( WP_Block_Type_Registry::get_instance()->is_registered( 'pv-blocks-suite/card' ) );
	}

	public function test_inner_blocks_content_is_preserved(): void {
		$output = (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/card-grid',
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
				'blockName'    => 'pv-blocks-suite/card-grid',
				'attrs'        => [],
				'innerHTML'    => '',
				'innerContent' => [],
			]
		);

		$this->assertStringContainsString( 'wp-block-pv-blocks-suite-card-grid', $output );
	}

	public function test_columns_becomes_a_css_custom_property(): void {
		$output = (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/card-grid',
				'attrs'        => [ 'columns' => 4 ],
				'innerHTML'    => '',
				'innerContent' => [],
			]
		);

		$this->assertStringContainsString( '--card-grid-columns:4', $output );
	}

	public function test_columns_defaults_to_three(): void {
		$output = (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/card-grid',
				'attrs'        => [],
				'innerHTML'    => '',
				'innerContent' => [],
			]
		);

		$this->assertStringContainsString( '--card-grid-columns:3', $output );
	}

	public function test_columns_is_clamped_to_a_valid_range(): void {
		$output_too_low  = (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/card-grid',
				'attrs'        => [ 'columns' => 1 ],
				'innerHTML'    => '',
				'innerContent' => [],
			]
		);
		$output_too_high = (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/card-grid',
				'attrs'        => [ 'columns' => 10 ],
				'innerHTML'    => '',
				'innerContent' => [],
			]
		);

		$this->assertStringContainsString( '--card-grid-columns:2', $output_too_low );
		$this->assertStringContainsString( '--card-grid-columns:4', $output_too_high );
	}

	public function test_nested_card_renders_correctly(): void {
		$output = (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/card-grid',
				'attrs'        => [],
				'innerBlocks'  => [
					[
						'blockName'    => 'pv-blocks-suite/card',
						'attrs'        => [ 'heading' => 'Fast' ],
						'innerBlocks'  => [],
						'innerHTML'    => '',
						'innerContent' => [],
					],
				],
				'innerHTML'    => '',
				'innerContent' => [ null ],
			]
		);

		$this->assertStringContainsString( 'Fast', $output );
	}
}
