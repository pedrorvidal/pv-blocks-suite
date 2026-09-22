<?php
/**
 * Tests for the `pv-blocks-suite/testimonial-item` block's server-side
 * render.
 *
 * @package PV\BlocksSuite
 */

declare( strict_types = 1 );

final class Testimonial_Item_Render_Test extends WP_UnitTestCase {

	/**
	 * Renders the testimonial-item block.
	 *
	 * @param array<string, mixed> $attrs Block attributes.
	 */
	private function render_testimonial_item( array $attrs = [] ): string {
		return (string) render_block(
			[
				'blockName'    => 'pv-blocks-suite/testimonial-item',
				'attrs'        => $attrs,
				'innerHTML'    => '',
				'innerContent' => [],
			]
		);
	}

	public function test_block_is_registered(): void {
		$this->assertTrue( WP_Block_Type_Registry::get_instance()->is_registered( 'pv-blocks-suite/testimonial-item' ) );
	}

	public function test_quote_and_name_are_rendered(): void {
		$output = $this->render_testimonial_item(
			[
				'quote' => 'This product changed how we work.',
				'name'  => 'Alex Rivera',
			]
		);

		$this->assertStringContainsString( '<blockquote class="wp-block-pv-blocks-suite-testimonial-item__quote">This product changed how we work.</blockquote>', $output );
		$this->assertStringContainsString( '<cite class="wp-block-pv-blocks-suite-testimonial-item__name">Alex Rivera</cite>', $output );
	}

	public function test_role_is_rendered_when_set(): void {
		$output = $this->render_testimonial_item(
			[
				'name' => 'Alex Rivera',
				'role' => 'CTO, Nimbus Labs',
			]
		);

		$this->assertStringContainsString( '<span class="wp-block-pv-blocks-suite-testimonial-item__role">CTO, Nimbus Labs</span>', $output );
	}

	public function test_empty_fields_are_omitted(): void {
		$output = $this->render_testimonial_item( [ 'showRating' => false ] );

		$this->assertStringNotContainsString( '<img', $output );
		$this->assertStringNotContainsString( '__quote', $output );
		$this->assertStringNotContainsString( '__name', $output );
		$this->assertStringNotContainsString( '__role', $output );
		$this->assertStringNotContainsString( '__rating', $output );
	}

	public function test_avatar_is_rendered_with_alt_text(): void {
		$output = $this->render_testimonial_item(
			[
				'avatarUrl' => 'https://example.org/photo.jpg',
				'avatarAlt' => 'Portrait of Alex Rivera',
			]
		);

		$this->assertStringContainsString( 'src="https://example.org/photo.jpg"', $output );
		$this->assertStringContainsString( 'alt="Portrait of Alex Rivera"', $output );
	}

	public function test_avatar_alt_attribute_is_always_present_even_when_empty(): void {
		$output = $this->render_testimonial_item( [ 'avatarUrl' => 'https://example.org/photo.jpg' ] );

		// A missing `alt` attribute (as opposed to an empty one) makes
		// screen readers fall back to announcing the raw filename/URL —
		// alt="" explicitly marks the image as decorative instead.
		$this->assertStringContainsString( 'alt=""', $output );
	}

	public function test_rating_is_shown_by_default(): void {
		$output = $this->render_testimonial_item();

		$this->assertStringContainsString( '__rating', $output );
	}

	public function test_rating_is_omitted_when_show_rating_is_false(): void {
		$output = $this->render_testimonial_item( [ 'showRating' => false ] );

		$this->assertStringNotContainsString( '__rating', $output );
	}

	public function test_rating_defaults_to_five_filled_stars(): void {
		$output = $this->render_testimonial_item();

		$filled_count = substr_count( $output, 'fill="currentColor"' );

		$this->assertSame( 5, $filled_count );
		$this->assertStringContainsString( 'aria-label="Rated 5 out of 5"', $output );
	}

	public function test_rating_renders_the_configured_number_of_filled_stars(): void {
		$output = $this->render_testimonial_item( [ 'rating' => 3 ] );

		$filled_count = substr_count( $output, 'fill="currentColor"' );

		$this->assertSame( 3, $filled_count );
		$this->assertStringContainsString( 'aria-label="Rated 3 out of 5"', $output );
	}

	public function test_rating_is_clamped_to_a_valid_range(): void {
		$output_too_low  = $this->render_testimonial_item( [ 'rating' => 0 ] );
		$output_too_high = $this->render_testimonial_item( [ 'rating' => 9 ] );

		$this->assertStringContainsString( 'aria-label="Rated 1 out of 5"', $output_too_low );
		$this->assertStringContainsString( 'aria-label="Rated 5 out of 5"', $output_too_high );
	}

	public function test_overflow_hidden_when_border_radius_is_set(): void {
		$output = $this->render_testimonial_item(
			[
				'style' => [
					'border' => [ 'radius' => '12px' ],
				],
			]
		);

		$this->assertStringContainsString( 'overflow:hidden', $output );
	}

	public function test_overflow_is_omitted_when_no_border_radius_is_set(): void {
		$output = $this->render_testimonial_item();

		$this->assertStringNotContainsString( 'overflow', $output );
	}

	public function test_quote_and_name_allow_safe_html_but_strip_scripts(): void {
		$output = $this->render_testimonial_item(
			[
				'quote' => 'Truly <strong>excellent</strong><script>alert(1)</script>',
				'name'  => 'Alex <em>Rivera</em><script>alert(2)</script>',
			]
		);

		$this->assertStringContainsString( '<strong>excellent</strong>', $output );
		$this->assertStringContainsString( '<em>Rivera</em>', $output );
		$this->assertStringNotContainsString( '<script>', $output );
	}

	public function test_avatar_url_is_escaped(): void {
		$output = $this->render_testimonial_item(
			[ 'avatarUrl' => 'https://example.org/photo.jpg?a=1&b=2' ]
		);

		$this->assertStringContainsString( 'src="https://example.org/photo.jpg?a=1&#038;b=2"', $output );
	}
}
