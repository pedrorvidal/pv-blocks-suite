<?php
/**
 * Tests for the `pv-blocks-suite/accordion` block's server-side render.
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

final class Accordion_Render_Test extends WP_UnitTestCase {

	public function test_block_is_registered(): void {
		$this->assertTrue( WP_Block_Type_Registry::get_instance()->is_registered( 'pv-blocks-suite/accordion' ) );
	}

	public function test_accordion_item_is_registered(): void {
		$this->assertTrue( WP_Block_Type_Registry::get_instance()->is_registered( 'pv-blocks-suite/accordion-item' ) );
	}

	public function test_inner_blocks_content_is_preserved(): void {
		$output = (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/accordion',
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
				'blockName'    => 'pv-blocks-suite/accordion',
				'attrs'        => [],
				'innerHTML'    => '',
				'innerContent' => [],
			]
		);

		$this->assertStringContainsString( 'wp-block-pv-blocks-suite-accordion', $output );
	}

	public function test_wrapper_has_role_group_for_assistive_tech(): void {
		$output = (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/accordion',
				'attrs'        => [],
				'innerHTML'    => '',
				'innerContent' => [],
			]
		);

		$this->assertStringContainsString( 'role="group"', $output );
	}

	/**
	 * Renders a full accordion with one nested accordion-item, exercising
	 * the real block-context propagation (providesContext/usesContext)
	 * between parent and child — the same mechanism WordPress uses for
	 * any nested dynamic blocks, not something this plugin implements
	 * itself.
	 */
	private function render_accordion_with_item( array $accordion_attrs, array $item_attrs, string $item_inner_html = '<p>Answer</p>' ): string {
		return (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/accordion',
				'attrs'        => $accordion_attrs,
				'innerBlocks'  => [
					[
						'blockName'    => 'pv-blocks-suite/accordion-item',
						'attrs'        => $item_attrs,
						'innerBlocks'  => [],
						'innerHTML'    => $item_inner_html,
						'innerContent' => [ $item_inner_html ],
					],
				],
				'innerHTML'    => '',
				'innerContent' => [ null ],
			]
		);
	}

	public function test_group_id_reaches_the_item_as_the_name_attribute(): void {
		$output = $this->render_accordion_with_item(
			[
				'groupId'           => 'test-group',
				'allowMultipleOpen' => false,
			],
			[ 'summary' => 'Question 1' ]
		);

		$this->assertStringContainsString( 'name="test-group"', $output );
	}

	public function test_name_attribute_is_omitted_when_multiple_open_is_allowed(): void {
		$output = $this->render_accordion_with_item(
			[
				'groupId'           => 'test-group',
				'allowMultipleOpen' => true,
			],
			[ 'summary' => 'Question 1' ]
		);

		$this->assertStringNotContainsString( 'name="test-group"', $output );
	}

	public function test_item_question_and_answer_are_rendered(): void {
		$output = $this->render_accordion_with_item(
			[ 'groupId' => 'test-group' ],
			[ 'summary' => 'What is PV Blocks Suite?' ],
			'<p>A suite of Gutenberg blocks.</p>'
		);

		$this->assertStringContainsString( 'What is PV Blocks Suite?', $output );
		$this->assertStringContainsString( '<p>A suite of Gutenberg blocks.</p>', $output );
	}
}
