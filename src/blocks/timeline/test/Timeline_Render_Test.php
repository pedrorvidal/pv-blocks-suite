<?php
/**
 * Tests for the `pv-blocks-suite/timeline` block's server-side render.
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

final class Timeline_Render_Test extends WP_UnitTestCase {

	public function test_block_is_registered(): void {
		$this->assertTrue( WP_Block_Type_Registry::get_instance()->is_registered( 'pv-blocks-suite/timeline' ) );
	}

	public function test_timeline_item_is_registered(): void {
		$this->assertTrue( WP_Block_Type_Registry::get_instance()->is_registered( 'pv-blocks-suite/timeline-item' ) );
	}

	public function test_renders_as_an_ordered_list(): void {
		$output = (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/timeline',
				'attrs'        => [],
				'innerHTML'    => '',
				'innerContent' => [],
			]
		);

		$this->assertStringContainsString( '<ol', $output );
		$this->assertStringContainsString( '</ol>', $output );
	}

	public function test_inner_blocks_content_is_preserved(): void {
		$output = (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/timeline',
				'attrs'        => [],
				'innerHTML'    => '<li>Marker content</li>',
				'innerContent' => [ '<li>Marker content</li>' ],
			]
		);

		$this->assertStringContainsString( '<li>Marker content</li>', $output );
	}

	public function test_wrapper_has_the_block_class(): void {
		$output = (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/timeline',
				'attrs'        => [],
				'innerHTML'    => '',
				'innerContent' => [],
			]
		);

		$this->assertStringContainsString( 'wp-block-pv-blocks-suite-timeline', $output );
	}

	public function test_nested_timeline_item_renders_correctly(): void {
		$output = (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/timeline',
				'attrs'        => [],
				'innerBlocks'  => [
					[
						'blockName'    => 'pv-blocks-suite/timeline-item',
						'attrs'        => [ 'heading' => 'Company founded' ],
						'innerBlocks'  => [],
						'innerHTML'    => '',
						'innerContent' => [],
					],
				],
				'innerHTML'    => '',
				'innerContent' => [ null ],
			]
		);

		$this->assertStringContainsString( 'Company founded', $output );
	}
}
