<?php
/**
 * Tests for the `pv-blocks-suite/card` block's server-side render.
 *
 * @package PV\BlocksSuite
 */

declare( strict_types=1 );

final class Card_Render_Test extends WP_UnitTestCase {

	/**
	 * Renders the card block.
	 *
	 * @param array<string, mixed> $attrs Block attributes.
	 */
	private function render_card( array $attrs = [] ): string {
		return (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/card',
				'attrs'        => $attrs,
				'innerHTML'    => '',
				'innerContent' => [],
			]
		);
	}

	public function test_block_is_registered(): void {
		$this->assertTrue( WP_Block_Type_Registry::get_instance()->is_registered( 'pv-blocks-suite/card' ) );
	}

	public function test_heading_and_description_are_rendered(): void {
		$output = $this->render_card(
			[
				'heading'     => 'Fast',
				'description' => 'Built for speed.',
			]
		);

		$this->assertStringContainsString( '<h3 class="wp-block-pv-blocks-suite-card__heading">Fast</h3>', $output );
		$this->assertStringContainsString( '<p class="wp-block-pv-blocks-suite-card__description">Built for speed.</p>', $output );
	}

	public function test_heading_level_is_configurable(): void {
		$output = $this->render_card(
			[
				'heading'      => 'Fast',
				'headingLevel' => 4,
			]
		);

		$this->assertStringContainsString( '<h4 class="wp-block-pv-blocks-suite-card__heading">Fast</h4>', $output );
	}

	public function test_heading_level_is_clamped_to_a_valid_range(): void {
		$output = $this->render_card(
			[
				'heading'      => 'Fast',
				'headingLevel' => 99,
			]
		);

		$this->assertStringContainsString( '<h6 class="wp-block-pv-blocks-suite-card__heading">Fast</h6>', $output );
	}

	public function test_empty_fields_are_omitted(): void {
		$output = $this->render_card();

		$this->assertStringNotContainsString( '<img', $output );
		$this->assertStringNotContainsString( '__heading', $output );
		$this->assertStringNotContainsString( '__description', $output );
		$this->assertStringNotContainsString( '<a ', $output );
	}

	public function test_image_is_rendered_with_alt_text(): void {
		$output = $this->render_card(
			[
				'imageUrl' => 'https://example.org/photo.jpg',
				'imageAlt' => 'A photo of a mountain',
			]
		);

		$this->assertStringContainsString( 'src="https://example.org/photo.jpg"', $output );
		$this->assertStringContainsString( 'alt="A photo of a mountain"', $output );
	}

	public function test_image_alt_attribute_is_always_present_even_when_empty(): void {
		$output = $this->render_card( [ 'imageUrl' => 'https://example.org/photo.jpg' ] );

		// A missing `alt` attribute (as opposed to an empty one) makes
		// screen readers fall back to announcing the raw filename/URL —
		// alt="" explicitly marks the image as decorative instead.
		$this->assertStringContainsString( 'alt=""', $output );
	}

	public function test_button_is_rendered_when_text_and_url_are_set(): void {
		$output = $this->render_card(
			[
				'buttonText' => 'Learn more',
				'buttonUrl'  => 'https://example.org/learn-more',
			]
		);

		$this->assertStringContainsString( 'href="https://example.org/learn-more"', $output );
		$this->assertMatchesRegularExpression( '/>\s*Learn more\s*<\/a>/', $output );
	}

	public function test_button_is_omitted_when_text_or_url_is_missing(): void {
		$output_no_url  = $this->render_card( [ 'buttonText' => 'Learn more' ] );
		$output_no_text = $this->render_card( [ 'buttonUrl' => 'https://example.org/learn-more' ] );

		$this->assertStringNotContainsString( '<a ', $output_no_url );
		$this->assertStringNotContainsString( '<a ', $output_no_text );
	}

	public function test_button_hints_it_opens_in_a_new_tab_for_screen_readers(): void {
		$output = $this->render_card(
			[
				'buttonText'          => 'Learn more',
				'buttonUrl'           => 'https://example.org/learn-more',
				'buttonOpensInNewTab' => true,
			]
		);

		$this->assertStringContainsString( 'target="_blank"', $output );
		$this->assertStringContainsString( 'rel="noopener noreferrer"', $output );
		$this->assertStringContainsString( '(opens in a new tab)', $output );
	}

	public function test_overflow_hidden_when_border_radius_is_set(): void {
		$output = $this->render_card(
			[
				'style' => [
					'border' => [ 'radius' => '12px' ],
				],
			]
		);

		$this->assertStringContainsString( 'overflow:hidden', $output );
	}

	public function test_overflow_is_omitted_when_no_border_radius_is_set(): void {
		$output = $this->render_card();

		$this->assertStringNotContainsString( 'overflow', $output );
	}

	public function test_heading_and_description_allow_safe_html_but_strip_scripts(): void {
		$output = $this->render_card(
			[
				'heading'     => 'Hello <strong>world</strong><script>alert(1)</script>',
				'description' => 'Safe <em>text</em><script>alert(2)</script>',
			]
		);

		$this->assertStringContainsString( '<strong>world</strong>', $output );
		$this->assertStringContainsString( '<em>text</em>', $output );
		$this->assertStringNotContainsString( '<script>', $output );
	}

	public function test_image_url_is_escaped(): void {
		$output = $this->render_card(
			[ 'imageUrl' => 'https://example.org/photo.jpg?a=1&b=2' ]
		);

		$this->assertStringContainsString( 'src="https://example.org/photo.jpg?a=1&#038;b=2"', $output );
	}
}
