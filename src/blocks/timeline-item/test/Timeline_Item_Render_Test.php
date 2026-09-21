<?php
/**
 * Tests for the `pv-blocks-suite/timeline-item` block's server-side render.
 *
 * @package PV\BlocksSuite
 */

declare( strict_types=1 );

final class Timeline_Item_Render_Test extends WP_UnitTestCase {

	/**
	 * Renders the timeline-item block.
	 *
	 * @param array<string, mixed> $attrs Block attributes.
	 */
	private function render_item( array $attrs = [] ): string {
		return (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/timeline-item',
				'attrs'        => $attrs,
				'innerHTML'    => '',
				'innerContent' => [],
			]
		);
	}

	public function test_block_is_registered(): void {
		$this->assertTrue( WP_Block_Type_Registry::get_instance()->is_registered( 'pv-blocks-suite/timeline-item' ) );
	}

	public function test_renders_as_a_list_item_with_a_dot_marker(): void {
		$output = $this->render_item();

		$this->assertStringContainsString( '<li', $output );
		$this->assertStringContainsString( 'wp-block-pv-blocks-suite-timeline-item__dot', $output );
		$this->assertStringContainsString( '</li>', $output );
	}

	public function test_date_heading_and_description_are_rendered(): void {
		$output = $this->render_item(
			[
				'date'        => '2024',
				'heading'     => 'Series A funding',
				'description' => 'Raised funding to accelerate growth.',
			]
		);

		$this->assertStringContainsString( '<span class="wp-block-pv-blocks-suite-timeline-item__date">2024</span>', $output );
		$this->assertStringContainsString( '<h3 class="wp-block-pv-blocks-suite-timeline-item__heading">Series A funding</h3>', $output );
		$this->assertStringContainsString( '<p class="wp-block-pv-blocks-suite-timeline-item__description">Raised funding to accelerate growth.</p>', $output );
	}

	public function test_heading_level_is_configurable(): void {
		$output = $this->render_item(
			[
				'heading'      => 'Series A funding',
				'headingLevel' => 4,
			]
		);

		$this->assertStringContainsString( '<h4 class="wp-block-pv-blocks-suite-timeline-item__heading">Series A funding</h4>', $output );
	}

	public function test_heading_level_is_clamped_to_a_valid_range(): void {
		$output = $this->render_item(
			[
				'heading'      => 'Series A funding',
				'headingLevel' => 99,
			]
		);

		$this->assertStringContainsString( '<h6 class="wp-block-pv-blocks-suite-timeline-item__heading">Series A funding</h6>', $output );
	}

	public function test_empty_fields_are_omitted(): void {
		$output = $this->render_item();

		$this->assertStringNotContainsString( '<img', $output );
		$this->assertStringNotContainsString( '__date', $output );
		$this->assertStringNotContainsString( '__heading', $output );
		$this->assertStringNotContainsString( '__description', $output );
	}

	public function test_image_is_rendered_with_alt_text(): void {
		$output = $this->render_item(
			[
				'imageUrl' => 'https://example.org/photo.jpg',
				'imageAlt' => 'A launch event',
			]
		);

		$this->assertStringContainsString( 'src="https://example.org/photo.jpg"', $output );
		$this->assertStringContainsString( 'alt="A launch event"', $output );
	}

	public function test_image_alt_attribute_is_always_present_even_when_empty(): void {
		$output = $this->render_item( [ 'imageUrl' => 'https://example.org/photo.jpg' ] );

		// A missing `alt` attribute (as opposed to an empty one) makes
		// screen readers fall back to announcing the raw filename/URL —
		// alt="" explicitly marks the image as decorative instead.
		$this->assertStringContainsString( 'alt=""', $output );
	}

	public function test_date_heading_and_description_allow_safe_html_but_strip_scripts(): void {
		$output = $this->render_item(
			[
				'date'        => '<strong>2024</strong><script>alert(0)</script>',
				'heading'     => 'Hello <strong>world</strong><script>alert(1)</script>',
				'description' => 'Safe <em>text</em><script>alert(2)</script>',
			]
		);

		$this->assertStringContainsString( '<strong>2024</strong>', $output );
		$this->assertStringContainsString( '<strong>world</strong>', $output );
		$this->assertStringContainsString( '<em>text</em>', $output );
		$this->assertStringNotContainsString( '<script>', $output );
	}
}
